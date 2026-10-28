(function () {
	'use strict';

	var root = document.getElementById('bp-industries');
	if (!root) {
		return;
	}

	root.classList.remove('no-js');

	// Industry filter chips: show one group, or all.
	var buttons = Array.prototype.slice.call(root.querySelectorAll('[data-bpi-filter]'));
	var items = Array.prototype.slice.call(root.querySelectorAll('[data-bpi-group]'));
	var count = root.querySelector('[data-bpi-count]');

	function applyFilter(group) {
		var shown = 0;

		buttons.forEach(function (button) {
			button.setAttribute('aria-pressed', button.getAttribute('data-bpi-filter') === group ? 'true' : 'false');
		});

		items.forEach(function (item) {
			var match = group === 'all' || item.getAttribute('data-bpi-group') === group;
			item.hidden = !match;
			if (match) {
				shown++;
			}
		});

		if (count) {
			count.textContent = shown + (shown === 1 ? ' industry' : ' industries');
		}
	}

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			applyFilter(button.getAttribute('data-bpi-filter'));
		});
	});
})();
