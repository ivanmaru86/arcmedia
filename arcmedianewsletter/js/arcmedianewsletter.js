(function ($) {
    $(document).ready(function () {
        if (sessionStorage.getItem('newsletter_block') == 'hidden') {
            $('#block-arcmedianewsletter').hide();
        }
        $('.close-btn').click(function(){
            $('#block-arcmedianewsletter').hide();
            sessionStorage.setItem('newsletter_block', 'hidden');
        });
    });

  })(jQuery);