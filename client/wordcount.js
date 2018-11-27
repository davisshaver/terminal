jQuery(document).ready(function($) {
    $('[class*=\'count[\']').each(function() {
        var elClass = $(this).attr('class');
        var maxWords = 0;
        var countControl = elClass.substring((elClass.indexOf('[')) + 1, elClass.lastIndexOf(']')).split(',');
        if (countControl.length > 1) {
            maxWords = countControl[1];
        } else {
            maxWords = countControl[0];
        }
        $(this).find('textarea').bind('keyup click blur focus change paste', function() {
            var numWords = jQuery.trim($(this).val()).replace(/\s+/g,' ').split(' ').length;
            if ($(this).val() === '') {
                numWords = 0;
            }
            $(this).siblings('.word-count-wrapper').children('.word-count').text(`${numWords}/${maxWords}`);
            if (numWords > maxWords && maxWords !== 0) {
                $(this).siblings('.word-count-wrapper').addClass('word-count-error');
            } else {
                $(this).siblings('.word-count-wrapper').removeClass('word-count-error');
            }

        }).after('<span class="word-count-wrapper">Total Word Count: <span class="word-count">0</span><span class="word-count-error-text">Content exceeds limit.</span></span>');
    });
});