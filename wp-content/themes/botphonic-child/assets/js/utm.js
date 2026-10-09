/**
 * UTM persistence.
 *
 * 1. Captures utm_* parameters from the landing URL and stores them (cookie
 *    shared across *.botphonic.ai + localStorage fallback).
 * 2. When a visitor clicks a link to this site or to the product app
 *    (app.botphonic.ai), the stored UTMs are appended to that link at click
 *    time — rendered HTML is never rewritten, so crawlers, canonical URLs and
 *    cached pages are untouched.
 * 3. Copies the stored UTMs into Contact Form 7 hidden fields right before
 *    submit (both the `utm_source` and `utm_source_cf7` naming styles used by
 *    the forms / mail templates), plus gclid, the external referrer
 *    (handl_ref) and the page URL (full_url / handl_url_base) when a form has
 *    those fields — so the forms no longer depend on the HandL plugin.
 *
 * 4. Paid-ad visitors (utm_source FacebookAds / GoogleAds / OpenAIAds) are sent
 *    from /contact/ to the app register page instead, with their UTMs.
 *
 * Visitors who never arrived with UTMs get unchanged links and empty UTM fields.
 * Exposes window.BotphonicUTM.decorate(url) for scripted redirects.
 */
(function (w, d) {
	"use strict";

	var CFG = w.botphonicUtmConfig || {};
	var KEYS = ["utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];
	var FORM_KEYS = ["utm_source", "utm_medium", "utm_campaign"];
	var STORE = "bp_utm";
	var META = "bp_meta";
	// Non-UTM lead fields the forms post (filled only when the form already has them).
	var EXTRA_FIELDS = {
		gclid: ["gclid", "gclid_cf7"],
		ref: ["handl_ref", "handl_ref_cf7"],
		url: ["full_url", "handl_url", "handl_url_cf7"],
		base: ["handl_url_base", "handl_url_base_cf7"]
	};
	var DAYS = CFG.days || 30;
	var SITE_HOSTS = (CFG.siteHosts || []).concat([location.host]).map(lc);
	var APP_HOSTS = (CFG.appHosts || ["app.botphonic.ai"]).map(lc);
	var SKIP_PATH = /\/(wp-admin|wp-login\.php|wp-json|xmlrpc\.php|wp-content|wp-includes|feed)(\/|$)|\.(pdf|jpe?g|png|gif|webp|svg|ico|zip|rar|docx?|xlsx?|pptx?|csv|txt|xml|mp3|mp4|webm|mov)$/i;
	// utm_source values (case-insensitive) whose /contact/ visits go to the app register page.
	var REGISTER_SOURCES = (CFG.registerSources || ["FacebookAds", "GoogleAds", "OpenAIAds"]).map(lc);
	var REGISTER_URL = CFG.registerUrl || "https://app.botphonic.ai/register/";
	var CONTACT_PATH = /\/contact\/?$/i;
	var IS_BOT = /bot|crawl|spider|slurp|facebookexternalhit|linkedin|lighthouse|headless/i.test(navigator.userAgent);

	function lc(s) {
		return String(s).toLowerCase();
	}

	function clean(v) {
		return String(v == null ? "" : v).trim().slice(0, 200);
	}

	/* ------------------------------ storage ------------------------------ */

	function cookieDomain() {
		var h = location.hostname;
		return /(^|\.)botphonic\.ai$/i.test(h) ? "; domain=.botphonic.ai" : "";
	}

	function save(name, data) {
		var json = JSON.stringify(data);
		try {
			d.cookie = name + "=" + encodeURIComponent(json) + "; path=/; max-age=" + DAYS * 86400 +
				cookieDomain() + "; SameSite=Lax" + (location.protocol === "https:" ? "; Secure" : "");
		} catch (e) {}
		try {
			w.localStorage.setItem(name, json);
		} catch (e) {}
	}

	function load(name) {
		var raw = null;
		var m = d.cookie.match(new RegExp("(?:^|; )" + name + "=([^;]*)"));
		try {
			raw = m ? decodeURIComponent(m[1]) : w.localStorage.getItem(name);
		} catch (e) {}
		if (!raw) return null;
		try {
			var data = JSON.parse(raw);
			return data && !(data.t && Date.now() - data.t > DAYS * 86400000) ? data : null;
		} catch (e) {
			return null;
		}
	}

	function hasAny(p) {
		return !!p && KEYS.some(function (k) {
			return !!p[k];
		});
	}

	/* ------------------------------ capture ------------------------------ */

	function capture() {
		var qs = new URLSearchParams(location.search);
		var p = {};
		KEYS.forEach(function (k) {
			var v = clean(qs.get(k));
			if (v) p[k] = v;
		});
		// A new campaign landing replaces the previous set entirely (last touch).
		if (hasAny(p)) {
			save(STORE, { p: p, t: Date.now() });
			return p;
		}
		var data = load(STORE);
		return data && hasAny(data.p) ? data.p : null;
	}

	// gclid (Google Ads auto-tagging) and the external referrer of the visit.
	function captureMeta() {
		var meta = load(META) || {};
		var gclid = clean(new URLSearchParams(location.search).get("gclid"));
		var ref = "";
		try {
			if (d.referrer && SITE_HOSTS.indexOf(lc(new URL(d.referrer).host)) === -1) ref = clean(d.referrer).slice(0, 200);
		} catch (e) {}
		if (gclid || ref) {
			if (gclid) meta.gclid = gclid;
			if (ref) meta.ref = ref;
			meta.t = Date.now();
			save(META, meta);
		}
		return meta;
	}

	var utm = IS_BOT ? null : capture();
	var meta = IS_BOT ? {} : captureMeta();

	function sendsToRegister() {
		return !!utm && REGISTER_SOURCES.indexOf(lc(utm.utm_source || "")) !== -1;
	}

	/* ------------------------------ links -------------------------------- */

	function decorate(href) {
		if (!hasAny(utm) || !href) return href;
		var url;
		try {
			url = new URL(href, location.href);
		} catch (e) {
			return href;
		}
		if (!/^https?:$/.test(url.protocol)) return href;

		var host = lc(url.host);
		var internal = SITE_HOSTS.indexOf(host) !== -1;
		if (!internal && APP_HOSTS.indexOf(lc(url.hostname)) === -1) return href;

		if (internal) {
			if (SKIP_PATH.test(url.pathname)) return href;
			// In-page anchor (#section) on the current page: adding a query would reload it.
			if (url.hash && url.pathname === location.pathname) return href;
			if (CONTACT_PATH.test(url.pathname) && sendsToRegister()) return decorate(REGISTER_URL);
		}

		// A link that already carries its own campaign wins.
		for (var i = 0; i < KEYS.length; i++) {
			if (url.searchParams.has(KEYS[i])) return href;
		}

		KEYS.forEach(function (k) {
			if (utm[k]) url.searchParams.set(k, utm[k]);
		});
		return url.toString();
	}

	function onLinkIntent(e) {
		if (!hasAny(utm)) return;
		var a = e.target && e.target.closest ? e.target.closest("a[href]") : null;
		if (!a || typeof a.href !== "string" || a.hasAttribute("download")) return;
		var raw = a.getAttribute("href");
		if (!raw || raw.charAt(0) === "#" || /^(mailto|tel|sms|javascript):/i.test(raw)) return;
		var next = decorate(a.href);
		if (next !== a.href) a.href = next;
	}

	// mousedown/touchstart run before the browser reads the href for
	// left/middle/ctrl/right-click ("open in new tab"); click + keydown cover keyboard.
	["mousedown", "touchstart", "keydown", "click"].forEach(function (type) {
		d.addEventListener(type, function (e) {
			if (type === "keydown" && e.key !== "Enter") return;
			onLinkIntent(e);
		}, { capture: true, passive: true });
	});

	/* ------------------------- contact -> register ----------------------- */

	// Direct visit to /contact/ (ad landing URL, typed, bookmark). Not inside the
	// Elementor editor / previews, which load the page in an iframe.
	if (CONTACT_PATH.test(location.pathname) && sendsToRegister() && w.self === w.top &&
		!/[?&](elementor-preview|preview|preview_id)=/.test(location.search)) {
		location.replace(decorate(REGISTER_URL));
	}

	/* ------------------------------ CF7 forms ---------------------------- */

	function fillForm(form) {
		FORM_KEYS.forEach(function (k) {
			var names = [k, k + "_cf7"];
			var inputs = [];
			names.forEach(function (n) {
				inputs = inputs.concat([].slice.call(form.querySelectorAll('input[name="' + n + '"]')));
			});

			// Placeholder values like "utm_source" (from a tag default) are not real data.
			var existing = "";
			inputs.forEach(function (el) {
				if (names.indexOf(el.value) !== -1) el.value = "";
				if (!existing && el.value) existing = el.value;
			});

			var value = (utm && utm[k]) || existing;

			names.forEach(function (n) {
				if (!form.querySelector('input[name="' + n + '"]')) {
					var el = d.createElement("input");
					el.type = "hidden";
					el.name = n;
					el.setAttribute("data-bp-utm", "");
					form.appendChild(el);
					inputs.push(el);
				}
			});
			inputs.forEach(function (el) {
				el.value = value;
			});
		});

		var extra = {
			gclid: meta.gclid || "",
			ref: meta.ref || "",
			url: location.href,
			base: location.origin + location.pathname
		};
		Object.keys(EXTRA_FIELDS).forEach(function (k) {
			var names = EXTRA_FIELDS[k];
			names.forEach(function (n) {
				[].forEach.call(form.querySelectorAll('input[name="' + n + '"]'), function (el) {
					if (names.indexOf(el.value) !== -1 || el.value === "gclid") el.value = "";
					// Keep a value another script (HandL) already set when we have nothing better.
					if (extra[k]) el.value = extra[k];
				});
			});
		});
	}

	function fillAll() {
		[].forEach.call(d.querySelectorAll("form.wpcf7-form"), fillForm);
	}

	// Capture phase: runs before CF7 serialises the form in its own submit handler.
	d.addEventListener("submit", function (e) {
		var form = e.target;
		if (form && form.classList && form.classList.contains("wpcf7-form")) fillForm(form);
	}, true);

	if (d.readyState === "loading") {
		d.addEventListener("DOMContentLoaded", fillAll);
	} else {
		fillAll();
	}
	// HandL fills the same inputs on load; re-apply afterwards so our values win.
	w.addEventListener("load", function () {
		setTimeout(fillAll, 0);
	});

	w.BotphonicUTM = {
		params: function () {
			return utm ? JSON.parse(JSON.stringify(utm)) : {};
		},
		decorate: decorate
	};
})(window, document);
