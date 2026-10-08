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
 *    the forms / mail templates).
 *
 * Visitors who never arrived with UTMs get no changes at all.
 * Exposes window.BotphonicUTM.decorate(url) for scripted redirects.
 */
(function (w, d) {
	"use strict";

	var CFG = w.botphonicUtmConfig || {};
	var KEYS = ["utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];
	var FORM_KEYS = ["utm_source", "utm_medium", "utm_campaign"];
	var STORE = "bp_utm";
	var DAYS = CFG.days || 30;
	var SITE_HOSTS = (CFG.siteHosts || []).concat([location.host]).map(lc);
	var APP_HOSTS = (CFG.appHosts || ["app.botphonic.ai"]).map(lc);
	var SKIP_PATH = /\/(wp-admin|wp-login\.php|wp-json|xmlrpc\.php|wp-content|wp-includes|feed)(\/|$)|\.(pdf|jpe?g|png|gif|webp|svg|ico|zip|rar|docx?|xlsx?|pptx?|csv|txt|xml|mp3|mp4|webm|mov)$/i;
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

	function save(data) {
		var json = JSON.stringify(data);
		try {
			d.cookie = STORE + "=" + encodeURIComponent(json) + "; path=/; max-age=" + DAYS * 86400 +
				cookieDomain() + "; SameSite=Lax" + (location.protocol === "https:" ? "; Secure" : "");
		} catch (e) {}
		try {
			w.localStorage.setItem(STORE, json);
		} catch (e) {}
	}

	function load() {
		var raw = null;
		var m = d.cookie.match(new RegExp("(?:^|; )" + STORE + "=([^;]*)"));
		try {
			raw = m ? decodeURIComponent(m[1]) : w.localStorage.getItem(STORE);
		} catch (e) {}
		if (!raw) return null;
		try {
			var data = JSON.parse(raw);
			if (!data || (data.t && Date.now() - data.t > DAYS * 86400000)) return null;
			return hasAny(data.p) ? data.p : null;
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
			save({ p: p, t: Date.now() });
			return p;
		}
		return load();
	}

	var utm = IS_BOT ? null : capture();

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
