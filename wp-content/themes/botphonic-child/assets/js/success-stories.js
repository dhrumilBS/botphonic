/**
 * Botphonic — Customer Stories behaviour.
 *
 * Everything the story views share with the blog (sticky section nav, reading
 * progress rail, scroll-spy, clipboard copy and navbar chrome) is handled by
 * assets/js/blog.js, which is enqueued as this file's companion. FAQ accordion
 * behavior is owned by the shared botphonic-faqs.js component.
 *
 * What is left is the count-up on the result metrics. Progressive enhancement
 * only: the final value is printed server-side, so with JavaScript off (or
 * `prefers-reduced-motion: reduce`) the reader simply sees the number straight
 * away instead of watching it climb.
 */
(function () {
	'use strict';

	var DURATION = 1100;

	function ready(fn) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', fn, { once: true });
		} else {
			fn();
		}
	}

	/**
	 * Format a number the way the authored value was written, so a count-up
	 * never changes the shape of the figure it lands on: "1,240" keeps its
	 * separator and "4.8" keeps its single decimal.
	 *
	 * @param {number} value    Current value.
	 * @param {number} decimals Decimal places to keep.
	 * @param {boolean} grouped Whether to group thousands.
	 * @return {string}
	 */
	function format(value, decimals, grouped) {
		var fixed = value.toFixed(decimals);

		if (!grouped) {
			return fixed;
		}

		var parts = fixed.split('.');
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

		return parts.join('.');
	}

	/**
	 * Animate one counter from zero to its target.
	 *
	 * @param {HTMLElement} el Element carrying data-bpg-count.
	 */
	function run(el) {
		var target = parseFloat(el.getAttribute('data-bpg-count'));

		if (isNaN(target)) {
			return;
		}

		var authored = el.getAttribute('data-bpg-count-text') || String(target);
		var dot = authored.indexOf('.');
		var decimals = dot === -1 ? 0 : authored.length - dot - 1;
		var grouped = authored.indexOf(',') !== -1 || Math.abs(target) >= 10000;
		var start = null;

		function step(now) {
			if (start === null) {
				start = now;
			}

			var progress = Math.min(1, (now - start) / DURATION);

			// easeOutCubic — fast off the mark, settles on the number.
			var eased = 1 - Math.pow(1 - progress, 3);

			el.textContent = format(target * eased, decimals, grouped);

			if (progress < 1) {
				window.requestAnimationFrame(step);
			} else {
				// Land on the authored string, so rounding can never leave the
				// tile one digit off what the editor typed.
				el.textContent = authored;
			}
		}

		window.requestAnimationFrame(step);
	}

	function initCounters() {
		var counters = document.querySelectorAll('[data-bpg-count]');

		if (!counters.length) {
			return;
		}

		var reduced =
			window.matchMedia &&
			window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		// No observer (or no appetite for motion): the server-rendered value is
		// already correct, so there is nothing to do.
		if (reduced || !('IntersectionObserver' in window)) {
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					// Once only: re-running on every scroll back would reset a
					// figure the reader has already read.
					observer.unobserve(entry.target);
					run(entry.target);
				});
			},
			{ rootMargin: '0px 0px -12% 0px', threshold: 0.35 }
		);

		Array.prototype.forEach.call(counters, function (el) {
			// Zeroed only now, so a failed observer leaves the real number on
			// screen rather than a stuck "0".
			el.textContent = '0';
			observer.observe(el);
		});
	}

	ready(initCounters);
})();
