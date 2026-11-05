(function () {
	'use strict';

	function initFeatureGroups(root) {
		var buttons = Array.prototype.slice.call(root.querySelectorAll('.bpg-alt-acc__btn'));

		if (!buttons.length) {
			return;
		}

		function panelFor(button) {
			var id = button.getAttribute('aria-controls');

			return id ? document.getElementById(id) : null;
		}

		function setOpen(button, open) {
			var panel = panelFor(button);

			button.setAttribute('aria-expanded', open ? 'true' : 'false');

			if (panel) {
				panel.classList.toggle('is-open', open);
			}
		}

		buttons.forEach(function (button) {
			button.addEventListener('click', function () {
				var willOpen = 'true' !== button.getAttribute('aria-expanded');

				buttons.forEach(function (other) {
					if (other !== button) {
						setOpen(other, false);
					}
				});

				setOpen(button, willOpen);
			});
		});
	}

	function initVersus(root) {
		var vs = root.querySelector('[data-bpg-vs]');

		if (!vs) {
			return;
		}

		var chips = Array.prototype.slice.call(vs.querySelectorAll('[data-bpg-vs-pick]'));
		var all = vs.querySelector('[data-bpg-vs-all]');
		var removers = Array.prototype.slice.call(vs.querySelectorAll('[data-bpg-vs-remove]'));
		var heads = Array.prototype.slice.call(vs.querySelectorAll('[data-bpg-vs-sec]'));
		var spans = Array.prototype.slice.call(vs.querySelectorAll('.bpg-alt-vs__sec-th'));
		var count = vs.querySelector('[data-bpg-vs-count]');
		var note = vs.querySelector('[data-bpg-vs-note]');
		var every = chips.map(function (chip) {
			return chip.getAttribute('data-bpg-vs-pick');
		});

		var selected = chips.filter(function (chip) {
			return 'true' === chip.getAttribute('aria-pressed');
		}).map(function (chip) {
			return chip.getAttribute('data-bpg-vs-pick');
		});

		if (vs.clientWidth < 640 && selected.length > 1) {
			selected = selected.slice(0, 1);
		}

		function say(message) {
			if (note) {
				note.textContent = message || '';
			}
		}

		function apply() {
			for (var i = 1; i <= 9; i++) {
				vs.classList.toggle('show-' + i, selected.indexOf(String(i)) > -1);
			}

			vs.style.setProperty('--bpg-alt-vs-cols', String(selected.length + 1));

			spans.forEach(function (cell) {
				cell.colSpan = selected.length + 2;
			});
			vs.classList.toggle('is-wide', selected.length > 3);

			chips.forEach(function (chip) {
				chip.setAttribute('aria-pressed', selected.indexOf(chip.getAttribute('data-bpg-vs-pick')) > -1 ? 'true' : 'false');
			});

			if (all) {
				all.setAttribute('aria-pressed', selected.length === every.length ? 'true' : 'false');
			}

			removers.forEach(function (button) {
				button.hidden = selected.length < 2;
			});

			if (count) {
				count.textContent = String(selected.length);
			}
		}

		function toggle(p) {
			var at = selected.indexOf(p);

			if (at > -1) {
				if (1 === selected.length) {
					say(vs.getAttribute('data-bpg-vs-msg-min'));
					return;
				}

				selected.splice(at, 1);
			} else {
				selected.push(p);
				selected.sort(function (a, b) {
					return a - b;
				});
			}

			say('');
			apply();
		}

		chips.forEach(function (chip) {
			chip.addEventListener('click', function () {
				toggle(chip.getAttribute('data-bpg-vs-pick'));
			});
		});

		if (all) {
			all.addEventListener('click', function () {
				selected = selected.length === every.length ? every.slice(0, 1) : every.slice();
				say('');
				apply();
			});
		}

		removers.forEach(function (button) {
			button.addEventListener('click', function () {
				toggle(button.getAttribute('data-bpg-vs-remove'));
			});
		});

		function setOpen(button, open) {
			var body = document.getElementById(button.getAttribute('aria-controls'));

			button.setAttribute('aria-expanded', open ? 'true' : 'false');

			if (body) {
				body.hidden = !open;
			}
		}

		heads.forEach(function (button) {
			setOpen(button, false);

			button.addEventListener('click', function () {
				var open = 'true' !== button.getAttribute('aria-expanded');

				heads.forEach(function (other) {
					setOpen(other, other === button && open);
				});
			});

			var head = button.closest('tr');

			if (head) {
				head.addEventListener('click', function (event) {
					if (!button.contains(event.target)) {
						button.click();
					}
				});
			}
		});

		apply();
		vs.classList.add('is-ready');
	}

	function initChecklists(root) {
		var tables = Array.prototype.slice.call(root.querySelectorAll('table.bpg-alt-checklist'));

		if (!tables.length) {
			return;
		}

		var TEXT = {
			copy: 'Copy checklist',
			copied: 'Copied',
			failed: 'Copy failed',
			copiedNote: 'Checklist copied to the clipboard.',
			failedNote: 'This browser blocked copying. Select the table text and copy it instead.',
			of: function (total) {
				return ' of ' + total + ' done';
			}
		};

		var SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';
		var ICON_COPY = SVG + ' stroke-width="2" class="bpg-alt-checklist__icon-copy"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M15 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h3"/></svg>';
		var ICON_DONE = SVG + ' stroke-width="2.2" class="bpg-alt-checklist__icon-done"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
		var ICON_TICK = SVG + ' stroke-width="3"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';

		function list(items) {
			return Array.prototype.slice.call(items || []);
		}

		function el(tag, className) {
			var node = document.createElement(tag);

			if (className) {
				node.className = className;
			}

			return node;
		}

		function textOf(node) {
			if (!node) {
				return '';
			}

			var copy = node.cloneNode(true);

			list(copy.querySelectorAll('br')).forEach(function (br) {
				br.parentNode.replaceChild(document.createTextNode(' '), br);
			});
			list(copy.querySelectorAll('p, div, li')).forEach(function (block) {
				block.appendChild(document.createTextNode(' '));
			});

			return copy.textContent.replace(/\s+/g, ' ').trim();
		}

		function headingBefore(node) {
			while (node && node !== root) {
				for (var prev = node.previousElementSibling; prev; prev = prev.previousElementSibling) {
					if (/^H[23]$/.test(prev.tagName)) {
						return textOf(prev);
					}
				}

				node = node.parentElement;
			}

			return '';
		}

		function hash(value) {
			var h = 5381;

			for (var i = 0; i < value.length; i++) {
				h = ((h << 5) + h + value.charCodeAt(i)) | 0;
			}

			return (h >>> 0).toString(36);
		}

		function load(key) {
			try {
				return window.localStorage.getItem(key) || '';
			} catch (e) {
				return '';
			}
		}

		function save(key, value) {
			try {
				if (-1 === value.indexOf('1')) {
					window.localStorage.removeItem(key);
				} else {
					window.localStorage.setItem(key, value);
				}
			} catch (e) {
			}
		}

		function legacyCopy(value) {
			return new Promise(function (resolve, reject) {
				var area = el('textarea');
				var focused = document.activeElement;
				var ok = false;

				area.value = value;
				area.setAttribute('readonly', '');
				area.setAttribute('aria-hidden', 'true');
				area.style.cssText = 'position:fixed;top:0;left:0;width:1px;height:1px;padding:0;border:0;opacity:0;';
				document.body.appendChild(area);
				area.select();
				area.setSelectionRange(0, value.length);

				try {
					ok = document.execCommand('copy');
				} catch (e) {
					ok = false;
				}

				document.body.removeChild(area);

				if (focused && 'function' === typeof focused.focus) {
					focused.focus({ preventScroll: true });
				}

				if (ok) {
					resolve();
				} else {
					reject(new Error('copy'));
				}
			});
		}

		function copyText(value) {
			if (navigator.clipboard && 'function' === typeof navigator.clipboard.writeText) {
				return navigator.clipboard.writeText(value).catch(function () {
					return legacyCopy(value);
				});
			}

			return legacyCopy(value);
		}

		function headerRow(table) {
			var head = table.tHead;

			if (head && head.rows.length) {
				return head.rows[head.rows.length - 1];
			}

			var rows = table.tBodies.length ? list(table.tBodies[0].rows) : [];
			var first = rows[0];

			if (!first || rows.length < 2) {
				return null;
			}

			var allTh = list(first.cells).every(function (cell) {
				return 'TH' === cell.tagName;
			});

			if (allTh || (textOf(first.cells[0]) && !textOf(rows[1].cells[0]))) {
				table.createTHead().appendChild(first);

				return first;
			}

			return null;
		}

		function setup(table, index) {
			if (table.classList.contains('is-ready')) {
				return;
			}

			var head = headerRow(table);
			var labels = head ? list(head.cells).map(textOf) : [];
			var prefix = 'bpg-alt-cl-' + (index + 1);
			var entries = [];

			list(table.tBodies).forEach(function (body) {
				list(body.rows).forEach(function (row) {
					var cells = list(row.cells);

					if (cells.length < 2 || !textOf(cells[1])) {
						return;
					}

					entries.push({ row: row, box: cells[0], step: cells[1], notes: cells.slice(2) });
				});
			});

			if (!entries.length) {
				return;
			}

			var title = table.caption ? textOf(table.caption) : '';
			var key = 'bpg-alt-checklist:' + window.location.pathname + ':' + hash(entries.map(function (entry) {
				return textOf(entry.step);
			}).join('\n'));
			var saved = load(key);

			entries.forEach(function (entry, i) {
				var check = el('label', 'bpg-alt-checklist__check');
				var input = el('input', 'bpg-alt-checklist__input');
				var mark = el('span', 'bpg-alt-checklist__box');

				if (!entry.step.id) {
					entry.step.id = prefix + '-' + (i + 1);
				}

				entry.notes.forEach(function (cell, k) {
					if (!cell.id) {
						cell.id = entry.step.id + '-note-' + (k + 1);
					}

					if (labels[k + 2]) {
						cell.setAttribute('data-label', labels[k + 2]);
					}
				});

				input.type = 'checkbox';
				input.checked = saved.length === entries.length && '1' === saved.charAt(i);
				input.setAttribute('aria-labelledby', entry.step.id);

				if (entry.notes.length) {
					input.setAttribute('aria-describedby', entry.notes.map(function (cell) {
						return cell.id;
					}).join(' '));
				}

				mark.setAttribute('aria-hidden', 'true');
				mark.innerHTML = ICON_TICK;
				check.appendChild(input);
				check.appendChild(mark);

				if (!textOf(entry.box) && !entry.box.querySelector('img, svg, input')) {
					entry.box.textContent = '';
				}

				entry.box.insertBefore(check, entry.box.firstChild);
				entry.input = input;
			});

			table.setAttribute('role', 'table');
			[table.tHead, table.tFoot].concat(list(table.tBodies)).forEach(function (group) {
				if (group) {
					group.setAttribute('role', 'rowgroup');
				}
			});
			list(table.rows).forEach(function (row) {
				var inHead = 'THEAD' === row.parentNode.tagName;

				row.setAttribute('role', 'row');
				list(row.cells).forEach(function (cell) {
					cell.setAttribute('role', inHead ? 'columnheader' : ('TH' === cell.tagName ? 'rowheader' : 'cell'));
				});
			});

			var wrap = el('div', 'bpg-alt-checklist-wrap');
			var bar = el('div', 'bpg-alt-checklist__bar');
			var heading = el('p', 'bpg-alt-checklist__title');
			var progress = el('div', 'bpg-alt-checklist__progress');
			var count = el('p', 'bpg-alt-checklist__count');
			var countDone = el('b');
			var meter = el('span', 'bpg-alt-checklist__meter');
			var fill = el('span', 'bpg-alt-checklist__meter-fill');
			var button = el('button', 'bpg-alt-checklist__copy');
			var buttonLabel = el('span', 'bpg-alt-checklist__copy-label');
			var status = el('p', 'bpg-alt-checklist__sr');
			var timer = 0;

			if (title) {
				heading.setAttribute('aria-hidden', 'true');
				heading.textContent = title;
				bar.appendChild(heading);
			}

			count.setAttribute('aria-live', 'polite');
			count.setAttribute('aria-atomic', 'true');
			count.appendChild(countDone);
			count.appendChild(document.createTextNode(TEXT.of(entries.length)));
			meter.setAttribute('aria-hidden', 'true');
			meter.appendChild(fill);
			progress.appendChild(count);
			progress.appendChild(meter);

			button.type = 'button';
			button.innerHTML = ICON_COPY + ICON_DONE;
			buttonLabel.textContent = TEXT.copy;
			button.appendChild(buttonLabel);
			status.setAttribute('role', 'status');

			bar.appendChild(progress);
			bar.appendChild(button);
			bar.appendChild(status);

			update();
			table.parentNode.insertBefore(wrap, table);
			wrap.appendChild(bar);
			wrap.appendChild(table);

			function update() {
				var done = 0;

				entries.forEach(function (entry) {
					entry.row.classList.toggle('is-done', entry.input.checked);
					done += entry.input.checked ? 1 : 0;
				});

				countDone.textContent = String(done);
				fill.style.width = Math.round((done / entries.length) * 100) + '%';
				wrap.classList.toggle('is-complete', done === entries.length);
			}

			function feedback(ok) {
				window.clearTimeout(timer);
				button.classList.toggle('is-copied', ok);
				button.classList.toggle('is-failed', !ok);
				buttonLabel.textContent = ok ? TEXT.copied : TEXT.failed;
				status.textContent = ok ? TEXT.copiedNote : TEXT.failedNote;
				timer = window.setTimeout(function () {
					button.classList.remove('is-copied');
					button.classList.remove('is-failed');
					buttonLabel.textContent = TEXT.copy;
					status.textContent = '';
				}, ok ? 2000 : 4000);
			}

			table.addEventListener('change', function (event) {
				if (!event.target.classList.contains('bpg-alt-checklist__input')) {
					return;
				}

				update();
				save(key, entries.map(function (entry) {
					return entry.input.checked ? '1' : '0';
				}).join(''));
			});

			table.addEventListener('click', function (event) {
				var target = event.target;
				var row = target.closest ? target.closest('tr') : null;
				var selection = window.getSelection ? window.getSelection() : null;

				if (!row || target.closest('a, button, input, label, select, textarea')) {
					return;
				}

				if (selection && !selection.isCollapsed && row.contains(selection.anchorNode)) {
					return;
				}

				entries.some(function (entry) {
					if (entry.row === row) {
						entry.input.click();

						return true;
					}

					return false;
				});
			});

			button.addEventListener('click', function () {
				var name = title || headingBefore(wrap);
				var lines = entries.map(function (entry) {
					var parts = [textOf(entry.step)].concat(entry.notes.map(textOf).filter(Boolean));

					return '- [' + (entry.input.checked ? 'x' : ' ') + '] ' + parts.join(' \u2014 ');
				});

				copyText((name ? name + '\n\n' : '') + lines.join('\n')).then(function () {
					feedback(true);
				}, function () {
					feedback(false);
				});
			});

			table.classList.add('is-ready');
		}

		tables.forEach(setup);
	}

	function init() {
		var root = document.querySelector('.bpg-single.bpg-alt');

		if (!root) {
			return;
		}

		initFeatureGroups(root);
		initVersus(root);
		initChecklists(root);
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
