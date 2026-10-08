(function ($) {

    function initSuccessSwiper($scope) {
        var $sliders = $scope.find('.successStorySwiper');
        if (!$sliders.length) return;

        $sliders.each(function () {
            if (this.swiper) return;

            new Swiper(this, {
                speed: 700,
                centeredSlides: true,
                spaceBetween: 54,
                allowTouchMove: true,
                simulateTouch: true,
                touchStartPreventDefault: false,
                touchStartForcePreventDefault: false,
                touchMoveStopPropagation: false,
                noSwiping: true,
                noSwipingSelector: '.case-card_mid',

                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false
                },

                navigation: {
                    nextEl: ".swiper-abs_arrows .case-arrow.next",
                    prevEl: ".swiper-abs_arrows .case-arrow.prev",
                },

                breakpoints: {
                    0: { slidesPerView: 1 },
                    1100: { slidesPerView: 1.25 }
                }
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            initSuccessSwiper($scope);
        });
    });

    $(window).on('load', function () {
        initSuccessSwiper($(document));
    });

})(jQuery);
