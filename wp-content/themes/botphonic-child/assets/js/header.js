/* Site header: mega menus at 1200px and up, full-screen menu below that. */
(function (w, d) {
	"use strict";

	var header = d.querySelector("[data-bph]");
	if (!header) return;

	var root = d.documentElement;
	var nav = header.querySelector(".bph__nav");
	var toggle = header.querySelector("[data-bph-toggle]");
	var items = [].slice.call(header.querySelectorAll("[data-bph-item]"));
	var topLinks = [].slice.call(header.querySelectorAll(".bph__menu > li > .bph__top"));
	var desktop = w.matchMedia("(min-width: 1200px)");
	var fineHover = w.matchMedia("(hover: hover) and (pointer: fine)");

	var OPEN_DELAY = 150;
	var CLOSE_DELAY = 200;
	var openItem = null;
	var hoverTimer = 0;
	var hoverOpenedAt = 0;

	function triggerOf(item) {
		return item.querySelector("[data-bph-trigger]");
	}

	function panelLinks(item) {
		return [].slice.call(item.querySelectorAll(".bph__panel a[href]"));
	}

	function isVisible(el) {
		return el.getClientRects().length > 0 && w.getComputedStyle(el).visibility !== "hidden";
	}

	function setOpen(item, open) {
		item.classList.toggle("is-open", open);
		triggerOf(item).setAttribute("aria-expanded", open ? "true" : "false");
	}

	function openPanel(item, byHover) {
		if (openItem && openItem !== item) setOpen(openItem, false);
		setOpen(item, true);
		openItem = item;
		hoverOpenedAt = byHover ? Date.now() : 0;
	}

	function closePanel() {
		if (!openItem) return;
		setOpen(openItem, false);
		openItem = null;
	}

	function canHover() {
		return desktop.matches && fineHover.matches;
	}

	/* Full-screen menu (below 1200px) */
	function isMenuOpen() {
		return header.classList.contains("is-menu-open");
	}

	function setMenu(open) {
		if (open === isMenuOpen()) return;

		if (open) {
			// Pin the bar exactly where the floating header sits (inside its 1px border).
			var rect = header.getBoundingClientRect();
			var top = Math.max(0, rect.top);
			header.style.setProperty("--bph-bar-top", top + 1 + "px");
			header.style.setProperty("--bph-bar-left", rect.left + 1 + "px");
			header.style.setProperty("--bph-bar-width", rect.width - 2 + "px");
			header.style.setProperty("--bph-drawer-top", top + rect.height + "px");
		} else {
			closePanel();
		}

		header.classList.toggle("is-menu-open", open);
		root.classList.toggle("bph-locked", open);
		toggle.setAttribute("aria-expanded", open ? "true" : "false");
		toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");

		if (open) {
			nav.scrollTop = 0;
			topLinks[0].focus();
		}
	}

	function trapFocus(e) {
		var focusables = [].slice.call(header.querySelectorAll("a[href], button")).filter(isVisible);
		if (!focusables.length) return;

		var first = focusables[0];
		var last = focusables[focusables.length - 1];

		if (e.shiftKey && d.activeElement === first) {
			e.preventDefault();
			last.focus();
		} else if (!e.shiftKey && d.activeElement === last) {
			e.preventDefault();
			first.focus();
		}
	}

	/* Mega menu items: click, hover intent, focus leaving */
	items.forEach(function (item) {
		triggerOf(item).addEventListener("click", function () {
			if (item !== openItem) {
				openPanel(item, false);
			} else if (Date.now() - hoverOpenedAt < 600) {
				// A click straight after hover opened it should not close it again.
				hoverOpenedAt = 0;
			} else {
				closePanel();
			}
		});

		item.addEventListener("mouseenter", function () {
			if (!canHover()) return;
			clearTimeout(hoverTimer);
			if (item === openItem) return;
			hoverTimer = setTimeout(function () {
				openPanel(item, true);
			}, OPEN_DELAY);
		});

		item.addEventListener("mouseleave", function () {
			if (!canHover()) return;
			clearTimeout(hoverTimer);
			if (item !== openItem) return;
			hoverTimer = setTimeout(closePanel, CLOSE_DELAY);
		});

		item.addEventListener("focusout", function (e) {
			if (desktop.matches && item === openItem && !item.contains(e.relatedTarget)) {
				closePanel();
			}
		});
	});

	toggle.addEventListener("click", function () {
		setMenu(!isMenuOpen());
	});

	// Following a link (including same-page anchors) closes whatever is open.
	nav.addEventListener("click", function (e) {
		if (!e.target.closest("a[href]")) return;
		closePanel();
		setMenu(false);
	});

	d.addEventListener("click", function (e) {
		if (openItem && desktop.matches && !openItem.contains(e.target)) closePanel();
	});

	/* Keyboard */
	header.addEventListener("keydown", function (e) {
		var key = e.key;
		var active = d.activeElement;

		if (key === "Escape") {
			if (!desktop.matches && isMenuOpen()) {
				setMenu(false);
				toggle.focus();
			} else if (openItem) {
				var trigger = triggerOf(openItem);
				closePanel();
				trigger.focus();
			}
			return;
		}

		if (!desktop.matches) {
			if (key === "Tab" && isMenuOpen()) trapFocus(e);
			return;
		}

		var topIndex = topLinks.indexOf(active);
		if (topIndex > -1) {
			if (key === "ArrowRight" || key === "ArrowLeft") {
				e.preventDefault();
				closePanel();
				topLinks[(topIndex + (key === "ArrowRight" ? 1 : -1) + topLinks.length) % topLinks.length].focus();
			} else if (key === "ArrowDown" && active.hasAttribute("data-bph-trigger")) {
				e.preventDefault();
				var item = active.closest("[data-bph-item]");
				openPanel(item, false);
				panelLinks(item)[0].focus();
			}
			return;
		}

		if (!openItem || !openItem.contains(active)) return;

		var links = panelLinks(openItem);
		var i = links.indexOf(active);
		if (i < 0) return;

		if (key === "ArrowDown" || key === "ArrowUp") {
			e.preventDefault();
			if (key === "ArrowUp" && i === 0) {
				triggerOf(openItem).focus();
			} else {
				links[Math.min(links.length - 1, Math.max(0, i + (key === "ArrowDown" ? 1 : -1)))].focus();
			}
		} else if (key === "Home" || key === "End") {
			e.preventDefault();
			links[key === "Home" ? 0 : links.length - 1].focus();
		}
	});

	/* Reset when crossing the 1200px breakpoint */
	function onBreakpoint() {
		clearTimeout(hoverTimer);
		closePanel();
		setMenu(false);
	}
	if (desktop.addEventListener) {
		desktop.addEventListener("change", onBreakpoint);
	} else {
		desktop.addListener(onBreakpoint);
	}

	/* Shadow once the page scrolls under the header */
	var stuck = false;
	var ticking = false;
	function measure() {
		ticking = false;
		var next = (w.pageYOffset || root.scrollTop || 0) > 0;
		if (next !== stuck) {
			stuck = next;
			header.classList.toggle("is-stuck", stuck);
		}
	}
	w.addEventListener("scroll", function () {
		if (ticking) return;
		ticking = true;
		w.requestAnimationFrame(measure);
	}, { passive: true });
	measure();

	header.classList.add("is-ready");
})(window, document);
