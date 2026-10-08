(function (w, d) {
	"use strict";
	if (location.pathname.startsWith("/embed")) return;

	function init() {
		/* ========================= CHECK LOGIN FIRST ========================== */
		const isUserLoggedIn = w.myData && typeof w.myData.isUserLoggedIn !== "undefined" ? w.myData.isUserLoggedIn : false;

		// 		if (!isUserLoggedIn) {
		// 			function loadBotphonic() {
		// 				if (d.getElementById("botphonic-script")) return;
		// 				if (!d.body) return;
		// 				const script = d.createElement("script");
		// 				script.id = "botphonic-script";
		// 				script.src = "https://app.botphonic.ai/scripts/voiceChat.js";
		// 				script.async = true;
		// 				script.defer = true;
		// 				script.dataset.botId = "68fb715fc65613c6242f5fab";
		// 				d.body.appendChild(script);
		// 			}

		// 			if ("requestIdleCallback" in w) {
		// 				w.requestIdleCallback(loadBotphonic, { timeout: 3000 });
		// 			} else {
		// 				setTimeout(loadBotphonic, 2000);
		// 			}
		// 		}
		/* ========================= PHONE VALIDATION ========================== */

		const phone = d.getElementById("your-number");
		if (phone) {
			phone.addEventListener("input", function (e) {
				e.target.value = e.target.value.replace(/[^\d()+\s]/g, "").slice(0, 15);
			});
		}

		d.addEventListener("wpcf7submit", function (e) {
			const wpcf7 = e.target.closest(".wpcf7");
			if (!wpcf7) return;
			const id = wpcf7.dataset.wpcf7Id;
			if (!["11923", "3104"].includes(id)) return;
			if (e.detail.status !== "mail_sent") return;

			const getValue = (name) =>
			e.target.querySelector(`[name="${name}"]`)?.value || "";

			const redirectUrl =
				  "https://app.botphonic.ai/register/?" +
				  "red=" + encodeURIComponent(getValue("red")) +
				  "&ref=" + encodeURIComponent(getValue("ref")) +
				  "&email=" + encodeURIComponent(getValue("your-email"));

			w.location.href = w.BotphonicUTM ? w.BotphonicUTM.decorate(redirectUrl) : redirectUrl;
			});

			/* ========================= DESKU CHAT (only if NOT logged in) ========================== */

			// if (!isUserLoggedIn) {

			// 		function loadChat() {
			// 			if (d.getElementById("desku-chat-js")) return;
			// 			if (!d.body) return;
			// 			w.lc_id = "431699510458";
			// 			w.lc_dc = "botphonic";
			// 			if (!d.querySelector("app-chat-box")) {
			// 		    	d.body.appendChild(d.createElement("app-chat-box"));
			// 			}

			// 			const s = d.createElement("script");
			// 			s.id = "desku-chat-js";
			// 			s.src = "https://widget.desku.io/chat-widget.js";
			// 			s.defer = true;

			// 			d.body.appendChild(s);
			// 		}

			// 		if ("requestIdleCallback" in w) {
			// 			w.requestIdleCallback(loadChat, { timeout: 4000 });
			// 		} else {
			// 			setTimeout(loadChat, 2500);
			// 		}
			// }

			}

			if (d.readyState === "loading") {
				d.addEventListener("DOMContentLoaded", init);
			} else {
				init();
			}

			})(window, document);