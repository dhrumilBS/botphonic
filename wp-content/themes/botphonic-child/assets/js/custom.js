(function (w, d) {
	"use strict";
	if (location.pathname.startsWith("/embed")) return;

	function init() {
		/* ========================= CHECK LOGIN FIRST ========================== */
		const isUserLoggedIn = w.myData && typeof w.myData.isUserLoggedIn !== "undefined" ? w.myData.isUserLoggedIn : false;
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
	}

	if (d.readyState === "loading") {
		d.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}

})(window, document);

(function (w, d) {
	"use strict";

	function initFooterAccordion() {
		var footer = d.querySelector(".bf-footer");
		if (!footer) return;

		var chevron = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6"/></svg>';

		[].forEach.call(footer.querySelectorAll(".bf-footer-links"), function (col, i) {
			var title = col.querySelector(".widget-title");
			var menu = col.querySelector(".menu");
			if (!title || !menu || col.querySelector(".bf-acc-toggle")) return;

			if (!menu.id) menu.id = "bf-acc-panel-" + i;

			var btn = d.createElement("button");
			btn.type = "button";
			btn.className = "bf-acc-toggle";
			btn.setAttribute("aria-expanded", "false");
			btn.setAttribute("aria-controls", menu.id);
			btn.innerHTML = "<span></span>" + chevron;
			btn.firstChild.textContent = title.textContent;
			title.parentNode.insertBefore(btn, title);

			btn.addEventListener("click", function () {
				var open = btn.getAttribute("aria-expanded") !== "true";
				btn.setAttribute("aria-expanded", open ? "true" : "false");
				col.classList.toggle("is-open", open);
			});
		});

		footer.classList.add("bf-acc-ready");
	}

	if (d.readyState === "loading") {
		d.addEventListener("DOMContentLoaded", initFooterAccordion);
	} else {
		initFooterAccordion();
	}
})(window, document);
