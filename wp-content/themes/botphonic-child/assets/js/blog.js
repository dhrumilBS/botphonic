(function () {
	'use strict';

	var root = document.documentElement;

	root.classList.add('bpg-blog-html', 'bpg-js');

	// Run once the DOM is ready.
	function ready(fn) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', fn, { once: true });
		} else {
			fn();
		}
	}

	// Limit a handler to one call per animation frame.
	function rafThrottle(fn) {
		var queued = false;

		return function () {
			if (queued) {
				return;
			}
			queued = true;
			window.requestAnimationFrame(function () {
				queued = false;
				fn();
			});
		};
	}

	// Copy text to the clipboard, with a fallback for older browsers.
	function copyText(text) {
		function legacyCopy() {
			return new Promise(function (resolve, reject) {
				var area = document.createElement('textarea');
				area.value = text;
				area.setAttribute('readonly', '');
				area.style.position = 'fixed';
				area.style.top = '0';
				area.style.left = '0';
				area.style.opacity = '0';
				document.body.appendChild(area);
				area.select();
				area.setSelectionRange(0, text.length);

				var ok = false;
				try {
					ok = document.execCommand('copy');
				} catch (e) {
					ok = false;
				}
				document.body.removeChild(area);

				if (ok) {
					resolve();
				} else {
					reject(new Error('copy failed'));
				}
			});
		}

		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(text).catch(legacyCopy);
		}
		return legacyCopy();
	}

	var COPY_ICON =
		'<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">' +
		'<path d="M8 8H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-3" stroke="currentColor" stroke-width="2"/>' +
		'<rect x="8" y="3" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>' +
		'</svg>';

	var DONE_ICON =
		'<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
		'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
		'<path d="M20 6 9 17l-5-5"/></svg>';

	// Reading progress bar. The header's sticky state lives in header.js.
	function initScrollChrome() {
		var progress = document.querySelector('[data-bpg-progress]');
		var article = document.querySelector('[data-bpg-article]');

		if (!progress || !article) {
			return;
		}

		var measure = rafThrottle(function () {
			var y = window.pageYOffset || root.scrollTop || 0;
			var start = article.getBoundingClientRect().top + y;
			var span = article.offsetHeight - window.innerHeight * 0.55;
			var pct = 0;

			if (span > 0) {
				pct = ((y - start) / span) * 100;
			} else if (y > start) {
				pct = 100;
			}

			progress.style.width = Math.min(100, Math.max(0, pct)).toFixed(2) + '%';
		});

		window.addEventListener('scroll', measure, { passive: true });
		window.addEventListener('resize', measure, { passive: true });
		measure();
	}

	// Share rail "Copy link" button.
	function initShare() {
		var copyBtn = document.querySelector('[data-bpg-copy]');
		if (!copyBtn) {
			return;
		}

		var label = copyBtn.querySelector('[data-bpg-copy-label]');
		var defaultLabel = label ? label.textContent : '';
		var timer = null;

		copyBtn.addEventListener('click', function () {
			var url = copyBtn.getAttribute('data-bpg-copy') || window.location.href;

			copyText(url).then(
				function () {
					copyBtn.classList.add('is-copied');
					if (label) {
						label.textContent = 'Link copied';
					}
					window.clearTimeout(timer);
					timer = window.setTimeout(function () {
						copyBtn.classList.remove('is-copied');
						if (label) {
							label.textContent = defaultLabel;
						}
					}, 2000);
				},
				function () {}
			);
		});
	}

	// In-post .copy-text blocks get a Copy button.
	function initCopyBlocks() {
		var blocks = document.querySelectorAll('.bpg-single .copy-text');
		if (!blocks.length) {
			return;
		}

		Array.prototype.forEach.call(blocks, function (block) {
			if (block.parentNode && block.parentNode.classList.contains('copy-wrap')) {
				return;
			}

			var wrap = document.createElement('div');
			wrap.className = 'copy-wrap';

			var button = document.createElement('button');
			button.type = 'button';
			button.className = 'copy-trigger';
			button.innerHTML = COPY_ICON + '<span>Copy</span>';

			block.parentNode.insertBefore(wrap, block);
			wrap.appendChild(button);
			wrap.appendChild(block);
		});

		document.addEventListener('click', function (event) {
			var button = event.target.closest
				? event.target.closest('.copy-trigger')
				: null;
			if (!button) {
				return;
			}

			var wrap = button.closest('.copy-wrap');
			var target = wrap ? wrap.querySelector('.copy-text') : null;
			if (!target) {
				return;
			}

			var text = target.dataset.copy || target.value || target.textContent.trim();
			if (!text) {
				return;
			}

			copyText(text).then(
				function () {
					button.classList.add('copied');
					button.innerHTML = DONE_ICON + '<span>Copied</span>';
					window.setTimeout(function () {
						button.classList.remove('copied');
						button.innerHTML = COPY_ICON + '<span>Copy</span>';
					}, 1500);
				},
				function () {
					button.innerHTML = '<span>Select to copy</span>';
					window.setTimeout(function () {
						button.innerHTML = COPY_ICON + '<span>Copy</span>';
					}, 2500);
				}
			);
		});
	}

	var CHECKLIST_STORE = 'bpg-checklist:' + window.location.pathname;

	// Load saved ticks for this post.
	function readChecklistState() {
		try {
			return JSON.parse(window.localStorage.getItem(CHECKLIST_STORE)) || {};
		} catch (e) {
			return {};
		}
	}

	// Save ticks for this post.
	function writeChecklistState(state) {
		try {
			window.localStorage.setItem(CHECKLIST_STORE, JSON.stringify(state));
		} catch (e) {}
	}

	function cleanText(node) {
		return (node.textContent || '').replace(/\s+/g, ' ').trim();
	}

	// Use the heading right before the list as its title.
	function headingBefore(list) {
		var prev = list.previousElementSibling;
		if (prev && /^H[2-5]$/.test(prev.tagName)) {
			return cleanText(prev);
		}
		return '';
	}

	// Interactive ul.checklist with progress, copy and clear.
	function initChecklists() {
		var lists = document.querySelectorAll('.bpg-single ul.checklist');
		if (!lists.length) {
			return;
		}

		var saved = readChecklistState();

		Array.prototype.forEach.call(lists, function (list, listIndex) {
			if (list.parentNode && list.parentNode.classList.contains('bp-checklist')) {
				return;
			}

			var id = list.getAttribute('data-checklist-id') || 'list-' + listIndex;
			var title = list.getAttribute('data-title') || headingBefore(list) || 'Checklist';
			var withSource = !list.hasAttribute('data-no-source');
			var savedTicks = saved[id] || [];

			var items = Array.prototype.filter.call(list.children, function (el) {
				return el.tagName === 'LI';
			});
			if (!items.length) {
				return;
			}

			var box = document.createElement('div');
			box.className = 'bp-checklist';

			var head = document.createElement('div');
			head.className = 'bp-checklist__head';

			var status = document.createElement('div');
			status.className = 'bp-checklist__status';

			var count = document.createElement('p');
			count.className = 'bp-checklist__count';
			count.setAttribute('aria-live', 'polite');

			var resetBtn = document.createElement('button');
			resetBtn.type = 'button';
			resetBtn.className = 'bp-checklist__reset';
			resetBtn.textContent = 'Clear ticks';

			status.appendChild(count);
			status.appendChild(resetBtn);

			var copyBtn = document.createElement('button');
			copyBtn.type = 'button';
			copyBtn.className = 'bp-checklist__copy';
			copyBtn.innerHTML = COPY_ICON + '<span>Copy checklist</span>';

			head.appendChild(status);
			head.appendChild(copyBtn);

			var bar = document.createElement('div');
			bar.className = 'bp-checklist__bar';
			bar.setAttribute('aria-hidden', 'true');
			var fill = document.createElement('span');
			bar.appendChild(fill);

			list.parentNode.insertBefore(box, list);
			box.appendChild(head);
			box.appendChild(bar);
			box.appendChild(list);

			var boxes = items.map(function (li, i) {
				var input = li.querySelector('input[type="checkbox"]');

				if (!input) {
					var label = document.createElement('label');
					label.className = 'bp-checklist__label';

					input = document.createElement('input');
					input.type = 'checkbox';
					input.className = 'custom-checkbox';

					var text = document.createElement('span');
					text.className = 'bp-checklist__text';
					while (li.firstChild) {
						text.appendChild(li.firstChild);
					}

					label.appendChild(input);
					label.appendChild(text);
					li.appendChild(label);
				} else {
					input.classList.add('custom-checkbox');
				}

				input.checked = savedTicks[i] === true || input.checked;
				li.classList.toggle('checked', input.checked);
				return input;
			});

			function update() {
				var done = boxes.filter(function (b) {
					return b.checked;
				}).length;
				var total = boxes.length;

				count.textContent = done === total
					? 'All ' + total + ' done'
					: done + ' of ' + total + ' done';
				fill.style.width = ((done / total) * 100).toFixed(1) + '%';
				box.classList.toggle('is-complete', done === total);
				resetBtn.disabled = done === 0;
			}

			function save() {
				saved[id] = boxes.map(function (b) {
					return b.checked;
				});
				writeChecklistState(saved);
			}

			list.addEventListener('change', function (event) {
				var input = event.target;
				if (!input || input.type !== 'checkbox') {
					return;
				}
				var li = input.closest('li');
				if (li) {
					li.classList.toggle('checked', input.checked);
				}
				save();
				update();
			});

			resetBtn.addEventListener('click', function () {
				boxes.forEach(function (b) {
					b.checked = false;
					var li = b.closest('li');
					if (li) {
						li.classList.remove('checked');
					}
				});
				save();
				update();
				if (boxes[0]) {
					boxes[0].focus();
				}
			});

			var copyTimer = null;

			copyBtn.addEventListener('click', function () {
				var lines = items.map(function (li, i) {
					var textNode = li.querySelector('.bp-checklist__text') || li;
					return (boxes[i].checked ? '- [x] ' : '- [ ] ') + cleanText(textNode);
				});

				var text = title + '\n\n' + lines.join('\n');
				if (withSource) {
					text += '\n\nSource: ' + window.location.origin + window.location.pathname;
				}

				copyText(text).then(
					function () {
						copyBtn.classList.add('is-copied');
						copyBtn.innerHTML = DONE_ICON + '<span>Checklist copied</span>';
					},
					function () {
						copyBtn.innerHTML = '<span>Select to copy</span>';
					}
				).then(function () {
					window.clearTimeout(copyTimer);
					copyTimer = window.setTimeout(function () {
						copyBtn.classList.remove('is-copied');
						copyBtn.innerHTML = COPY_ICON + '<span>Copy checklist</span>';
					}, 2000);
				});
			});

			update();
		});
	}

	// Close "More topics" on outside click or Escape.
	function initMoreTopics() {
		var more = document.querySelector('.bpg-more');
		if (!more) {
			return;
		}

		document.addEventListener('click', function (event) {
			if (more.open && !more.contains(event.target)) {
				more.open = false;
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && more.open) {
				more.open = false;
				var summary = more.querySelector('summary');
				if (summary) {
					summary.focus();
				}
			}
		});
	}

	// Scroll the active category chip into view on mobile.
	function initFilterRail() {
		var track = document.querySelector('.bpg-filters__track');
		if (!track) {
			return;
		}

		var active = track.querySelector('.bpg-filter.is-active');

		if (!active || track.scrollWidth <= track.clientWidth + 1) {
			return;
		}

		var chip = active.getBoundingClientRect();
		var rail = track.getBoundingClientRect();
		var offset = (chip.left - rail.left) - (rail.width - chip.width) / 2;

		track.scrollLeft = Math.max(0, track.scrollLeft + offset);
	}

	ready(function () {
		initScrollChrome();
		initShare();
		initCopyBlocks();
		initChecklists();
		initMoreTopics();
		initFilterRail();
	});
})();