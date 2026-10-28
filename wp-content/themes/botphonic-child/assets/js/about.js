(function () {
	'use strict';

	var root = document.getElementById('bp-about');
	if (!root) {
		return;
	}

	root.classList.remove('no-js');
	root.classList.add('is-ready');

	// Industry tabs: click, arrow keys, Home and End.
	var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-bau-tabs] [role="tab"]'));
	var panels = Array.prototype.slice.call(root.querySelectorAll('[data-bau-panel]'));

	function selectTab(tab, focus) {
		tabs.forEach(function (item) {
			var active = item === tab;
			item.setAttribute('aria-selected', active ? 'true' : 'false');
			item.tabIndex = active ? 0 : -1;
		});

		panels.forEach(function (panel) {
			var active = panel.id === tab.getAttribute('aria-controls');
			panel.classList.toggle('is-active', active);
			panel.hidden = !active;
		});

		if (focus) {
			tab.focus();
		}
	}

	tabs.forEach(function (tab, index) {
		tab.addEventListener('click', function () {
			selectTab(tab, false);
		});

		tab.addEventListener('keydown', function (e) {
			var next = null;
			if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
				next = tabs[(index + 1) % tabs.length];
			} else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
				next = tabs[(index - 1 + tabs.length) % tabs.length];
			} else if (e.key === 'Home') {
				next = tabs[0];
			} else if (e.key === 'End') {
				next = tabs[tabs.length - 1];
			}

			if (next) {
				e.preventDefault();
				selectTab(next, true);
			}
		});
	});

	var initial = root.querySelector('[data-bau-tabs] [aria-selected="true"]') || tabs[0];
	if (initial) {
		selectTab(initial, false);
	}

	// Reveal blocks as they scroll into view, staggered within each group.
	var reveals = Array.prototype.slice.call(root.querySelectorAll('[data-bau-reveal]'));
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (!('IntersectionObserver' in window) || reduceMotion) {
		reveals.forEach(function (el) {
			el.classList.add('is-visible');
		});
		return;
	}

	reveals.forEach(function (el) {
		var siblings = Array.prototype.filter.call(el.parentNode.children, function (child) {
			return child.hasAttribute('data-bau-reveal');
		});
		el.style.setProperty('--bau-delay', Math.min(siblings.indexOf(el), 5) * 70 + 'ms');
	});

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				entry.target.classList.add('is-visible');
				observer.unobserve(entry.target);
			}
		});
	}, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });

	reveals.forEach(function (el) {
		observer.observe(el);
	});
})();
