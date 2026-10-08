(function () {
	'use strict';

	/* ════════════ HERO CANVAS WAVES ════════════ */
	/* ════════════ HERO PARTICLES ════════════ */
	/* ════════════ SCROLL TABS ════════════ */

	const TOTAL_TABS = 3;
	const scrollContainer = document.getElementById('bfScrollContainer');
	const tabButtons = document.querySelectorAll('.bf-scroll-tabs .bf-tab-button');
	const contentPanels = document.querySelectorAll('.bf-scroll-tabs .bf-content-panel');

	if (!scrollContainer || !tabButtons.length) return;

	let activeTab = 0;
	let isClickScrolling = false;

	function setActiveTab(index) {
		if (index === activeTab || index < 0 || index >= TOTAL_TABS) return;

		tabButtons[activeTab].classList.remove('bf-active');
		contentPanels[activeTab].classList.remove('bf-active');
		activeTab = index;
		tabButtons[activeTab].classList.add('bf-active');
		contentPanels[activeTab].classList.add('bf-active');
	}

	function handleScroll() {
		if (window.innerWidth <= 768 || isClickScrolling) return;

		const rectTop = scrollContainer.getBoundingClientRect().top;
		const scrollableHeight = scrollContainer.offsetHeight - window.innerHeight;
		if (scrollableHeight <= 0) return;

		const progress = -rectTop / scrollableHeight;
		const newIndex = Math.min( TOTAL_TABS - 1, Math.max(0, (progress * TOTAL_TABS) | 0)
								 );

		setActiveTab(newIndex);
	}

	function scrollToTab(index) {
		const rect = scrollContainer.getBoundingClientRect();
		const sectionTop = rect.top + window.scrollY;
		const scrollableHeight = scrollContainer.offsetHeight - window.innerHeight;
		const targetScroll = sectionTop + (scrollableHeight / (TOTAL_TABS - 1)) * index;
		isClickScrolling = true;
		window.scrollTo({ top: targetScroll, behavior: 'smooth' });
		setActiveTab(index);
		setTimeout(() => {
			isClickScrolling = false;
		}, 800);
	}

	function init() {
		window.addEventListener('scroll', handleScroll, { passive: true });
		tabButtons.forEach((btn, index) => {
			btn.addEventListener('click', (e) => {
				e.preventDefault();
				if (window.innerWidth <= 768) {
					setActiveTab(index);
				} else {
					scrollToTab(index);
				}
			});
		});
		setActiveTab(0);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();

/* ════════════ METRICS — RADIAL RING ANIMATION ════════════ */

const metricsGrid = document.getElementById('metrics-grid');
if (metricsGrid) {
	let metricsFired = false;
	const ringTargets = [
		{ id: 'ring1', offset: 40 },
		{ id: 'ring2', offset: 2 },
		{ id: 'ring3', offset: 90 },
		{ id: 'ring4', offset: 27 },
	];
	const metricItems = document.querySelectorAll('.metric-item');
	const metricsObserver = new IntersectionObserver((entries) => {
		if (!entries[0].isIntersecting || metricsFired) return;
		metricsFired = true;
		metricItems.forEach((item, i) => {
			setTimeout(() => {
				item.classList.add('counted');
				const ring = document.getElementById(ringTargets[i].id);
				if (ring) {
					ring.style.strokeDashoffset = ringTargets[i].offset;
				}
			}, i * 220);
		});
	}, { threshold: 0.35 });
	metricsObserver.observe(metricsGrid);
}