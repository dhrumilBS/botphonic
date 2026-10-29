/**
 * Botphonic — Alternatives (comparison guides) single view.
 *
 * The comparison is the only thing on the page that no other view has: the
 * collapsible feature groups of the classic table, or the picker and folding
 * sections of the versus layout, whichever the guide prints.
 *
 * The three functions that used to sit alongside it — a collapse for the sidebar
 * contents list, a second one for a phone-only copy of that list, and an
 * IntersectionObserver scroll-spy feeding both — are gone. They were a parallel
 * implementation of the card the blog and the customer stories were already
 * running, written because assets/js/blog.js could not be pointed at a
 * hand-built nav without double-binding its toggle. That card now lives in
 * template-parts/toc.php and assets/js/toc.js, which all three views load.
 *
 * Nothing here is required for the page to work. The table renders every
 * category heading, all anchors are plain hrefs, and the FAQ list is native
 * <details> — so with JavaScript off the only thing lost is the ability to fold
 * a category away.
 *
 * @package Botphonic
 */
(function () {
	'use strict';

	/**
	 * Collapsible feature groups.
	 *
	 * The toggle is a real <button aria-controls>, so Enter, Space and focus
	 * order all come from the platform; this only has to move the classes and
	 * keep aria-expanded honest. One group at a time, because two open
	 * categories in a horizontally scrolling table is more rows than the viewport
	 * can hold.
	 */
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

	/**
	 * Versus comparison (template-parts/alt-compare-vs.php).
	 *
	 * The brand column always shows; the picker chooses which rivals stand
	 * beside it — any number, or all of them with "All platforms". Columns are
	 * shown and hidden by `show-N` classes on the root, so a change is one class
	 * flip rather than a pass over every cell. `--bpg-alt-vs-cols` tells the
	 * phone layout how many columns remain, and `is-wide` lets four or more
	 * rivals scroll sideways on desktop instead of squeezing.
	 *
	 * The server renders every column and section open with the picker hidden;
	 * `.is-ready` is what turns this layout on.
	 */
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

		// A phone fits the brand and one rival side by side; start there.
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

			// Section headers span the label column, the brand and the picks.
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

		// "All platforms" selects every rival; pressed again it goes back to
		// the first one alone, since at least one has to stay.
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

		// "At a glance" has no toggle and stays open. The category sections are
		// an accordion: all start closed and opening one closes the others.
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

			// The whole header row is a target; the button stays the one
			// keyboard stop.
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

	function init() {
		var root = document.querySelector('.bpg-single.bpg-alt');

		if (!root) {
			return;
		}

		initFeatureGroups(root);
		initVersus(root);
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
