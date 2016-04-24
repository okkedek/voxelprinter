(function($) {
    "use strict";
    $(document).on('click','a',function(){
        $('html, body').animate({
            scrollTop: $( $(this).attr('href') ).offset().top - 50
        }, 500);
        return false;
    });
})(jQuery);
