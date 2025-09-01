document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.slide-track').forEach(track => {
		track.addEventListener('mouseenter', () => {
			track.style.animationPlayState = 'paused';
		});
		track.addEventListener('mouseleave', () => {
			track.style.animationPlayState = 'running';
		});
	});


	const swiper = new Swiper('.agent-slider', {
		slidesPerView: 1,
		spaceBetween: 20,
		loop: true,
		autoplay: {
			delay: 3000,
			disableOnInteraction: false
		},
		pagination: {
			el: '.swipers-pagination',
			clickable: true
		},
		breakpoints: {
			576: {
				slidesPerView: 2
			},
			1200: {
				slidesPerView: 3
			}
		}
	});
})