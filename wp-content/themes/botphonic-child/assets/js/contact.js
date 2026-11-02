(function () {
	'use strict';

	var root = document.getElementById('bp-contact');
	if (!root) {
		return;
	}

	root.classList.remove('no-js');
	root.classList.add('is-ready');

	var form = root.querySelector('.bct-form-card .wpcf7-form');

	// The CF7 form uses placeholders instead of labels. Give every control an
	// accessible name and an autocomplete hint without changing the form itself.
	if (form) {
		var autocomplete = {
			'your-name': 'name',
			'your-email': 'email',
			'company-website': 'url',
			'your-number': 'tel'
		};

		Array.prototype.forEach.call(form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]), select, textarea'), function (field) {
			if (!field.getAttribute('aria-label')) {
				var name = field.getAttribute('placeholder');
				if (!name && field.tagName === 'SELECT' && field.options.length) {
					name = field.options[0].text;
				}
				if (name) {
					field.setAttribute('aria-label', name);
				}
			}
			if (autocomplete[field.name] && !field.getAttribute('autocomplete')) {
				field.setAttribute('autocomplete', autocomplete[field.name]);
			}
		});

		// Grey the select text while it still shows its label option.
		Array.prototype.forEach.call(form.querySelectorAll('select'), function (select) {
			var sync = function () {
				select.classList.toggle('is-placeholder', select.selectedIndex <= 0 && select.value === '');
			};
			select.addEventListener('change', sync);
			sync();
		});
	}

	// International phone input: stores the full E.164 number in the hidden full_phone field.
	var phone = form && form.querySelector('[name="your-number"]');
	var fullPhone = form && form.querySelector('[name="full_phone"]');

	if (phone && fullPhone && typeof window.intlTelInput === 'function') {
		var iti = window.intlTelInput(phone, {
			formatOnDisplay: true,
			separateDialCode: true,
			strictMode: true,
			nationalMode: false,
			autoPlaceholder: 'aggressive',
			initialCountry: 'auto',
			geoIpLookup: function (callback) {
				fetch('https://ipapi.co/json/')
					.then(function (res) { return res.json(); })
					.then(function (data) { callback(data.country_code); })
					.catch(function () { callback('IN'); });
			},
			utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/utils.js'
		});

		var updatePhone = function () {
			var wrap = phone.closest('.wpcf7-form-control-wrap');
			var tip = wrap && wrap.querySelector('.bct-phone-tip');

			if (!phone.value.trim()) {
				fullPhone.value = '';
				return;
			}

			if (!iti.isValidNumber()) {
				fullPhone.value = '';
				phone.classList.add('wpcf7-not-valid');
				phone.setAttribute('aria-invalid', 'true');
				if (wrap && !tip) {
					tip = document.createElement('span');
					tip.className = 'wpcf7-not-valid-tip bct-phone-tip';
					tip.setAttribute('aria-live', 'polite');
					tip.textContent = 'Please enter a valid phone number for the selected country.';
					wrap.appendChild(tip);
				}
			} else {
				fullPhone.value = iti.getNumber();
				phone.classList.remove('wpcf7-not-valid');
				phone.removeAttribute('aria-invalid');
				if (tip) {
					tip.remove();
				}
			}
		};

		phone.addEventListener('blur', updatePhone);
		phone.addEventListener('countrychange', updatePhone);
		phone.addEventListener('input', function () {
			// Only re-check while an error is showing, so typing isn't interrupted.
			if (phone.classList.contains('wpcf7-not-valid')) {
				updatePhone();
			}
		});
		document.addEventListener('wpcf7submit', updatePhone, true);
	}

	// Reveal blocks as they scroll into view, staggered within each group.
	var reveals = Array.prototype.slice.call(root.querySelectorAll('[data-bct-reveal]'));
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (!('IntersectionObserver' in window) || reduceMotion) {
		reveals.forEach(function (el) {
			el.classList.add('is-visible');
		});
		return;
	}

	reveals.forEach(function (el) {
		var siblings = Array.prototype.filter.call(el.parentNode.children, function (child) {
			return child.hasAttribute('data-bct-reveal');
		});
		el.style.setProperty('--bct-delay', Math.min(siblings.indexOf(el), 5) * 70 + 'ms');
	});

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				entry.target.classList.add('is-visible');
				observer.unobserve(entry.target);
			}
		});
	}, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });

	reveals.forEach(function (el) {
		observer.observe(el);
	});
})();
