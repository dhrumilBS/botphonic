/**
 * Hub pages (Products, Solutions, Industries): filter chips for the card grid.
 * Without JavaScript every card stays visible and the chips stay hidden.
 */
(function () {
	'use strict';

	var root = document.querySelector('[data-bphub]');
	if (!root) {
		return;
	}

	root.classList.remove('no-js');

	var buttons = Array.prototype.slice.call(root.querySelectorAll('[data-bphub-filter]'));
	var items = Array.prototype.slice.call(root.querySelectorAll('[data-bphub-group]'));
	var count = root.querySelector('[data-bphub-count]');
	var one = count ? count.getAttribute('data-one') : '';
	var many = count ? count.getAttribute('data-many') : '';

	function applyFilter(group) {
		var shown = 0;

		buttons.forEach(function (button) {
			button.setAttribute('aria-pressed', button.getAttribute('data-bphub-filter') === group ? 'true' : 'false');
		});

		items.forEach(function (item) {
			var match = group === 'all' || item.getAttribute('data-bphub-group') === group;
			item.hidden = !match;
			if (match) {
				shown++;
			}
		});

		if (count) {
			count.textContent = shown + ' ' + (shown === 1 ? one : many);
		}
	}

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			applyFilter(button.getAttribute('data-bphub-filter'));
		});
	});
})();
