<?php
function usecases_shortcode()
{
	wp_enqueue_style('botphonic-Swiper');
	wp_enqueue_script('botphonic-Swiper');

	wp_enqueue_style('botphonic-usecase-css');
	ob_start();
?>
	<?php
	$botphonic_usecases = [
		[
			'file' => 'Sales-Assistant-Lead-Qualifier.wav',
			'title' => 'Lead Qualification Assistant',
			'subtitle' => 'Resolve inquiries quickly, speedy answers, and 24 hours assistance service.',
			'link' => site_url() . '/ai-answering-service/',
			'gradient_class' => 'purple',
		],
		[
			'file' => 'Customer-Support.wav',
			'title' => 'Customer Support AI',
			'subtitle' => 'Manage high volume of calls, minimizes client wait time, and manage leads.',
			'link' => site_url() . '/ai-customer-service/',
			'gradient_class' => 'green',
		],
		[
			'file' => 'Healthcare-Receptionist.wav',
			'title' => 'Healthcare Receptionist',
			'subtitle' => 'Schedule meetings, analyze reports, send appointment reminders, and more.',
			'link' => site_url() . '/ai-receptionist/',
			'gradient_class' => 'blue',
		],
	];

	// $usecases = [
	// 	[
	// 		'title' => 'Customer Support',
	// 		'tag' => 'Customer Support',
	// 		'description' => 'Resolve common issues, answer questions, and provide 24/7 assistance. Free up agents to focus on more complex cases.',
	// 		'cta' => 'Meet your Answering Assistant →',
	// 		'cta_link' => '#',
	// 		'gradient_class' => 'purple',
	// 	],
	// 	[
	// 		'title' => 'Call Centers',
	// 		'tag' => 'Call Centers',
	// 		'description' => 'Manage peak volume, reduce wait times, and improve first-call resolution. Scale support without growing your team.',
	// 		'cta' => 'Try our Recruitment AI →',
	// 		'cta_link' => '#',
	// 		'gradient_class' => 'green',
	// 	],
	// 	[
	// 		'title' => 'Appointment Scheduling',
	// 		'tag' => 'Appointment Scheduling',
	// 		'description' => 'Book, reschedule, and manage calendars automatically. Send reminders to reduce no-shows and free up your staff.',
	// 		'cta' => 'Launch Healthcare AI →',
	// 		'cta_link' => '#',
	// 		'gradient_class' => 'blue',
	// 	],
	// ];

	?>
	<section class="usecase-section botphonic-usecase section-padded">
		<div class="container">
			<div class="heading text-center">
				<span class="pre-title d-inline-block"> Use cases </span>
				<h2 class="title">Check Out Our Botphonic Use Cases </h2>
			</div>


			<?php if (!empty($botphonic_usecases)) { ?>
				<div class="usecases-wrapper swiper ">
					<div class="swiper-wrapper">
						<?php foreach ($botphonic_usecases as $item) { ?>
							<div class="swiper-slide">
								<div class="usecase-card">
									<div data-element="audio-btn" class="usecase-header usecase-btn <?= $item['gradient_class']; ?>" data-audio="<?= site_url(); ?>/wp-content/plugins/botPhonic/assets/wav/<?= $item['file']; ?>">
										<div class="usecase-btn d-flex align-items-center">
											<div class="is-soundwaves">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" width="22" height="22" class="icon-soundwave">
													<rect x="0.41" y="7.08" width="2.35" height="7.84" rx="1.17" fill="white" data-id="line-1"></rect>
													<rect x="5.12" y="3.16" width="2.35" height="15.69" rx="1.17" fill="white" data-id="line-2"></rect>
													<rect x="9.82" y="0.02" width="2.35" height="21.96" rx="1.17" fill="white" data-id="line-3"></rect>
													<rect x="14.53" y="3.16" width="2.35" height="15.69" rx="1.17" fill="white" data-id="line-4"></rect>
													<rect x="19.24" y="7.08" width="2.35" height="7.84" rx="1.17" fill="white" data-id="line-5"></rect>
												</svg>
											</div>
											<div class="h6 m-0 fw-bold ms-2"><?= $item['title']; ?></div>
										</div>
										<div class="usecase-try-btn d-flex align-items-center  ms-auto ">
											<div class="usecase-control-icon position-relative d-flex me-1">
												<svg data-icon="play" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" width="12" height="12" fill="none" class="">
													<path d="M11.25 5.99999C11.2503 6.12731 11.2177 6.25255 11.1552 6.36352C11.0928 6.4745 11.0027 6.56742 10.8937 6.63327L4.14 10.7648C4.02613 10.8346 3.89572 10.8726 3.76222 10.8751C3.62873 10.8776 3.49699 10.8444 3.38063 10.7789C3.26536 10.7144 3.16935 10.6205 3.10245 10.5066C3.03556 10.3928 3.00019 10.2631 3 10.1311V1.8689C3.00019 1.73684 3.03556 1.60722 3.10245 1.49337C3.16935 1.37951 3.26536 1.28553 3.38063 1.22108C3.49699 1.15562 3.62873 1.12241 3.76222 1.12488C3.89572 1.12736 4.02613 1.16542 4.14 1.23515L10.8937 5.36671C11.0027 5.43255 11.0928 5.52548 11.1552 5.63645C11.2177 5.74743 11.2503 5.87266 11.25 5.99999Z" fill="currentColor"></path>
												</svg>
												<svg data-icon="pause" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" width="12" height="12" fill="none" class=" d-none">
													<path d="M2.30038 3.6C2.30038 2.8456 2.30038 2.4688 2.53478 2.2344C2.76918 2 3.14598 2 3.90038 2C4.65478 2 5.03158 2 5.26598 2.2344C5.50038 2.4688 5.50038 2.8456 5.50038 3.6V8.4C5.50038 9.1544 5.50038 9.5312 5.26598 9.7656C5.03158 10 4.65478 10 3.90038 10C3.14598 10 2.76918 10 2.53478 9.7656C2.30038 9.5312 2.30038 9.1544 2.30038 8.4V3.6ZM7.10038 3.6C7.10038 2.8456 7.10038 2.4688 7.33478 2.2344C7.56918 2 7.94598 2 8.70038 2C9.45478 2 9.83158 2 10.066 2.2344C10.3004 2.4688 10.3004 2.8456 10.3004 3.6V8.4C10.3004 9.1544 10.3004 9.5312 10.066 9.7656C9.83158 10 9.45478 10 8.70038 10C7.94598 10 7.56918 10 7.33478 9.7656C7.10038 9.5312 7.10038 9.1544 7.10038 8.4V3.6Z" fill="currentColor"></path>
												</svg>
											</div>
											<div>Try it</div>
										</div>
									</div>
									<div class="usecase-body">
										<div class="body-title h4"><?= $item['title']; ?></div>
										<p class="body-p"><?= $item['subtitle']; ?></p>
										<a href="#" class="d-inline-block"> Try our Sales AI → </a>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
	<script>
		document.addEventListener('DOMContentLoaded', function() {


			let currentAudio = new Audio();
			let currentBtn = null;

			document.querySelectorAll('[data-element="audio-btn"]').forEach(btn => {
				btn.addEventListener('click', function() {
					const audioSrc = btn.getAttribute('data-audio');
					const soundwave = btn.querySelector('.is-soundwaves');
					const playIcon = btn.querySelector('[data-icon="play"]');
					const pauseIcon = btn.querySelector('[data-icon="pause"]');

					if (currentBtn && currentBtn !== btn) {
						btn.classList.remove('is-active');
						currentBtn.classList.remove('is-active');
						currentBtn.querySelector('.is-soundwaves').classList.remove('is-active');
						currentBtn.querySelector('[data-icon="play"]').classList.remove('d-none');
						currentBtn.querySelector('[data-icon="pause"]').classList.add('d-none');
						currentAudio.pause();
					}

					if (currentAudio.src !== audioSrc) {
						currentAudio.src = audioSrc;
					}

					if (currentAudio.paused) {
						currentAudio.play();
						btn.classList.add('is-active');
						soundwave.classList.add('is-active');
						playIcon.classList.add('d-none');
						pauseIcon.classList.remove('d-none');
						currentBtn = btn;
					} else {
						currentAudio.pause();
						btn.classList.remove('is-active');
						soundwave.classList.remove('is-active');
						playIcon.classList.remove('d-none');
						pauseIcon.classList.add('d-none');
					}

					currentAudio.onended = () => {
						btn.classList.remove('is-active');
						soundwave.classList.remove('is-active');
						playIcon.classList.remove('d-none');
						pauseIcon.classList.add('d-none');
					};
				});
			});






			var swiper = new Swiper('.usecases-wrapper', {
				slidesPerView: 1,
				spaceBetween: 30,
				navigation: {
					nextEl: '.next_caro',
					prevEl: '.previous_caro',
				},
				breakpoints: {
					768: {
						slidesPerView: 2
					},
					1200: {
						slidesPerView: 3,
						spaceBetween: 30,
					},
				},
				on: {
					init: updatePaging,
					slideChange: updatePaging
				}
			});

			function updatePaging(swiper) {
				document.querySelector('.pagingInfo').innerHTML =
					'<span class="current-slide">' + (swiper.realIndex + 1) + '</span><span class="total-slide"> /' + swiper.slides.length + '</span>';
			}
		});
	</script>

<?php
	return ob_get_clean();
}
add_shortcode('botphonic_usecases', 'usecases_shortcode');
