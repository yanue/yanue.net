define(function (require, exports) {
    exports.closeAd = function (btn) {
        $(btn).live('click', function (e) {
            var area = $(this).attr('data-area');
            $('.ad[data-area="' + area + '"]').hide();
        });
    }
});