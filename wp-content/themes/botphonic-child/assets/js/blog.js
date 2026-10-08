/**
 * Botphonic — blog single + archive behaviour.
 *
 * Single source of truth for blog behaviour: this file absorbed the former
 * assets/js/single.js (in-content copy blocks, § 4).
 *
 * The table of contents is no longer here. It moved to assets/js/toc.js, which
 * the customer stories and the comparison guides load too — the guides used to
 * carry a second implementation of it. Everything left in this file answers
 * markup only a post or the blog archive prints.
 *
 * Progressive enhancement only. Everything here degrades to a working page
 * when JavaScript is unavailable:
 *   - glassy navbar        → stays translucent, just never "firms up"
 *   - reading progress     → rail stays at 0 width (visually absent)
 *   - share / copy link    → native links keep working, copy button hides
 *   - copy blocks          → the code/script text is still shown and selectable
 *   - "More topics"        → native <details>, only the click-away closing is
 *                            added here
 *   - FAQs                 → the shared botphonic-faqs.js component owns
 *                            native grouping and its legacy-browser fallback
 *
 * `html.bpg-blog-html` is printed server-side and carries the CSS that must
 * work without JavaScript. `html.bpg-js`, added below, gates the enhancements
 * that would otherwise leave dead controls on the page.
 *
 * Scope: elements inside .bpg-single / .bpg-archive plus #wrapper-navbar on
 * pages carrying the body.bpg-blog class. Nothing else is touched.
 */
