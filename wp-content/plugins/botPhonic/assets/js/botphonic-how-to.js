(function ($) {
	function initHowToWidget(scope) {
		document.querySelectorAll('.custom-tabs').forEach((tabGroup) => {
			const tabs = tabGroup.querySelectorAll('.custom-tab');
			const contents = tabGroup.querySelectorAll('.custom-content');
			let currentIndex = 0;
			const intervalTime = 10000;
			let startTime = null;
			let progressBarId;
			let isPaused = false;
			let elapsed = 0;

			function activateTab(index) {
				tabs.forEach((tab, i) => {
					tab.classList.toggle('active', i === index);
					tab.querySelector('.progress-bar').style.width = '0%';
					contents[i].classList.toggle('active', i === index);
				});
				currentIndex = index;
				elapsed = 0;
				startTime = null;
			}

			function animateProgressBar(timestamp) {
				if (!startTime) startTime = timestamp;
				if (isPaused) {
					progressBarId = requestAnimationFrame(animateProgressBar);
					return;
				}
				const delta = timestamp - startTime;
				startTime = timestamp;
				elapsed += delta;
				const percent = Math.min((elapsed / intervalTime) * 100, 100);
				const bar = tabs[currentIndex].querySelector('.progress-bar');
				bar.style.width = percent + '%';

				if (elapsed >= intervalTime) {
					activateTab((currentIndex + 1) % tabs.length);
				}

				progressBarId = requestAnimationFrame(animateProgressBar);
			}

			tabs.forEach((tab, index) => {
				tab.addEventListener('click', () => {
					activateTab(index);
				});

				tab.addEventListener('mouseover', () => {
					if (index === currentIndex) {
						isPaused = true;
						startTime = null; // Reset so timestamp delta is accurate when resumed
					}
				});

				tab.addEventListener('mouseleave', () => {
					if (index === currentIndex) {
						isPaused = false;
					}
				});
			});

			activateTab(currentIndex);
			progressBarId = requestAnimationFrame(animateProgressBar);
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.steps-section').forEach(initHowToWidget);
	});

	if (typeof elementorFrontend !== 'undefined') {
		$(window).on('elementor/frontend/init', function () {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/botphonic-how-to.default',
				function ($scope) {
					initHowToWidget($scope);
				}
			);
		});
	}
})(jQuery);