/**
 * Integration pages (page-{hubspot,salesforce,zapier,zoho,whatsapp}-integration.php).
 * Use-case tabs, scroll reveal and accessible names for the sign-up form.
 * Loaded deferred, only on these pages, by inc/function-integration-pages.php.
 */
(function () {
	'use strict';

	var root = document.getElementById('bp-integration');
	if (!root) {
		return;
	}

	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var reveals = Array.prototype.slice.call(root.querySelectorAll('[data-bpi-reveal]'));
	var canObserve = 'IntersectionObserver' in window && !reduceMotion;

	// Never hide what is already on screen: mark it visible before the
	// reveal styles switch on, so nothing above the fold flickers.
	reveals.forEach(function (el) {
		if (!canObserve || el.getBoundingClientRect().top < window.innerHeight) {
			el.classList.add('is-visible');
		}
	});

	root.classList.remove('no-js');
	root.classList.add('is-ready');

	// Use-case tabs: click, arrow keys, Home and End.
	var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-bpi-tabs] [role="tab"]'));
	var panels = Array.prototype.slice.call(root.querySelectorAll('[data-bpi-panel]'));

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

	if (tabs.length) {
		selectTab(root.querySelector('[data-bpi-tabs] [aria-selected="true"]') || tabs[0], false);
	}

	// The sign-up form (Contact Form 7, shared site-wide) uses a placeholder
	// instead of a label. Give the field an accessible name on these pages.
	Array.prototype.forEach.call(root.querySelectorAll('.bpi-signup input[type="email"]'), function (field) {
		if (!field.getAttribute('aria-label') && !field.getAttribute('aria-labelledby')) {
			field.setAttribute('aria-label', 'Work email address');
		}
		if (!field.getAttribute('autocomplete')) {
			field.setAttribute('autocomplete', 'email');
		}
	});

	// Reveal blocks as they scroll into view, staggered within each group.
	if (!canObserve) {
		return;
	}

	reveals.forEach(function (el) {
		var siblings = Array.prototype.filter.call(el.parentNode.children, function (child) {
			return child.hasAttribute('data-bpi-reveal');
		});
		el.style.setProperty('--bpi-delay', Math.min(siblings.indexOf(el), 5) * 70 + 'ms');
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
		if (!el.classList.contains('is-visible')) {
			observer.observe(el);
		}
	});
})();
