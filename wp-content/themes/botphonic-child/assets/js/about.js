(function () {
	'use strict';

	var root = document.getElementById('bp-about');
	if (!root) {
		return;
	}

	root.classList.remove('no-js');
	root.classList.add('is-ready');

	var audienceButtons = Array.prototype.slice.call(root.querySelectorAll('[data-bau-audience]'));
	var audiencePanels = Array.prototype.slice.call(root.querySelectorAll('[data-bau-audience-panel]'));

	function selectAudience(id) {
		audienceButtons.forEach(function (button) {
			var active = button.getAttribute('data-bau-audience') === id;
			button.classList.toggle('is-active', active);
			button.setAttribute('aria-pressed', active ? 'true' : 'false');
		});

		audiencePanels.forEach(function (panel) {
			var active = panel.getAttribute('data-bau-audience-panel') === id;
			panel.classList.toggle('is-active', active);
			panel.hidden = !active;
		});
	}

	audienceButtons.forEach(function (button) {
		button.addEventListener('click', function () {
			selectAudience(button.getAttribute('data-bau-audience'));
		});
	});

	var initialAudience = root.querySelector('[data-bau-audience][aria-pressed="true"]');
	if (initialAudience) {
		selectAudience(initialAudience.getAttribute('data-bau-audience'));
	}

	var minuteButtons = Array.prototype.slice.call(root.querySelectorAll('[data-bau-minutes]'));
	var minuteLabel = root.querySelector('[data-bau-minutes-label]');
	var botCost = root.querySelector('[data-bau-bot-cost]');
	var humanCost = root.querySelector('[data-bau-human-cost]');
	var saving = root.querySelector('[data-bau-saving]');
	var formatter = new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 });
	var RATE_PER_MINUTE = 0.12; // pay-as-you-go; human services are $1–$2 per minute

	function money(value) {
		return '$' + formatter.format(Math.round(value));
	}

	function updateEstimate(minutes) {
		minuteButtons.forEach(function (button) {
			var active = Number(button.getAttribute('data-bau-minutes')) === minutes;
			button.classList.toggle('is-active', active);
			button.setAttribute('aria-pressed', active ? 'true' : 'false');
		});

		if (minuteLabel) {
			minuteLabel.textContent = formatter.format(minutes);
		}
		if (botCost) {
			botCost.textContent = money(minutes * RATE_PER_MINUTE);
		}
		if (humanCost) {
			humanCost.textContent = money(minutes) + '–' + money(minutes * 2);
		}
		if (saving) {
			saving.textContent = money(minutes * (1 - RATE_PER_MINUTE)) + '–' + money(minutes * (2 - RATE_PER_MINUTE));
		}
	}

	minuteButtons.forEach(function (button) {
		button.addEventListener('click', function () {
			updateEstimate(Number(button.getAttribute('data-bau-minutes')));
		});
	});
})();