/**
 * Botphonic — Alternatives (comparison guides) single view.
 *
 * One behaviour: the collapsible feature groups in the comparison table. It is
 * the only thing on the page that no other view has.
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

	function init() {
		var root = document.querySelector('.bpg-single.bpg-alt');

		if (!root) {
			return;
		}

		initFeatureGroups(root);
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
