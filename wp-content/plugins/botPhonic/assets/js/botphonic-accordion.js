document.addEventListener("DOMContentLoaded", function () {
    function initBotphonicAccordion(scope) {
        const accordions = scope.querySelectorAll(".botphonic-accordion");
        accordions.forEach(function (accordion) {
            const items = accordion.querySelectorAll(".bp-item");
            items.forEach(function (item) {
                const question = item.querySelector(".bp-question");
                const answer = item.querySelector(".bp-answer");
                answer.style.display = "none";
                question.addEventListener("click", function () {
                    const isActive = item.classList.contains("active");
                    items.forEach(function (el) {
                        el.classList.remove("active");
                        el.querySelector(".bp-answer").style.display = "none";
                    });
                    if (!isActive) {
                        item.classList.add("active");
                        answer.style.display = "block";
                    }
                });
            });
        });
    }
    initBotphonicAccordion(document);
	if (typeof elementorFrontend !== 'undefined') {
		jQuery(window).on('elementor/frontend/init', function () {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/botphonic_accordion.default',
				function ($scope) {
					initBotphonicAccordion($scope);
				}
			);
		});
	}
});