<?php
/*
 * Template name: Contact
 *
 */
?>
<?php get_header(); ?>
<?php 
wp_enqueue_style('intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/css/intlTelInput.css');
wp_enqueue_script('intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/intlTelInput.min.js', array('jquery'), null, true);
wp_enqueue_script('intl-tel-utils', 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/utils.js', array(), null, true);
?>
<script type="text/javascript" id="intl-tel-js">
	document.addEventListener('DOMContentLoaded', function () {
		var input = document.querySelector('#intl-phone-input');
		var hiddenInput = document.querySelector('#full-phone');

		if (!input || !hiddenInput) return;

		var iti = window.intlTelInput(input, {
			formatOnDisplay: true,
			separateDialCode: true,
			strictMode: true,
			nationalMode: false,
			autoPlaceholder: 'aggressive',
			initialCountry: 'auto',
			geoIpLookup: function(callback) {
				fetch('https://ipapi.co/json/')
					.then(res => res.json())
					.then(data => callback(data.country_code))
					.catch(() => callback('IN'));
			},
			utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/utils.js'
		});
		function updateHiddenPhone() {
			const parentWrap = input.closest('.wpcf7-form-control-wrap');

			if (!iti.isValidNumber()) {
				hiddenInput.value = '';
				input.classList.add('wpcf7-not-valid');
				if (!parentWrap.querySelector('.wpcf7-not-valid-tip')) {
					const error = document.createElement('span');
					error.className = 'wpcf7-not-valid-tip';
					error.textContent = 'Invalid phone number';
					parentWrap.appendChild(error);
				}
			} else {
				hiddenInput.value = iti.getNumber();
				input.classList.remove('wpcf7-not-valid');
				const tip = parentWrap.querySelector('.wpcf7-not-valid-tip');
				if (tip) tip.remove();
			}
		}
		input.addEventListener('input', updateHiddenPhone);
		document.addEventListener('wpcf7submit', function () {
			updateHiddenPhone();
		}, true);
	}); 
</script>

<style>
	/* 	Contact Hero */
	/* 	.contact-page-hero{ background: linear-gradient(to right bottom, hsl(225 47% 11% / 1), hsl(260.35deg 65.64% 31.96%), hsl(225 47% 11% / 1));	} */

	.contact-page-hero .elementor-icon-list-items{ display: flex; flex-wrap: wrap; --gap: 12px; gap: var(--gap); margin: 12px 0; }
	.contact-page-hero .elementor-icon-list-items .elementor-icon-list-item{ background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 10px !important; border:1px solid rgba(255, 255, 255, 0.1); max-width: calc(50% - (var(--gap) / 2)); width: 100%; font-size: 18px; }

	@media screen and (max-width: 767px){
		.contact-page-hero .elementor-icon-list-items .elementor-icon-list-item{ max-width: 100%;  font-size: 16px; }
	}

	.contact-page-hero .contact-page-hero-form{}
	.contact-page-hero .contact-page-hero-form .contact-v2{}
	.contact-page-hero .contact-page-hero-form .contact-v2 .h3{ color: #fff; }
	.contact-page-hero .contact-page-hero-form .contact-v2 .w-form-field{color: #fff; }
	.contact-page-hero .contact-page-hero-form .contact-v2 textarea::placeholder,
	.contact-page-hero .contact-page-hero-form .contact-v2 select::placeholder,
	.contact-page-hero .contact-page-hero-form .contact-v2 input::placeholder{ color: #fff5; }

	.contact-page-hero .contact-page-hero-form .contact-v2 .w-form-field{ background-color: #ffffff10; border-color: #ffffff33 }
	.contact-page-hero .contact-page-hero-form .contact-v2 .w-form-field option{color: #333;}

	.contact-page-hero .contact-page-hero-form .contact-v2 .form-submit-btn .theme-btn{
		border:0; 
		/* background-image : linear-gradient(to right, rgb(59, 130, 246), rgb(147, 51, 234));  */
	}
</style>

<?php the_content (); ?>
<?php get_footer(); ?>