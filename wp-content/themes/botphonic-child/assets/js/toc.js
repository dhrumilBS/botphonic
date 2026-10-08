/**
 * Botphonic — table of contents behaviour, shared by every long-form single.
 *
 * Lifted out of assets/js/blog.js (where it drove the blog and the customer
 * stories) and assets/js/alternatives.js (which had grown a second, parallel
 * implementation for the comparison guides: one function for the sidebar copy,
 * one for a phone-only copy, and an IntersectionObserver scroll-spy of its own).
 * One card, one script, three views. The markup comes from
 * template-parts/toc.php and the styling from assets/css/blog.css §§ 5.1 / 17.
 *
 * Progressive enhancement only. With JavaScript off the card renders expanded
 * instead of collapsed on mobile, stays in the flow instead of pinned, and its
 * links still work: they are ordinary fragment anchors, offset by
 * scroll-padding-top (hfe.css, tightened for the pinned card in blog.css § 17)
 * and animated by scroll-behavior. Nothing here calculates a scroll position.
 *
 * `html.bpg-js` is added below because every pinned rule in blog.css § 17 is
 * gated on it — that gate is what keeps a dead × off a card whose controls are
 * not running. blog.js adds the same class for its own enhancements; both are
 * idempotent, and this file is the only one loaded on all three views.
 *
 * Every [data-bpg-toc] on the page is bound, not just the first. Nothing ships
 * two today, but a component that silently ignores the second copy is a trap for
 * whoever adds one.
 *
 * @package Botphonic
 */
(function () {
	'use strict';

	/*
	 * Collapsed-by-default boundary. Deliberately the wider of the two widths in
	 * play: below 992px the card sits above the article rather than beside it
	 * (blog.css § 17), where an open list would push the first paragraph off the
	 * screen, while the pinning itself only starts at 768px.
	 */
	var MOBILE_QUERY = '(max-width: 991.98px)';

	/* How far down the viewport a heading counts as "current" for the spy. Kept
	 * in step with the scroll-padding the pinned card asks for in § 17, so the
	 * entry lighting up is the one under the card rather than behind it. */
	var SPY_OFFSET = 140;

	document.documentElement.classList.add('bpg-js');

	function ready(fn) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', fn, { once: true });
		} else {
			fn();
		}
	}

	/**
	 * Run `fn(matches)` now and whenever the media query flips.
	 *
	 * @param {string}   query Media query.
	 * @param {Function} fn    Receives the current match state.
	 */
	function onBreakpoint(query, fn) {
		var mql = window.matchMedia(query);

		fn(mql.matches);

		if (typeof mql.addEventListener === 'function') {
			mql.addEventListener('change', function (event) {
				fn(event.matches);
			});
		} else if (typeof mql.addListener === 'function') {
			// Safari < 14.
			mql.addListener(function (event) {
				fn(event.matches);
			});
		}
	}

	/**
	 * Coalesce high-frequency events into one animation frame.
	 *
	 * @param {Function} fn Work to run.
	 * @return {Function} Throttled caller.
	 */
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

	/**
	 * Wire up one contents card.
	 *
	 * @param {Element} toc The [data-bpg-toc] card.
	 */
	function initToc(toc) {
		var nav = toc.querySelector('.bpg-toc__nav');
		var toggle = toc.querySelector('.bpg-toc__toggle');
		var links = nav ? nav.querySelectorAll('a[href^="#"]') : [];

		/*
		 * No headings picked up — a short post, or the blog's TOC plugin bailing
		 * out. Remove the empty shell rather than leaving a card that opens onto
		 * nothing. The views that build their entries in PHP never reach this:
		 * template-parts/toc.php prints nothing for an empty list.
		 */
		if (!links.length) {
			if (toc.parentNode) {
				toc.parentNode.removeChild(toc);
			}
			return;
		}

		/* Tells blog.css § 17 the controls are live, which is what reveals the
		 * phone-only × and tightens scroll-padding for the pinned card. */
		toc.setAttribute('data-bpg-toc-ready', 'true');

		var isMobile = false;

		function isOpen() {
			return 'true' !== toc.getAttribute('data-bpg-collapsed');
		}

		function setExpanded(expanded) {
			toc.setAttribute('data-bpg-collapsed', expanded ? 'false' : 'true');

			if (toggle) {
				toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
			}
		}

		if (toggle) {
			toggle.addEventListener('click', function (event) {
				// Otherwise the document listener below sees the same click and
				// closes what this just opened.
				event.stopPropagation();
				setExpanded(!isOpen());
			});
		}

		/* Collapsed by default on mobile, open on desktop. Driven by matchMedia
		 * so it only fires when the breakpoint actually changes — a resize that
		 * stays on one side of it leaves a reader's own choice alone. */
		onBreakpoint(MOBILE_QUERY, function (matches) {
			isMobile = matches;
			setExpanded(!matches);
		});

		/* Unpin. Drops the card back into the flow rather than off the page, so
		 * a reader who wants the article uncovered keeps the list, the toggle and
		 * the scroll-spy. For this page view only, deliberately not persisted:
		 * a refresh or a return visit pins it again. */
		var closeBtn = toc.querySelector('[data-bpg-toc-close]');

		if (closeBtn) {
			closeBtn.addEventListener('click', function (event) {
				event.stopPropagation();
				toc.setAttribute('data-bpg-toc-pinned', 'false');
				setExpanded(false);

				if (toggle) {
					toggle.focus();
				}
			});
		}

		/* Get out of the way once a heading has been picked. Only on mobile:
		 * on desktop the card is a sidebar that is meant to stay open. */
		Array.prototype.forEach.call(links, function (link) {
			link.addEventListener('click', function () {
				if (isMobile) {
					setExpanded(false);
				}
			});
		});

		/*
		 * Escape and a click outside, both mobile-only. While the card is pinned
		 * it is holding screen over the article, and reaching back for the toggle
		 * should not be the only way to ask for it back. On desktop the same
		 * gestures would collapse a sidebar nobody asked to collapse.
		 */
		document.addEventListener('click', function (event) {
			if (isMobile && isOpen() && !toc.contains(event.target)) {
				setExpanded(false);
			}
		});

		document.addEventListener('keydown', function (event) {
			if (('Escape' === event.key || 'Esc' === event.key) && isMobile && isOpen()) {
				setExpanded(false);

				if (toggle) {
					toggle.focus();
				}
			}
		});

		/* --- scroll-spy ------------------------------------------------ */
		var targets = [];

		Array.prototype.forEach.call(links, function (link) {
			var id = decodeURIComponent(link.hash || '').slice(1);

			if (!id) {
				return;
			}

			var el = document.getElementById(id);

			if (el) {
				targets.push({ link: link, el: el });
			}
		});

		if (!targets.length) {
			return;
		}

		var active = null;

		var spy = rafThrottle(function () {
			var current = targets[0];

			for (var i = 0; i < targets.length; i++) {
				if (targets[i].el.getBoundingClientRect().top - SPY_OFFSET <= 0) {
					current = targets[i];
				} else {
					break;
				}
			}

			if (current.link === active) {
				return;
			}

			if (active) {
				active.classList.remove('bpg-toc-active');
				active.removeAttribute('aria-current');
			}

			active = current.link;
			active.classList.add('bpg-toc-active');
			active.setAttribute('aria-current', 'location');
		});

		window.addEventListener('scroll', spy, { passive: true });
		window.addEventListener('resize', spy, { passive: true });
		spy();
	}

	ready(function () {
		Array.prototype.forEach.call(document.querySelectorAll('[data-bpg-toc]'), initToc);
	});
})();
