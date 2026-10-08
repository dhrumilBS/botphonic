(function ($) {
	function initScrollTabs($scope) {
		const container = $scope.find('.bf-scroll-container')[0];
		const tabs = $scope.find('.bf-tab-button');
		const panels = $scope.find('.bf-content-panel');

		if (!container || !tabs.length) return;

		let totalTabs = tabs.length;
		let activeTab = 0;
		let isClickScrolling = false;
		let ticking = false;

		if (window.innerWidth > 768) {
			container.style.height = (totalTabs * 100) + 'vh';
		}

		function easeInOutCubic(t) {
			return t < 0.5
				? 4 * t * t * t
				: 1 - Math.pow(-2 * t + 2, 3) / 2;
		}

		function setActiveTab(index) {
			if (index === activeTab || index < 0 || index >= totalTabs) return;

			tabs.removeClass('bf-active');
			panels.removeClass('bf-active');

			$(tabs[index]).addClass('bf-active');
			$(panels[index]).addClass('bf-active');

			activeTab = index;
		}

		function handleScroll() {

			if (window.innerWidth <= 768 || isClickScrolling) return;
			const rect = container.getBoundingClientRect();

			if (rect.bottom < 0 || rect.top > window.innerHeight) return;
			if (!ticking) {

				requestAnimationFrame(() => {

					const scrollableHeight = container.offsetHeight - window.innerHeight;

					if (scrollableHeight <= 0) return;

					let progress = -rect.top / scrollableHeight;
					progress = Math.max(0, Math.min(1, progress));
					const index = Math.floor(progress * (totalTabs - 1));
					setActiveTab(index);
					ticking = false;
				});

				ticking = true;
			}
		}

		function smoothScrollTo(targetY, duration = 900) {

			const startY = window.scrollY;
			const distance = targetY - startY;
			let startTime = null;

			function animation(currentTime) {

				if (!startTime) startTime = currentTime;

				const elapsed = currentTime - startTime;
				const progress = Math.min(elapsed / duration, 1);
				const eased = easeInOutCubic(progress);

				window.scrollTo(0, startY + distance * eased);

				if (elapsed < duration) {
					requestAnimationFrame(animation);
				} else {
					isClickScrolling = false;
				}
			}

			requestAnimationFrame(animation);
		}

		function scrollToTab(index) {

			if (totalTabs <= 1) return;

			const rect = container.getBoundingClientRect();
			const sectionTop = rect.top + window.scrollY;
			const scrollableHeight = container.offsetHeight - window.innerHeight;
			const offset = 80; // header offset (adjust if needed)
			const target = sectionTop + ((scrollableHeight / (totalTabs - 1)) * index) - offset;

			isClickScrolling = true;
			setActiveTab(index);
			smoothScrollTo(target, 900);
		}

		tabs.each(function (i) {
			$(this).on('click', function (e) {
				e.preventDefault();

				if (window.innerWidth <= 768) {
					setActiveTab(i);
				} else {
					scrollToTab(i);
				}
			});
		});

		$(window).off('scroll.bfScrollTabs');
		$(window).on('scroll.bfScrollTabs', handleScroll);

		$(window).on('resize.bfScrollTabs', function () {
			if (window.innerWidth > 768) {
				container.style.height = (totalTabs * 100) + 'vh';
			} else {
				container.style.height = 'auto';
			}
		});

		setActiveTab(0);
	}

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/bf_scroll_tabs.default',
			initScrollTabs
		);
	});

})(jQuery);