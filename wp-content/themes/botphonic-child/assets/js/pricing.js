(function () {
	"use strict";

	var root = document.querySelector("[data-bpp-root]");
	if (!root) {
		return;
	}
	if (root.getAttribute("data-bpp-ready") === "1") {
		return; // guard against a duplicated enqueue
	}
	root.setAttribute("data-bpp-ready", "1");
	var CFG = window.BP_PRICING || {};
	var API = CFG.apiUrl || "https://api.botphonic.ai/api/plans";
	var SIGNUP_URL = CFG.signupUrl || "https://app.botphonic.ai/register/";
	var CONTACT_URL = CFG.contactUrl || "https://botphonic.ai/contact/";
	var USD_INR = Number(CFG.usdInrRate) > 0 ? Number(CFG.usdInrRate) : 95.5;
	var NOTES = CFG.notes || {};
	var DESC = CFG.descriptions || {};
	var INHERIT = CFG.inherit || {};
	var ADDON = CFG.addon || { USD: "$1.48", INR: "\u20B9300" };
	var T = CFG.i18n || {};
	var VIEWS = { monthly: 1, yearly: 1, payg: 1 };
	var CURRENCIES = { USD: "$", INR: "\u20B9" };
	var MATRIX_SKIP = Array.isArray(CFG.matrixSkip) ? CFG.matrixSkip : ["New Phone Number"];
	var MATRIX_LABEL = CFG.matrixLabels || {};
	var MATRIX_ORDER = Array.isArray(CFG.matrixOrder) ? CFG.matrixOrder : [];
	var MATRIX_ABSENT = CFG.matrixAbsent || {};
	var MATRIX_RATE_KEY = /credit|rate/i;

	var state = {
		view: VIEWS[CFG.defaultView] ? CFG.defaultView : "yearly",
		currency: CURRENCIES[CFG.defaultCurrency] ? CFG.defaultCurrency : "USD"
	};

	function find(selector) {
		return root.querySelector(selector);
	}

	function findAll(selector) {
		return Array.prototype.slice.call(root.querySelectorAll(selector));
	}

	function t(key, fallback) {
		return typeof T[key] === "string" && T[key] !== "" ? T[key] : fallback;
	}

	function esc(value) {
		return String(value == null ? "" : value).replace(/[&<>"']/g, function (ch) {
			return {
				"&": "&amp;",
				"<": "&lt;",
				">": "&gt;",
				'"': "&quot;",
				"'": "&#039;"
			}[ch];
		});
	}

	function money(currency, amount, dp) {
		var value = Number(amount);
		if (!isFinite(value)) {
			return "";
		}
		var digits = dp === undefined ? (value % 1 ? 2 : 0) : dp;
		try {
			return new Intl.NumberFormat(currency === "INR" ? "en-IN" : "en-US", {
				style: "currency",
				currency: currency,
				minimumFractionDigits: digits,
				maximumFractionDigits: digits
			}).format(value);
		} catch (e) {
			return (CURRENCIES[currency] || "") + value.toFixed(digits);
		}
	}

	function pick(features, section, key) {
		return features && features[section] && features[section][key] !== undefined
			? features[section][key]
			: undefined;
	}

	var TICK =
		'<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" ' +
		'aria-hidden="true" focusable="false"><path d="M4 10.5l4 4 8-9"/></svg>';

	function bullet(html) {
		return "<li>" + TICK + "<span>" + html + "</span></li>";
	}

	function supportLine(features) {
		var support = (features && features.Support) || {};
		var channels = [];

		if (support["Email Support"]) { channels.push(t("chEmail", "Email")); }
		if (support["Desku Support"]) { channels.push(t("chChat", "chat")); }
		if (support["Google Meet Support"]) { channels.push(t("chMeet", "Google Meet")); }

		var line = channels.length
			? channels.join(", ").replace(/,([^,]*)$/, " " + t("and", "and") + "$1") + " " + t("support", "support")
			: "";

		if (support["Solution Architect"]) {
			line = line
				? line + t("plusArchitect", ", plus a solution architect")
				: t("architect", "Dedicated solution architect");
		}

		return line;
	}

	function featureLines(features) {
		var cost = (features && features["Cost Structure"]) || {};
		var core = (features && features["Core Capabilities"]) || {};
		var actions = (features && features.Actions) || {};
		var white = (features && features["White Label"]) || {};
		var out = [];

		var minutes = cost["Minutes Included"];
		if (typeof minutes === "number") {
			out.push("<b>" + minutes + " " + t("talkMinutes", "talk minutes") + "</b> " + t("perMonth", "per month"));
		} else if (minutes) {
			out.push("<b>" + t("custom", "Custom") + "</b> " + t("minuteBundle", "minute bundle"));
		}

		var concurrent = cost["Concurrent Calls"];
		if (concurrent !== undefined && concurrent !== null) {
			out.push(
				"<b>" +
				(typeof concurrent === "number" ? concurrent : t("custom", "Custom")) +
				" " + t("concurrentCalls", "concurrent calls") + "</b>"
			);
		}

		if (core["Unlimited Agents"]) { out.push(t("unlimitedAgents", "Unlimited AI agents, all 50+ languages")); }
		if (core["Batch Campaigns"]) { out.push(t("batchCampaigns", "Outbound batch campaigns")); }

		var workflows = actions["Custom Workflows Included"];
		if (typeof workflows === "number" && workflows > 0) {
			out.push(workflows + " " + t("customWorkflows", "custom workflows"));
		} else if (workflows === "Custom") {
			out.push(t("unlimitedWorkflows", "Unlimited custom workflows"));
		}

		if (core["Invite Team Members"]) { out.push(t("inviteTeam", "Team member invitations")); }

		var subaccounts = white.Subaccounts;
		if (typeof subaccounts === "number" && subaccounts > 0) {
			out.push(subaccounts + " " + t("subaccounts", "white-label subaccounts"));
		} else if (subaccounts === "Custom") {
			out.push(t("customSubaccounts", "Custom white-label subaccounts"));
		}

		if (core.Rebilling) { out.push(t("rebilling", "Rebilling")); }
		if (cost["Boosted Queuing for Calls"]) { out.push(t("boostedQueue", "Boosted call queuing")); }

		var support = supportLine(features);
		if (support) { out.push(support); }

		return out;
	}

	function subscriptionCard(plan, currency, cycle, monthlyPrice) {
		var isCustom = !plan.hasPrice || plan.price == null;
		var strike =
			cycle === "Yearly" && monthlyPrice && monthlyPrice > plan.price
				? '<span class="bpp-was">' + esc(money(currency, monthlyPrice)) + "</span>"
				: "";

		var minutes = pick(plan.features, "Cost Structure", "Minutes Included");
		var rate = isCustom
			? esc(t("volumeScope", "Priced on volume and compliance scope"))
			: "<b>" +
			esc(typeof minutes === "number" ? minutes + " " + t("minutes", "minutes") : t("custom", "Custom")) +
			"</b> " +
			esc(t("includedBilled", "included \u00b7 billed") + " " + cycle.toLowerCase());

		var ctaClass = plan.isRecommended ? "bpp-btn--coral" : isCustom ? "bpp-btn--ghost" : "bpp-btn--ink";

		return (
			'<article class="bpp-plan' + (plan.isRecommended ? " bpp-plan--best" : "") + '">' +
			(plan.isRecommended ? '<span class="bpp-flag">' + esc(t("mostPopular", "Most popular")) + "</span>" : "") +
			"<h3>" + esc(plan.name) + "</h3>" +
			'<p class="bpp-for">' + esc(plan.description || DESC[plan.name] || "") + "</p>" +
			'<div class="bpp-amount' + (isCustom ? " bpp-amount--custom" : "") + '">' +
			"<b>" + esc(isCustom ? t("custom", "Custom") : money(currency, plan.price)) + "</b>" +
			"<span>" + esc(isCustom ? t("volumePricing", "volume pricing") : t("perMonthSlash", "/month")) + "</span>" +
			strike +
			"</div>" +
			'<p class="bpp-rate">' + rate + "</p>" +
			'<a class="bpp-btn ' + ctaClass + '" href="' + esc(isCustom ? CONTACT_URL : SIGNUP_URL) + '"' +
			(isCustom ? "" : ' target="_blank" rel="noopener"') + ">" +
			esc(isCustom ? t("talkToSales", "Talk to sales") : t("startTrial", "Start 14-day free trial")) +
			"</a>" +
			"<ul>" + featureLines(plan.features).map(bullet).join("") + "</ul>" +
			'<p class="bpp-inherit">' + (INHERIT[plan.name] ? esc(INHERIT[plan.name]) : "&nbsp;") + "</p>" +
			'<p class="bpp-foot">' +
			esc(isCustom
				? t("footEnterprise", "White-label and reseller terms available for agencies.")
				: t("footTrial", "$1 verification charge, refunded within 48 hours.")) +
			"</p>" +
			"</article>"
		);
	}

	function walletRate(usdRate, currency) {
		if (usdRate == null) {
			return null;
		}
		return currency === "INR" ? usdRate * USD_INR : usdRate;
	}

	function walletCard(plan, currency) {
		var rate = walletRate(pick(plan.features, "Cost Structure", "Per Minute Credit"), currency);
		var perMinute = rate == null ? "" : money(currency, rate, 2) + " " + t("perTalkMinute", "per talk minute");
		var minutes = rate ? Math.round(plan.price / rate) : null;

		return (
			'<article class="bpp-plan">' +
			"<h3>" + esc(plan.name) + "</h3>" +
			'<p class="bpp-for">' + esc(t("paygFor", "Prepaid credit, drawn down per talk minute.")) + "</p>" +
			'<div class="bpp-amount"><b>' + esc(money(currency, plan.price)) + "</b>" +
			"<span>" + esc(t("creditWallet", "credit wallet")) + "</span></div>" +
			'<p class="bpp-rate">' +
			(perMinute ? '<span class="bpp-permin">' + esc(perMinute) + "</span>" : "") +
			"</p>" +
			'<a class="bpp-btn bpp-btn--ink" href="' + esc(SIGNUP_URL) + '" target="_blank" rel="noopener">' +
			esc(t("topUp", "Top up and start")) +
			"</a>" +
			"<ul>" + featureLines(plan.features).map(bullet).join("") + "</ul>" +
			'<p class="bpp-inherit">' +
			(minutes
				? esc(t("roughlyMinutes", "Roughly %d minutes of conversation.").replace("%d", minutes))
				: "&nbsp;") +
			"</p>" +
			'<p class="bpp-foot">' +
			esc(t("footWallet", "Credit does not expire. No monthly subscription charge.")) +
			(currency === "INR" ? " " + esc(t("footConverted", "Per-minute rate converted from USD at the prevailing rate.")) : "") +
			"</p>" +
			"</article>"
		);
	}

	var cache = {};
	function load(url) {
		if (cache[url]) {
			return cache[url];
		}

		cache[url] = fetch(url, { credentials: "omit", headers: { Accept: "application/json" } })
			.then(function (response) {
				if (!response.ok) {
					throw new Error("Botphonic pricing API returned " + response.status);
				}
				return response.json();
			})
			.then(function (json) {
				var data = json && Array.isArray(json.data) ? json.data : [];
				if (!data.length) {
					throw new Error("Botphonic pricing API returned no plans");
				}
				return data;
			})
			.catch(function (error) {
				delete cache[url]; // let a later toggle retry
				throw error;
			});

		return cache[url];
	}

	function subUrl(cycle, currency) {
		return API + "?billingCycle=" + encodeURIComponent(cycle) + "&currency=" + encodeURIComponent(currency);
	}

	function walletUrl(currency) {
		return API + "?currency=" + encodeURIComponent(currency) + "&isWalletPlan=true";
	}

	function onRenderFailure(grid, error) {
		if (grid.getAttribute("data-bpp-live") === "1") {
			grid.removeAttribute("data-bpp-live");
			grid.innerHTML =
				'<p class="bpp-plans-msg">' +
				esc(t("unavailable", "Live pricing for this selection is temporarily unavailable.")) +
				' <a class="bpp-link" href="' + esc(CONTACT_URL) + '">' +
				esc(t("talkToSales", "Talk to sales")) +
				"</a>.</p>";
		}
		throw error;
	}

	function renderSubscriptions() {
		var grid = find('[data-bpp-grid="sub"]');
		if (!grid) {
			return Promise.resolve();
		}

		var currency = state.currency;
		var cycle = state.view === "monthly" ? "Monthly" : "Yearly";

		return Promise.all([
			load(subUrl(cycle, currency)),
			cycle === "Yearly" ? load(subUrl("Monthly", currency)) : Promise.resolve([])
		]).then(function (results) {
			var plans = results[0];
			var monthly = {};

			results[1].forEach(function (plan) {
				monthly[plan.name] = plan.price;
			});

			grid.innerHTML = plans
				.map(function (plan) {
					return subscriptionCard(plan, currency, cycle, monthly[plan.name]);
				})
				.join("");
			grid.setAttribute("data-bpp-live", "1");
		}, function (error) {
			return onRenderFailure(grid, error);
		});
	}

	function renderWallets() {
		var grid = find('[data-bpp-grid="payg"]');
		if (!grid) {
			return Promise.resolve();
		}

		var currency = state.currency;

		return load(walletUrl(currency)).then(function (plans) {
			grid.innerHTML = plans
				.map(function (plan) {
					return walletCard(plan, currency);
				})
				.join("");
			grid.setAttribute("data-bpp-live", "1");
		}, function (error) {
			return onRenderFailure(grid, error);
		});
	}

	var TICK_TD =
		'<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.4" ' +
		'aria-hidden="true" focusable="false"><path d="M4 10.5l4 4 8-9"/></svg>';

	function matrixCycle() {
		return state.view === "monthly" ? "Monthly" : "Yearly";
	}

	function isWalletPlan(plan) {
		return pick(plan.features, "Cost Structure", "Per Minute Credit") != null;
	}

	function notIncluded() {
		return '<span class="bpp-none">' + esc(t("notIncluded", "Not included")) + "</span>";
	}

	function matrixCell(value, key, currency) {
		if (value === true) {
			return TICK_TD + '<span class="bpp-sr">' + esc(t("included", "Included")) + "</span>";
		}
		if (value === false || value === null) {
			return notIncluded();
		}
		if (typeof value === "object") {
			return value.price ? esc(value.price) : notIncluded();
		}
		if (typeof value === "number") {
			if (MATRIX_RATE_KEY.test(key)) {
				return esc(money(currency, walletRate(value, currency), 2));
			}
			return value === 0 ? notIncluded() : esc(String(value));
		}
		return esc(String(value));
	}

	function matrixKeys(columns) {
		var seen = {};
		var keys = [];

		columns.forEach(function (plan) {
			var features = plan.features || {};

			Object.keys(features).forEach(function (group) {
				Object.keys(features[group] || {}).forEach(function (key) {
					if (MATRIX_SKIP.indexOf(key) !== -1) {
						return;
					}
					var id = group + "|" + key;
					if (seen[id]) {
						return;
					}
					seen[id] = 1;
					keys.push({ group: group, key: key, seq: keys.length });
				});
			});
		});

		return keys.sort(function (a, b) {
			var ra = MATRIX_ORDER.indexOf(a.key);
			var rb = MATRIX_ORDER.indexOf(b.key);
			if (ra < 0) { ra = MATRIX_ORDER.length + a.seq; }
			if (rb < 0) { rb = MATRIX_ORDER.length + b.seq; }
			return ra - rb;
		});
	}

	function matrixRow(label, columns, cellFor) {
		return (
			'<tr><th scope="row">' + esc(label) + "</th>" +
			columns
				.map(function (plan) {
					return (
						"<td" + (plan.isRecommended ? ' class="bpp-best"' : "") + ">" +
						cellFor(plan) +
						"</td>"
					);
				})
				.join("") +
			"</tr>"
		);
	}

	function matrixMarkup(columns, currency, cycle) {
		var head =
			'<tr><th scope="col">' + esc(t("matrixFeature", "Feature")) + "</th>" +
			columns
				.map(function (plan) {
					return (
						'<th scope="col"' + (plan.isRecommended ? ' class="bpp-best"' : "") + ">" +
						esc(plan.name) +
						"</th>"
					);
				})
				.join("") +
			"</tr>";

		var rows = [];

		/* Price and billing lead: the two things every visitor scans for. */
		rows.push(
			matrixRow(t("matrixPriceRow", "Price"), columns, function (plan) {
				return esc(
					money(currency, plan.price) +
					(isWalletPlan(plan) ? t("matrixWalletSuffix", " wallet") : t("perMonthSlash", "/month"))
				);
			})
		);

		rows.push(
			matrixRow(t("matrixBillingRow", "Billing"), columns, function (plan) {
				return esc(
					isWalletPlan(plan)
						? t("matrixPrepaid", "Prepaid, no renewal")
						: t("matrixBilled", "Billed %s").replace(
							"%s",
							cycle === "Monthly" ? t("cycleMonthly", "monthly") : t("cycleYearly", "yearly")
						)
				);
			})
		);

		matrixKeys(columns).forEach(function (entry) {
			var label = MATRIX_LABEL[entry.key] || entry.key;

			rows.push(
				matrixRow(label, columns, function (plan) {
					var group = plan.features && plan.features[entry.group];
					var value = group ? group[entry.key] : undefined;

					/* A key absent from a plan is not a disabled feature: a wallet has
					   no monthly minutes, a subscription has no per-minute wallet rate. */
					if (value === undefined) {
						return '<span class="bpp-none">' + esc(MATRIX_ABSENT[entry.key] || "—") + "</span>";
					}
					return matrixCell(value, entry.key, currency);
				})
			);
		});

		return { head: head, body: rows.join("") };
	}

	function renderMatrix() {
		var box = find("[data-bpp-matrix]");
		if (!box) {
			return Promise.resolve();
		}

		var stateEl = box.querySelector("[data-bpp-matrix-state]");
		var scroll = box.querySelector("[data-bpp-matrix-scroll]");
		var head = box.querySelector("[data-bpp-matrix-head]");
		var body = box.querySelector("[data-bpp-matrix-body]");
		var caption = box.querySelector("[data-bpp-matrix-caption]");
		if (!stateEl || !scroll || !head || !body) {
			return Promise.resolve();
		}

		var currency = state.currency;
		var cycle = matrixCycle();
		var hasTable = body.children.length > 0;

		function message(text) {
			stateEl.textContent = text;
			stateEl.hidden = false;
			scroll.hidden = true;
			scroll.classList.remove("is-bpp-loading");
			head.innerHTML = "";
			body.innerHTML = "";
		}

		if (hasTable) {
			/* Keep the built table on screen and just dim it, so a billing or
			   currency switch does not collapse the section under the viewport. */
			scroll.classList.add("is-bpp-loading");
			scroll.setAttribute("aria-busy", "true");
		} else {
			stateEl.textContent = t("matrixLoading", "Loading the feature comparison…");
			stateEl.hidden = false;
		}

		return Promise.all([
			load(subUrl(cycle, currency)),
			load(walletUrl(currency))
		]).then(function (results) {
			var columns = results[0]
				.filter(function (plan) {
					return plan.hasPrice && plan.price;
				})
				.concat(results[1]);

			if (!columns.length) {
				message(t("matrixEmpty", "The feature comparison is not published for this selection yet."));
				return;
			}

			var markup = matrixMarkup(columns, currency, cycle);
			head.innerHTML = markup.head;
			body.innerHTML = markup.body;

			if (caption) {
				caption.textContent = t("matrixCaption", "Feature comparison of Botphonic AI voice agent plans, %s.")
					.replace("%s", currency === "INR"
						? t("inRupees", "in Indian rupees")
						: t("inDollars", "in US dollars"));
			}

			stateEl.hidden = true;
			scroll.hidden = false;
			scroll.classList.remove("is-bpp-loading");
			scroll.setAttribute("aria-busy", "false");
		}, function (error) {
			message(t("matrixError", "The feature comparison could not be loaded."));
			throw error;
		});
	}

	function renderAddons() {
		findAll("[data-bpp-addon]").forEach(function (node) {
			node.textContent = ADDON[state.currency] || ADDON.USD || "";
		});
	}

	function syncStaticCurrency() {
		findAll("[data-bpp-cur]").forEach(function (node) {
			node.hidden = node.getAttribute("data-bpp-cur") !== state.currency;
		});
	}

	function percentOff(from, to) {
		return from > 0 && to < from ? Math.floor(((from - to) / from) * 100) : 0;
	}

	function setBadge(key, value) {
		findAll('[data-bpp-save="' + key + '"]').forEach(function (node) {
			if (value > 0) {
				node.textContent = t("saveUpTo", "save up to %d%").replace("%d", value);
				node.hidden = false;
			} else {
				node.textContent = "";
				node.hidden = true;
			}
		});
	}

	function renderBadges() {
		var currency = state.currency;

		return Promise.all([
			load(subUrl("Monthly", currency)),
			load(subUrl("Yearly", currency)),
			load(walletUrl(currency))
		]).then(function (results) {
			var monthly = results[0];
			var yearly = results[1];
			var wallets = results[2];
			var byName = {};
			var bestYearly = 0;

			monthly.forEach(function (plan) {
				if (plan.hasPrice && plan.price) {
					byName[plan.name] = plan;
				}
			});

			yearly.forEach(function (plan) {
				var reference = byName[plan.name];
				if (plan.hasPrice && plan.price && reference) {
					bestYearly = Math.max(bestYearly, percentOff(reference.price, plan.price));
				}
			});
			setBadge("yearly", bestYearly);

			var reference = byName.Starter || monthly.filter(function (plan) {
				return plan.hasPrice && plan.price;
			})[0];
			var refMinutes = reference && pick(reference.features, "Cost Structure", "Minutes Included");
			var refRate = reference && typeof refMinutes === "number" && refMinutes > 0
				? reference.price / refMinutes
				: 0;

			var bestWallet = 0;
			wallets.forEach(function (plan) {
				var rate = walletRate(pick(plan.features, "Cost Structure", "Per Minute Credit"), currency);
				if (rate == null) {
					return;
				}
				bestWallet = Math.max(bestWallet, percentOff(refRate, rate));
			});
			setBadge("payg", bestWallet);
		});
	}

	/* ---------------------------------------------------------------------
	 * State application
	 * ------------------------------------------------------------------ */

	function warn(error) {
		if (window.console && console.warn) {
			console.warn("[Botphonic pricing] live prices unavailable, showing the published fallback.", error);
		}
	}

	function apply() {
		var isPayg = state.view === "payg";

		findAll("[data-bpp-pane]").forEach(function (pane) {
			var isPaygPane = pane.getAttribute("data-bpp-pane") === "payg";
			pane.classList.toggle("bpp-off", isPaygPane !== isPayg);
			pane.hidden = isPaygPane !== isPayg;
		});

		var note = find("[data-bpp-note]");
		if (note && NOTES[state.view]) {
			note.textContent = NOTES[state.view];
		}

		findAll("[data-bpp-view]").forEach(function (button) {
			button.setAttribute("aria-pressed", button.getAttribute("data-bpp-view") === state.view ? "true" : "false");
		});
		findAll("[data-bpp-currency]").forEach(function (button) {
			button.setAttribute(
				"aria-pressed",
				button.getAttribute("data-bpp-currency") === state.currency ? "true" : "false"
			);
		});

		renderAddons();
		syncStaticCurrency();

		var grid = find(isPayg ? '[data-bpp-grid="payg"]' : '[data-bpp-grid="sub"]');
		if (grid) {
			grid.classList.add("is-bpp-loading");
		}

		Promise.all([
			renderSubscriptions().catch(warn),
			renderWallets().catch(warn)
		]).then(function () {
			if (grid) {
				grid.classList.remove("is-bpp-loading");
			}
		});

		renderMatrix().catch(warn);
		renderBadges().catch(function () {
			/* Leave whatever the server rendered. */
		});
	}

	function bind() {
		findAll("[data-bpp-view]").forEach(function (button) {
			button.addEventListener("click", function () {
				var next = button.getAttribute("data-bpp-view");
				if (!VIEWS[next] || next === state.view) {
					return;
				}
				state.view = next;
				apply();
			});
		});

		findAll("[data-bpp-currency]").forEach(function (button) {
			button.addEventListener("click", function () {
				var next = button.getAttribute("data-bpp-currency");
				if (!CURRENCIES[next] || next === state.currency) {
					return;
				}
				state.currency = next;
				apply();
			});
		});
	}

	function init() {
		bind();
		apply();
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init, { once: true });
	} else {
		init();
	}
}());
