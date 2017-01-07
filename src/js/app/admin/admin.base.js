define(function (require, exports) {

    var site_url = app.site_url;

    exports.requestApi = function (uri, data, btn, async, func) {
        var async = async ? true : false;
        var data = $.extend({}, {'sign': app.sign}, data);
        var paras = $.extend({}, ajaxParas(btn), {
            url: site_url + uri,
            async: async,
            data: data,
            success: function (res) {
                if (typeof func == 'function') {
                    func(res);
                }
                if (res.error.code != 0) {
                    showErrorCode(res.error);
                }
            }
        });
        // console.log(paras);
        $.ajax(paras);
    };

    // 左边导航展开
    exports.expandMenu = function () {
        $(".menuTitle").live('click', function (e) {
            if ($(this).parent().find('.menuList').is(':visible')) {
                $("#menu .menuTitle").removeClass('current');
                $(this).parent().find('.menuList').hide();
            } else {
                $("#menu .menuTitle").removeClass('current');
                $(this).addClass('current');
                $(this).parent().find('.menuList').fadeIn();
            }
            e.stopImmediatePropagation();
        });
    };

    // status : err, ok, wait, warming
    exports.showTip = function (status, tip, time) {
        $('#ajaxStatus').fadeIn();
        $('#ajaxStatus #ajaxTip').html(tip).addClass(status);
        if (time > 0) {
            setTimeout(function () {
                exports.hideTip();
            }, time);
        }
    }

    exports.hideTip = function () {
        $('#ajaxStatus').fadeOut();
        $('#ajaxStatus #ajaxTip').removeClass();
    }

    // all select status
    exports.selectCheckbox = function () {
        // tr click
        $('.list .listData .row .chk').live('click', function (e) {
            var chk_id = $(this).attr('data-id');

            if ($(this).attr('checked') == true || $(this).attr('checked') == 'checked') {
                $(this).parents('.row[data-id=' + chk_id + ']').addClass('selected');
            } else {
                $(this).parents('.row[data-id=' + chk_id + ']').removeClass('selected');
            }
            e.stopImmediatePropagation();
        });

        // checkbox click
        $('.list .listData .row').live('click', function (e) {
            var chk_id = $(this).find('.chk').attr('data-id');
            if ($(this).find('.chk').attr('checked') == true || $(this).find('.chk').attr('checked') == 'checked') {
                $(this).removeClass('selected');
                $(this).find('.chk').attr('checked', false);
            } else {
                $(this).addClass('selected');
                $(this).find('.chk').attr('checked', true);
            }
            e.stopImmediatePropagation();
        });

        // 全选
        $(".selectAll").live('click', function () {
            $(this).addClass('current');
            $(this).siblings('.btn-light').removeClass('current');
            $(".list .listData .row input.chk").attr("checked", true);
            $('.list .listData .row').addClass('selected');
        });
        // 全不选
        $(".selectNone").live('click', function () {
            $(this).siblings('.btn-light').removeClass('current');
            $(".list .listData .row input.chk").attr("checked", false);
            $('.list .listData .row').removeClass('selected');
        });
        // 反选
        $(".selectInvert").live('click', function () {
            $(this).addClass('current');
            $(this).siblings('.btn-light').removeClass('current');
            $(".list .listData .row input.chk").each(function () {
                $(this).attr("checked", !this.checked);//反选
                var chk_id = $(this).attr('data-id')
                if ($(this).attr('checked') == true || $(this).attr('checked') == 'checked') {
                    $(this).parents('.row[data-id=' + chk_id + ']').addClass('selected');
                } else {
                    $(this).parents('.row[data-id=' + chk_id + ']').removeClass('selected');
                }
            });
        });
    }

    // all select status
    exports.selectNone = function () {
        // 全不选
        $(".list .listData input.chk").attr("checked", false);
        $('.list .listData .row').removeClass('selected');
    }

    // go to the input page
    exports.goPage = function (btn) {
        $(btn).live('click', function () {
            var page = parseInt($(this).siblings('.pageVal').val());
            var requestSting = $(this).attr('data-string');
            var suffix = $(this).attr('data-suffix');
            var total = $(this).attr('data-total');
            page = isNaN(page) ? 1 : page;
            page = page >= total ? total : page;
            page = page <= 1 ? 1 : page;
            var url = $(this).attr('data-url') + '/p/' + page + suffix;
            url = requestSting ? url + '?' + requestSting : url;
            window.location.href = url;
        });
    }

    // popup basic show hide
    exports.showPop = function (popElem) {
        var pop = new popup(popElem);
        pop.show();
    }


    // popup basic show hide
    exports.hidePop = function (popElem) {
        var pop = new popup(popElem);
        pop.hide();
    }


    // popup
    function popup(popElem) {
        this.widget = popElem ? popElem : '.popup-widget';

        var _this = this;
        // close click
        $('.popup-widget .popup-close').on('click', function () {
            _this.hide();
        });

        // bg click
        $('.popup-bg').on('click', function () {
            _this.hide();
        });
    }

    popup.prototype = {
        'show': function () {
            $(this.widget).fadeIn();
            $('.popup-bg').fadeIn();
        },
        'hide': function () {
            $('.popup-widget').hide();
            $('.popup-bg').hide();
        }
    }

    //ajax 请求公用参数
    function ajaxParas(btn) {
        var paras = {
            dataType: 'json',
            type: 'post',
            beforeSend: function () {
                $(btn).attr('disabled', true);
                exports.showTip('wait', '正在处理请求...');
            },
            timeout: function () {
                $(btn).attr('disabled', false);
                exports.showTip('err', '请求超时,请重试！');
            },
            abort: function () {
                $(btn).attr('disabled', false);
                exports.showTip('err', '网路连接被中断！');
            },
            parsererror: function () {
                $(btn).attr('disabled', false);
                exports.showTip('err', '运行时发生错误！');
            },
            complete: function () {
                setTimeout(function () {
                    exports.hideTip();
                }, 3000);
                $(btn).attr('disabled', false);
            },
            error: function () {
                $(btn).attr('disabled', false);
                exports.showTip('err', '请求数据发生错误,请联系管理员！');
            }
        };
        return paras;
    }

    function showErrorCode(err) {
        exports.showTip('err', err.msg + '【' + err.more + '】' + ' 错误码:' + err.code, 3000);
    }
});