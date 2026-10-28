/**
 * Botphonic — site search behaviour.
 *
 * Enhances every `form[data-bps-suggest]` (searchform.php) into an ARIA 1.2
 * combobox with live suggestions from GET /wp-json/botphonic/v1/search, and
 * adds two small conveniences:
 *   - a clear button inside the field
 *   - "/" anywhere on the page focuses the search field
 *
 * Progressive enhancement only: without JavaScript the form is a plain GET
 * form and the search page works exactly the same.
 *
 * window.bpsSearch = { endpoint, searchUrl } is printed by
 * inc/function-search.php.
 */
(function () {
	'use strict';

	var config = window.bpsSearch || {};
	var MIN_CHARS = 2;
	var DEBOUNCE_MS = 200;
	var cache = Object.create(null);

	var ICONS = {
		articles: '<rect x="4" y="3" width="16" height="18" rx="2.5"/><path d="M8 8h8M8 12h8M8 16h5"/>',
		comparisons: '<path d="M12 3v18M7 7h10"/><path d="M4 12l3-5 3 5a3 3 0 0 1-6 0zM14 12l3-5 3 5a3 3 0 0 1-6 0z"/>',
		stories: '<path d="M8 4h8v5a4 4 0 0 1-8 0V4z"/><path d="M16 5h3v2a3 3 0 0 1-3 3M8 5H5v2a3 3 0 0 0 3 3M12 13v4M8.5 21h7M9.5 17h5"/>',
		pages: '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/>',
		arrow: '<path d="M5 12h14M13 6l6 6-6 6"/>'
	};

	document.documentElement.classList.add('bps-js');

	function svg(name) {
		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' + (ICONS[name] || ICONS.pages) + '</svg>';
	}

	function escapeHtml(text) {
		return String(text).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}

	function escapeRegExp(text) {
		return text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
	}

	/** Escape, then wrap each search word in <mark>. */
	function highlight(text, term) {
		var safe = escapeHtml(text);
		var words = term.split(/\s+/).filter(function (w) { return w.length > 1; })
			.sort(function (a, b) { return b.length - a.length; })
			.map(function (w) { return escapeRegExp(escapeHtml(w)); });

		if (!words.length) {
			return safe;
		}

		return safe.replace(new RegExp('(' + words.join('|') + ')', 'gi'), '<mark class="bps-mark">$1</mark>');
	}

	function enhance(form) {
		var input = form.querySelector('input[name="s"]');
		var list = form.querySelector('.bps-suggest');
		var clear = form.querySelector('[data-bps-clear]');
		var status = form.querySelector('[data-bps-status]');
		var typeField = form.querySelector('input[name="type"]');

		if (!input) {
			return;
		}

		var timer = 0;
		var controller = null;
		var activeIndex = -1;
		var lastTerm = '';

		function syncValueState() {
			var hasValue = input.value.length > 0;
			form.classList.toggle('has-value', hasValue);
			if (clear) {
				clear.hidden = !hasValue;
			}
		}

		if (clear) {
			clear.addEventListener('click', function () {
				input.value = '';
				syncValueState();
				close();
				input.focus();
			});
		}

		syncValueState();

		// Without an endpoint or a listbox there is nothing more to enhance.
		if (!list || !config.endpoint || !window.fetch) {
			input.addEventListener('input', syncValueState);
			return;
		}

		function options() {
			return Array.prototype.slice.call(list.querySelectorAll('[role="option"]'));
		}

		function setActive(index) {
			var items = options();

			items.forEach(function (item, i) {
				var on = i === index;
				item.classList.toggle('is-active', on);
				item.setAttribute('aria-selected', on ? 'true' : 'false');
			});

			activeIndex = index;

			if (index >= 0 && items[index]) {
				input.setAttribute('aria-activedescendant', items[index].id);
				items[index].scrollIntoView({ block: 'nearest' });
			} else {
				input.removeAttribute('aria-activedescendant');
			}
		}

		function open() {
			list.hidden = false;
			input.setAttribute('aria-expanded', 'true');
		}

		function close() {
			list.hidden = true;
			input.setAttribute('aria-expanded', 'false');
			setActive(-1);
		}

		function announce(text) {
			if (status) {
				status.textContent = text;
			}
		}

		function allResultsUrl(term) {
			var url = new URL(config.searchUrl || form.action, window.location.href);
			url.searchParams.set('s', term);
			if (typeField && typeField.value) {
				url.searchParams.set('type', typeField.value);
			}
			return url.toString();
		}

		function render(term, data) {
			var id = list.id;
			var html = '';

			if (!data.items.length) {
				html = '<p class="bps-suggest__empty">No quick matches for &ldquo;' + escapeHtml(term) + '&rdquo;. Press Enter to search everything.</p>';
				announce('No suggestions.');
			} else {
				data.items.forEach(function (item, i) {
					var thumb = item.thumb
						? '<img src="' + escapeHtml(item.thumb) + '" alt="" loading="lazy" decoding="async">'
						: svg(item.type);

					html += '<a class="bps-suggest__item" role="option" aria-selected="false" id="' + id + '-opt-' + i + '" href="' + escapeHtml(item.url) + '">'
						+ '<span class="bps-suggest__thumb">' + thumb + '</span>'
						+ '<span class="bps-suggest__text">'
						+ '<span class="bps-suggest__title">' + highlight(item.title, term) + '</span>'
						+ '<span class="bps-suggest__type">' + escapeHtml(item.typeLabel) + '</span>'
						+ '</span></a>';
				});

				announce(data.items.length + ' suggestion' + (data.items.length === 1 ? '' : 's') + ' available. Use up and down arrows to browse.');
			}

			html += '<a class="bps-suggest__all" role="option" aria-selected="false" id="' + id + '-opt-all" href="' + escapeHtml(allResultsUrl(term)) + '">'
				+ '<span>' + (data.total > data.items.length ? 'See all ' + data.total + ' results' : 'Search for &ldquo;' + escapeHtml(term) + '&rdquo;') + '</span>'
				+ svg('arrow') + '</a>';

			list.innerHTML = html;
			activeIndex = -1;
			open();
		}

		function fetchSuggestions(term) {
			var key = (typeField ? typeField.value : '') + '|' + term.toLowerCase();

			if (cache[key]) {
				render(term, cache[key]);
				return;
			}

			if (controller) {
				controller.abort();
			}
			controller = window.AbortController ? new AbortController() : null;

			if (list.hidden || !list.innerHTML) {
				list.innerHTML = '<p class="bps-suggest__loading">Searching&hellip;</p>';
				open();
			}

			var url = new URL(config.endpoint, window.location.href);
			url.searchParams.set('q', term);
			if (typeField && typeField.value) {
				url.searchParams.set('type', typeField.value);
			}

			fetch(url.toString(), { signal: controller ? controller.signal : undefined, headers: { Accept: 'application/json' } })
				.then(function (response) {
					if (!response.ok) {
						throw new Error('HTTP ' + response.status);
					}
					return response.json();
				})
				.then(function (data) {
					data = { items: data.items || [], total: data.total || 0 };
					cache[key] = data;

					// Ignore stale responses: the user kept typing.
					if (input.value.trim() === term) {
						render(term, data);
					}
				})
				.catch(function (error) {
					if (error && error.name === 'AbortError') {
						return;
					}
					close();
				});
		}

		input.addEventListener('input', function () {
			var term = input.value.trim();

			syncValueState();
			window.clearTimeout(timer);

			if (term.length < MIN_CHARS) {
				lastTerm = '';
				if (controller) {
					controller.abort();
				}
				close();
				list.innerHTML = '';
				return;
			}

			if (term === lastTerm) {
				return;
			}

			timer = window.setTimeout(function () {
				lastTerm = term;
				fetchSuggestions(term);
			}, DEBOUNCE_MS);
		});

		input.addEventListener('keydown', function (event) {
			var items = options();

			switch (event.key) {
				case 'ArrowDown':
					if (list.hidden) {
						if (items.length) {
							open();
						}
						return;
					}
					event.preventDefault();
					setActive(activeIndex + 1 >= items.length ? 0 : activeIndex + 1);
					break;

				case 'ArrowUp':
					if (list.hidden) {
						return;
					}
					event.preventDefault();
					setActive(activeIndex - 1 < 0 ? items.length - 1 : activeIndex - 1);
					break;

				case 'Enter':
					if (!list.hidden && activeIndex >= 0 && items[activeIndex]) {
						event.preventDefault();
						window.location.href = items[activeIndex].href;
					}
					break;

				case 'Escape':
					if (!list.hidden) {
						event.preventDefault();
						close();
					} else if (input.value) {
						event.preventDefault();
						input.value = '';
						syncValueState();
					}
					break;
			}
		});

		input.addEventListener('focus', function () {
			if (input.value.trim().length >= MIN_CHARS && options().length) {
				open();
			}
		});

		// Keep the list open while the pointer is down on it, so a click on an
		// option lands before blur closes it.
		list.addEventListener('mousedown', function (event) {
			event.preventDefault();
		});

		list.addEventListener('mousemove', function (event) {
			var option = event.target.closest('[role="option"]');
			if (option) {
				setActive(options().indexOf(option));
			}
		});

		input.addEventListener('blur', function () {
			window.setTimeout(close, 120);
		});

		document.addEventListener('click', function (event) {
			if (!form.contains(event.target)) {
				close();
			}
		});

		// Never submit an empty search.
		form.addEventListener('submit', function (event) {
			if (!input.value.trim()) {
				event.preventDefault();
				input.focus();
			}
		});
	}

	/** "/" focuses the first search field on the page, like most doc sites. */
	function bindSlashShortcut() {
		document.addEventListener('keydown', function (event) {
			if (event.key !== '/' || event.ctrlKey || event.metaKey || event.altKey) {
				return;
			}

			var target = event.target;
			var tag = target && target.tagName;

			if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || (target && target.isContentEditable)) {
				return;
			}

			var field = document.querySelector('form[data-bps-suggest] input[name="s"]');
			if (field) {
				event.preventDefault();
				field.focus();
				field.select();
			}
		});
	}

	function init() {
		Array.prototype.forEach.call(document.querySelectorAll('form[data-bps-suggest]'), enhance);
		bindSlashShortcut();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init, { once: true });
	} else {
		init();
	}
})();
