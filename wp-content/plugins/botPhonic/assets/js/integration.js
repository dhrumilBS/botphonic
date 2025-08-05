(function (jQuery) {
    var jQueryfilters = jQuery('.botphonic-call-filters [data-filter]'),
        jQueryboxes = jQuery('.all-integration li');
    jQueryfilters.on('click', function (e) {
        e.preventDefault();



        var jQuerythis = jQuery(this);
        jQueryfilters.removeClass('active');
        jQuerythis.addClass('active');
        var jQueryfilterColor = jQuerythis.attr('data-filter');
        if (jQueryfilterColor == 'all') {
            jQueryboxes.fadeOut().promise().done(function () {
                    jQueryboxes.fadeIn();
                });
            jQuery('.allintegration').html("All Integrations")
            jQuery('html, body').animate({
                scrollTop: jQuery(".allintegration").offset().top - 150
            }, 500);
            //$('.botphonic-call_featured').scrollTop(3000);
        } else {
            jQueryboxes.fadeOut().promise().done(function () {
                jQueryboxes.each(function (elm, value) {
                    if (jQuery(value).attr("data-category").indexOf(jQueryfilterColor) > -1) {
                        jQuery(value).fadeIn();
                    }
                })
            });
            jQuery('.allintegration').html(jQueryfilterColor)
            jQuery('html, body').animate({
                scrollTop: jQuery(".allintegration").offset().top - 150
            }, 500);
        }
    });
    // search js
    jQuery(".botphonic-search .form-control").keyup(function () {
        var filter = jQuery(this).val().toLowerCase();
        jQuery(".botphonic-integration-li").each(function () {
            var current = jQuery(this).attr('data-name');
            var category = jQuery(this).attr('data-category');
            if (filter != "") {
                if (current.indexOf(filter) > -1 || category.indexOf(filter) > -1) {
                    jQuery(this).fadeIn();
                } else {
                    jQuery(this).fadeOut();
                }
            } else {
                jQuery(".botphonic-integration-li").show();
            }
        }).promise().done(function () {
            if (jQuery('.botphonic-integration-li:visible').length === 0) {
                jQuery("#botphonic-integration_nokeyword").show();
            } else {
                jQuery("#botphonic-integration_nokeyword").hide();
            }
        });
    });
})(jQuery);
