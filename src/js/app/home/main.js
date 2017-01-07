// admin user,group,menus,permission settings
define(function (require, exports) {

    exports.tabSwitch = function () {
        $('.tab-switch .tab').hover(function () {
            var tab = $(this).attr('data-tab');
            $(".tab-switch .tab").removeClass('current');
            $(this).addClass('current');
            $('.tab-content').hide();
            $('.tab-content[data-tab="' + tab + '"]').show();
        });
    }

    exports.topNav = function () {
        var timer = null;
        $('.top-alt').live({
            'mouseenter': function () {
                var sub = $(this).attr('data-sub');
                $('.sub-nav[data-sub="' + sub + '"]').fadeIn();
            },
            'mouseleave': function () {
                var sub = $(this).attr('data-sub');
                timer = setTimeout(function () {
                    $('.sub-nav[data-sub="' + sub + '"]').fadeOut();
                }, 1000);
            }
        });

        $('.sub-nav').live({
            'mouseenter': function () {
                window.clearInterval(timer);
            },
            'mouseleave': function () {
                $(this).fadeOut();
            }
        });
    }

    exports.recommend = function () {
        require('jquery/jquery.bxslider.min');
        require('jquery/jquery.easing.1.3');
        var slider = $('.recommend .bxslider').bxSlider({
            mode: 'horizontal',
            useCSS: false,
            infiniteLoop: true,
            hideControlOnEnd: true,
            tickerHover: true,
            easing: 'easeInOutBack',
            speed: 1000,
            pager: false,
            controls: false,
            auto: true
        });
        $('.recommend .go-next').live('click', function () {
            slider.goToNextSlide();
        })
    }

    exports.prettfiy = function () {
        require('tools/prettfiy/prettify');
        $(function () {
            prettyPrint();
        });
    }

    exports.showSubCat = function () {
        $('.menu .main-cat .showSub').live({
            'mouseenter': function (e) {
                var rel = $(this).attr('data-rel');

                $('.menu .main-cat .showSub .arrow').hide();

                $('.sub-cat-show').removeClass('current');
                $(this).addClass('current').find('.arrow').show();

                $('.menu .sub-cat .cat-group').hide();
                $('.menu .sub-cat .cat-group[data-rel="' + rel + '"]').fadeIn();

                e.stopImmediatePropagation();
            }
        })
    }
});