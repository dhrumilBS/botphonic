/**
 * Botphonic — Cold Email landing page behaviour.
 *
 * Replaces the inline <script> that used to sit at the bottom of
 * temp-cold-mail.php.
 *
 * Two behaviours remain. The pro-tip disclosures are gone from here entirely:
 * they are native <details> elements now, so they open, close and expose their
 * state to assistive tech without any JavaScript.
 *
 * Progressive enhancement only:
 *   - feature walkthrough : with JS off every step shows its own copy and its
 *                           own inline image, so the section reads as a list
 *   - copy buttons        : hidden when the Clipboard API is unavailable, and
 *                           the template text stays on screen and selectable
 *
 * Image URLs are read from each button's data-bpcm-img attribute. The old
 * version kept a second copy of all five URLs in a JS object, which had to be
 * edited in step with the PHP array.
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', fn, { once: true });
		} else {
			fn();
		}
	}

	/* ------------------------------------------------------------------
	 * 1. Feature walkthrough — select a step, swap the preview
	 * ---------------------------------------------------------------- */
	function initWalkthrough() {
		var walk = document.querySelector('[data-bpcm-walk]');
		if (!walk) {
			return;
		}

		var preview = walk.querySelector('[data-bpcm-preview]');
		var buttons = walk.querySelectorAll('[data-bpcm-img]');

		if (!preview || buttons.length < 2) {
			return;
		}

		var swapTimer = null;

		function select(button) {
			var item = button.closest('.bpcm-walk__item');
			if (!item || item.classList.contains('is-active')) {
				return;
			}

			Array.prototype.forEach.call(buttons, function (b) {
				var li = b.closest('.bpcm-walk__item');
				if (li) {
					li.classList.remove('is-active');
				}
				b.setAttribute('aria-expanded', 'false');
			});

			item.classList.add('is-active');
			button.setAttribute('aria-expanded', 'true');

			var src = button.getAttribute('data-bpcm-img');
			if (!src || src === preview.getAttribute('src')) {
				return;
			}

			/* Fade out, swap once the new file has decoded, fade back in — so the
			 * panel never flashes a half-loaded image. */
			window.clearTimeout(swapTimer);
			preview.classList.add('is-swapping');

			swapTimer = window.setTimeout(function () {
				var next = new Image();
				next.onload = function () {
					preview.src = src;
					preview.alt = button.getAttribute('data-bpcm-alt') || '';
					preview.classList.remove('is-swapping');
				};
				next.onerror = function () {
					preview.classList.remove('is-swapping');
				};
				next.src = src;
			}, 160);
		}

		Array.prototype.forEach.call(buttons, function (b) {
			b.addEventListener('click', function () {
				select(b);
			});
		});
	}

	/* ------------------------------------------------------------------
	 * 2. Copy-to-clipboard on the template cards
	 * ---------------------------------------------------------------- */
	function initCopy() {
		var buttons = document.querySelectorAll('[data-bpcm-copy]');
		if (!buttons.length) {
			return;
		}

		if (!navigator.clipboard) {
			// No clipboard access (insecure context): hide a control that cannot work.
			Array.prototype.forEach.call(buttons, function (b) {
				b.hidden = true;
			});
			return;
		}

		Array.prototype.forEach.call(buttons, function (button) {
			var label = button.querySelector('[data-bpcm-copy-label]');
			var original = label ? label.textContent : '';
			var timer = null;

			button.addEventListener('click', function () {
				// The text lives in the card, so there is only ever one copy of it.
				var card = button.closest('.bpcm-tpl');
				var body = card ? card.querySelector('.bpcm-tpl__body') : null;
				var text = body ? body.textContent.trim() : '';

				if (!text) {
					return;
				}

				navigator.clipboard.writeText(text).then(
					function () {
						button.classList.add('is-copied');
						if (label) {
							label.textContent = 'Copied';
						}
						window.clearTimeout(timer);
						timer = window.setTimeout(function () {
							button.classList.remove('is-copied');
							if (label) {
								label.textContent = original;
							}
						}, 1800);
					},
					function () {
						/* Permission denied — the text is still on screen to select. */
					}
				);
			});
		});
	}

	ready(function () {
		initWalkthrough();
		initCopy();
	});
})();
