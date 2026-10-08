(function ($) {
	'use strict';

	var GROUP_SELECTOR = '[data-botphonic-faq]';
	var supportsNamedDetails = typeof document.createElement === 'function'
		&& 'name' in document.createElement('details');

	function getRoot(scope) {
		if (scope && scope.jquery && scope.length) {
			return scope.get(0);
		}

		if (scope && scope.nodeType) {
			return scope;
		}

		return document;
	}

	function getGroups(root) {
		var groups = [];

		if (root.matches && root.matches(GROUP_SELECTOR)) {
			groups.push(root);
		}

		return groups.concat(Array.prototype.slice.call(root.querySelectorAll(GROUP_SELECTOR)));
	}

	function getGroupItems(group) {
		return Array.prototype.filter.call(
			group.querySelectorAll('details.faq-item'),
			function (item) {
				return item.closest(GROUP_SELECTOR) === group;
			}
		);
	}

	function hasNativeGrouping(items) {
		if (!supportsNamedDetails || !items.length || !items[0].name) {
			return false;
		}

		var groupName = items[0].name;
		return items.every(function (item) {
			return item.name === groupName;
		});
	}

	function normalizeInitialState(items) {
		var opened = null;

		items.forEach(function (item) {
			if (!item.open) {
				return;
			}

			if (opened) {
				item.open = false;
			} else {
				opened = item;
			}
		});

		if (!opened && items.length) {
			items[0].open = true;
		}
	}

	function addGroupingFallback(items) {
		items.forEach(function (item) {
			item.addEventListener('toggle', function () {
				if (!item.open) {
					return;
				}

				items.forEach(function (otherItem) {
					if (otherItem !== item && otherItem.open) {
						otherItem.open = false;
					}
				});
			});
		});
	}

	function initGroup(group) {
		if (group.dataset.botphonicFaqInitialized === 'true') {
			return;
		}

		var items = getGroupItems(group);
		if (!items.length) {
			return;
		}

		group.dataset.botphonicFaqInitialized = 'true';
		normalizeInitialState(items);

		if (!hasNativeGrouping(items)) {
			addGroupingFallback(items);
		}
	}

	function botPhonicFaqs(scope) {
		var root = getRoot(scope);
		getGroups(root).forEach(initGroup);
	}

	window.botPhonicFaqs = botPhonicFaqs;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			botPhonicFaqs(document);
		});
	} else {
		botPhonicFaqs(document);
	}

	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend === 'undefined') {
			return;
		}

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/botphonic-faq.default',
			function ($scope) {
				botPhonicFaqs($scope);
			}
		);
	});

	$(document).on('botphonic:initFaq', function (event, $scope) {
		botPhonicFaqs($scope);
	});
})(jQuery);
