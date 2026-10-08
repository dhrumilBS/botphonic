(function ($) {
	'use strict';

	var SCROLL_OFFSET = 150;
	function BotphonicIntegration(root) {
		var $root = $(root);
		var $buttons = $root.find('.botphonic-call-filters [data-filter]');
		var $select = $root.find('.botphonic-filter-select');
		var $items = $root.find('.botphonic-integration-li');
		var $search = $root.find('.botphonic-search-input');
		var $title = $root.find('.allintegration');
		var $empty = $root.find('.botphonic-integration-empty');

		if (!$items.length) {
			return;
		}

		var defaultLabel = $title.data('default-label') || 'All Integrations';
		var activeFilter = 'all';
		var query = '';
		var debounceTimer = null;
		var index = $items.map(function () {
			var $item = $(this);
			return {
				$el: $item,
				name: ($item.attr('data-name') || '').toLowerCase(),
				category: ($item.attr('data-category') || '').toLowerCase()
			};
		}).get();

		function matches(entry) {
			var filterOk = activeFilter === 'all' ||
				entry.category.indexOf(activeFilter.toLowerCase()) > -1;

			if (!filterOk) {
				return false;
			}

			if (!query) {
				return true;
			}

			return entry.name.indexOf(query) > -1 || entry.category.indexOf(query) > -1;
		}

		function apply() {
			var visible = 0;

			index.forEach(function (entry) {
				var show = matches(entry);
				entry.$el.toggleClass('is-hidden', !show);
				if (show) {
					visible++;
				}
			});

			$empty.prop('hidden', visible !== 0);
		}

		function setFilter(value, shouldScroll) {
			activeFilter = value || 'all';

			$buttons
				.removeClass('active')
				.attr('aria-pressed', 'false')
				.filter('[data-filter="' + activeFilter.replace(/"/g, '\\"') + '"]')
				.addClass('active')
				.attr('aria-pressed', 'true');

			if ($select.length && $select.val() !== activeFilter) {
				$select.val(activeFilter);
			}

			if ($title.length) {
				$title.text(activeFilter === 'all' ? defaultLabel : activeFilter);
			}

			apply();

			if (shouldScroll && $title.length && $title.is(':visible')) {
				$('html, body').animate({
					scrollTop: $title.offset().top - SCROLL_OFFSET
				}, 500);
			}
		}

		$buttons.on('click', function (e) {
			e.preventDefault();
			setFilter($(this).attr('data-filter'), true);
		});

		$select.on('change', function () {
			setFilter($(this).val(), true);
		});

		$search.on('input', function () {
			var value = $(this).val();

			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function () {
				query = $.trim(value).toLowerCase();
				apply();
			}, 120);
		});

		apply();
	}

	function init(scope) {
		var $scope = scope ? $(scope) : $(document);

		$scope.find('.botphonic-integration-wrap').addBack('.botphonic-integration-wrap').each(function () {
			if ($(this).data('botphonicIntegrationReady')) {
				return;
			}
			$(this).data('botphonicIntegrationReady', true);
			BotphonicIntegration(this);
		});
	}

	$(window).on('elementor/frontend/init', function () {
		if (window.elementorFrontend && elementorFrontend.hooks) {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/botphonic-integration.default',
				function ($scope) { init($scope); }
			);
		}
	});

	$(function () {
		init(document);
	});
})(jQuery);