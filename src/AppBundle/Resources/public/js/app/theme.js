(function ($) {
    "use strict";
    $(document).on('click', 'a.page-scroll', function () {
        // smooth scrolling to in page anchors
        if ($(this).attr('href')) {
            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top
            }, 500);
        }
        return false;
    });
})(jQuery);
