function botPhonicFaqs() {
    // FAQ JS  
    jQuery('.faq-item').click(function () {
        const e = jQuery(this);
        if (!e.hasClass('open')) {
            var h = e.find('.faq-text .faq-paragraph').outerHeight();
            e.find('.faq-text').css({ height: h + 'px' })
            e.siblings().find('.faq-text').css({ height: 0 })
            e.addClass('open')
            e.siblings().removeAttr('style').removeClass('open')
            e.find('.faq-icon-minus').animate({ opacity: 1 })
            e.find('.faq-icon-plus').animate({ opacity: 0 })
            e.siblings().find('.faq-icon-minus').animate({ opacity: 0 })
            e.siblings().find('.faq-icon-plus').animate({ opacity: 1 })
        } else {
            e.find('.faq-text').height(0)
            e.removeAttr('style').removeClass('open')
            e.find('.faq-icon-minus').animate({ opacity: 0 })
            e.find('.faq-icon-plus').animate({ opacity: 1 })
        }
    });
}

botPhonicFaqs()

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/botphonic-faq.default', botPhonicFaqs
    );
});