(function($) {
    "use strict";
    $(document).on('click','a',function(){
        if ($(this).attr('href')) {
            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top
            }, 500);
        }
        return false;
    });
})(jQuery);