(function () {
	'use strict';

	var MOBILE_QUERY = '(max-width: 991.98px)';
	var root = document.documentElement;

	root.classList.add('bpg-blog-html', 'bpg-js');

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

	/* ------------------------------------------------------------------
	 * 1. Glassy navbar state + reading progress
	 * ---------------------------------------------------------------- */
	function initScrollChrome() {
		var navbar = document.getElementById('wrapper-navbar');
		var progress = document.querySelector('[data-bpg-progress]');
		var article = document.querySelector('[data-bpg-article]');

		if (!navbar && !(progress && article)) {
			return;
		}

		var stuck = false;

		var measure = rafThrottle(function () {
			var y = window.pageYOffset || root.scrollTop || 0;

			if (navbar) {
				var shouldStick = y > 12;
				if (shouldStick !== stuck) {
					stuck = shouldStick;
					navbar.classList.toggle('bpg-nav-stuck', stuck);
				}
			}

			if (progress && article) {
				/* Progress is measured against the article, not the document, so
				 * the footer and the CTA blocks below it do not count as
				 * "unread". The last screenful of the article is treated as
				 * finished, which is why the span is short by 55vh. */
				var start = article.getBoundingClientRect().top + y;
				var span = article.offsetHeight - window.innerHeight * 0.55;
				var pct = 0;

				if (span > 0) {
					pct = ((y - start) / span) * 100;
				} else if (y > start) {
					/* Article shorter than the viewport: there is nothing to
					 * track, so show it as read rather than stuck at zero. */
					pct = 100;
				}

				progress.style.width = Math.min(100, Math.max(0, pct)).toFixed(2) + '%';
			}
		});

		window.addEventListener('scroll', measure, { passive: true });
		window.addEventListener('resize', measure, { passive: true });
		measure();

		/* The Max Mega Menu drawer needs an opaque bar behind it. The `:has()`
		 * rule in hfe.css covers modern browsers; this mirrors it as a class
		 * for anything older. */
		var toggle = navbar && navbar.querySelector('.mega-menu-toggle');
		if (toggle && 'MutationObserver' in window) {
			new MutationObserver(function () {
				navbar.classList.toggle(
					'bpg-nav-open',
					toggle.classList.contains('mega-menu-open')
				);
			}).observe(toggle, { attributes: true, attributeFilter: ['class'] });
		}
	}

	/* ------------------------------------------------------------------
	 * 2. Share rail — clipboard copy with a visible confirmation
	 * ---------------------------------------------------------------- */
	function initShare() {
		var copyBtn = document.querySelector('[data-bpg-copy]');
		if (!copyBtn) {
			return;
		}

		if (!navigator.clipboard) {
			copyBtn.hidden = true;
			return;
		}

		var label = copyBtn.querySelector('[data-bpg-copy-label]');
		var defaultLabel = label ? label.textContent : '';
		var timer = null;

		copyBtn.addEventListener('click', function () {
			var url = copyBtn.getAttribute('data-bpg-copy') || window.location.href;

			navigator.clipboard.writeText(url).then(
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
				function () {
					/* Clipboard denied (insecure context / permissions) — leave
					 * the visible share links as the working path. */
				}
			);
		});
	}

	/* ------------------------------------------------------------------
	 * 3. In-content copy blocks
	 *
	 * Posts mark a block as copyable with `.copy-text`. Each one gets wrapped
	 * in `.copy-wrap` with a copy button (styled in blog.css § 7.12). Merged
	 * here from the former assets/js/single.js.
	 * ---------------------------------------------------------------- */
	function initCopyBlocks() {
		var blocks = document.querySelectorAll('.bpg-single .copy-text');
		if (!blocks.length || !navigator.clipboard) {
			return;
		}

		var copyIcon =
			'<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">' +
			'<path d="M8 8H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-3" stroke="currentColor" stroke-width="2"/>' +
			'<rect x="8" y="3" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"/>' +
			'</svg>';

		var doneIcon =
			'<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
			'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
			'<path d="M20 6 9 17l-5-5"/></svg>';

		Array.prototype.forEach.call(blocks, function (block) {
			if (block.parentNode && block.parentNode.classList.contains('copy-wrap')) {
				return;
			}

			var wrap = document.createElement('div');
			wrap.className = 'copy-wrap';

			var button = document.createElement('button');
			button.type = 'button';
			button.className = 'copy-trigger';
			button.setAttribute('title', 'Copy');
			button.innerHTML = copyIcon + '<span>Copy</span>';

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

			navigator.clipboard.writeText(text).then(function () {
				button.classList.add('copied');
				button.innerHTML = doneIcon + '<span>Copied</span>';
				window.setTimeout(function () {
					button.classList.remove('copied');
					button.innerHTML = copyIcon + '<span>Copy</span>';
				}, 1500);
			});
		});
	}

	/* ------------------------------------------------------------------
	 * 4. "More topics" disclosure — close on outside click / Escape
	 * ---------------------------------------------------------------- */
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

	/* ------------------------------------------------------------------
	 * 4.1 Category rail — bring the active topic into view
	 *
	 * Below 768px the rail is a one-row horizontal scroller (blog.css § 17).
	 * On a category archive the active chip can sit past the right edge, so a
	 * reader lands on the page with no visible sign of which topic they are
	 * browsing. Centring it on load fixes that.
	 *
	 * scrollLeft is assigned rather than calling scrollIntoView(), because that
	 * method also scrolls the nearest scrollable ancestors — i.e. the document —
	 * which would jump the reader past the hero.
	 * ---------------------------------------------------------------- */
	function initFilterRail() {
		var track = document.querySelector('.bpg-filters__track');
		if (!track) {
			return;
		}

		var active = track.querySelector('.bpg-filter.is-active');

		// Nothing to do on desktop, where the rail does not scroll.
		if (!active || track.scrollWidth <= track.clientWidth + 1) {
			return;
		}

		// Measured off rects rather than offsetLeft: the chips' offsetParent is
		// `.bpg-filters` (it is positioned), not the track, so offsetLeft would
		// silently drift if the rail ever gained padding.
		var chip = active.getBoundingClientRect();
		var rail = track.getBoundingClientRect();
		var offset = (chip.left - rail.left) - (rail.width - chip.width) / 2;

		// The browser clamps to the maximum scroll position by itself.
		track.scrollLeft = Math.max(0, track.scrollLeft + offset);
	}

	ready(function () {
		initScrollChrome();
		initShare();
		initCopyBlocks();
		initMoreTopics();
		initFilterRail();
	});
})();
