/**
 * Integration pages: the hub (page-integrations.php) and
 * page-{hubspot,salesforce,zapier,zoho,whatsapp}-integration.php.
 * Use-case tabs, the hub's directory filter, scroll reveal and accessible
 * names for the sign-up form.
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

	// Hub directory: category filter, search and #category links.
	var directory = root.querySelector('[data-bpi-directory]');
	if (directory) {
		initDirectory(directory);
	}

	function initDirectory(dir) {
		var cards = Array.prototype.slice.call(dir.querySelectorAll('[data-bpi-item]'));
		var chips = Array.prototype.slice.call(dir.querySelectorAll('[data-bpi-filter]'));
		var search = dir.querySelector('[data-bpi-search]');
		var status = dir.querySelector('[data-bpi-status]');
		var empty = dir.querySelector('[data-bpi-empty]');
		var reset = dir.querySelector('[data-bpi-reset]');

		if (!cards.length || !chips.length) {
			return;
		}

		var total = cards.length;
		var active = 'all';
		var timer = null;
		var labels = {};

		chips.forEach(function (chip) {
			labels[chip.getAttribute('data-bpi-filter')] = chip.getAttribute('data-bpi-label') || '';
		});

		// Lowercase, drop accents and punctuation, so "cal.com", "Cal.com" and "calcom " compare alike.
		function normalize(text) {
			var value = String(text || '').toLowerCase();
			if (value.normalize) {
				value = value.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
			}
			return value.replace(/[^a-z0-9.+]+/g, ' ').trim();
		}

		var index = cards.map(function (card) {
			return {
				el: card,
				category: card.getAttribute('data-category') || '',
				text: normalize((card.getAttribute('data-search') || '') + ' ' + card.textContent)
			};
		});

		function render() {
			var raw = search ? search.value.trim() : '';
			var terms = normalize(raw).split(' ').filter(Boolean);
			var shown = 0;

			index.forEach(function (entry) {
				var match = (active === 'all' || entry.category === active) && terms.every(function (term) {
					return entry.text.indexOf(term) !== -1;
				});
				entry.el.hidden = !match;
				if (match) {
					shown++;
				}
			});

			if (empty) {
				empty.hidden = shown !== 0;
			}

			if (status) {
				var text = 'Showing all ' + total + ' integrations';
				if (active !== 'all' || terms.length) {
					text = 'Showing ' + shown + ' of ' + total + ' integrations';
					if (active !== 'all' && labels[active]) {
						text += ' in ' + labels[active];
					}
					if (terms.length) {
						text += ' matching \u201C' + raw + '\u201D';
					}
				}
				if (status.textContent !== text) {
					status.textContent = text;
				}
			}
		}

		function setFilter(key, updateUrl) {
			active = Object.prototype.hasOwnProperty.call(labels, key) ? key : 'all';

			chips.forEach(function (chip) {
				chip.setAttribute('aria-pressed', chip.getAttribute('data-bpi-filter') === active ? 'true' : 'false');
			});

			// Keep the filtered view shareable without adding history entries.
			if (updateUrl && window.history && typeof window.history.replaceState === 'function') {
				window.history.replaceState(null, '', window.location.pathname + window.location.search + (active === 'all' ? '' : '#' + active));
			}

			render();
		}

		function categoryFromHash() {
			var hash = '';
			try {
				hash = decodeURIComponent(window.location.hash.replace(/^#/, ''));
			} catch (err) {
				return '';
			}
			return hash !== 'all' && Object.prototype.hasOwnProperty.call(labels, hash) ? hash : '';
		}

		chips.forEach(function (chip) {
			chip.addEventListener('click', function () {
				setFilter(chip.getAttribute('data-bpi-filter'), true);
			});
		});

		if (search) {
			var onSearch = function () {
				clearTimeout(timer);
				timer = setTimeout(render, 150);
			};
			search.addEventListener('input', onSearch);
			search.addEventListener('search', onSearch);
		}

		if (reset) {
			reset.addEventListener('click', function () {
				if (search) {
					search.value = '';
				}
				setFilter('all', true);
				if (search) {
					search.focus();
				}
			});
		}

		window.addEventListener('hashchange', function () {
			var key = categoryFromHash();
			if (key) {
				setFilter(key, false);
				dir.scrollIntoView({ block: 'start' });
			}
		});

		var initial = categoryFromHash();
		if (initial) {
			setFilter(initial, false);
			dir.scrollIntoView({ block: 'start' });
		} else {
			render();
		}
	}

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
