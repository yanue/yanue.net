// admin user,group,menus,permission settings
define(function (require, exports) {

    var base = require('app/admin/admin.base');//公共函数
    var site_url = app.site_url;

    /**
     * publish page or not
     *
     * @param btn
     * @param referer
     */
    exports.pubPage = function (btn) {
        $(btn).live('click', function (e) {
            // params
            var id = $(this).attr('data-id');
            var is_publish = $(this).attr('data-status');
            var data = {
                'id': id,
                'published': is_publish
            };

            // disable the button
            $(btn).attr('disabled', true);
            // api request
            base.requestApi('/api/page/publish', data, btn, true, function (res) {
                if (res.error.code == 0) {
                    var btns = $('.pubBtn[data-id="' + id + '"]');
                    if (is_publish == 1) {
                        btns.attr('data-status', 0).text("取消发布").css('color', '');
                    } else {
                        btns.attr('data-status', 1).text("发布").css('color', '#f00');
                    }
                    base.showTip('ok', '操作成功！');
                }
            });
            e.stopImmediatePropagation();
        });
    };

    /**
     * del page
     * @param btn
     */
    exports.delPage = function (btn, page) {
        $(btn).live('click', function (e) {
            // params
            var id = $(this).attr('data-id');
            var data = {
                'id': id
            };

            // confirm
            var cm = window.confirm('你确定需要该条数据吗？');
            if (!cm) {
                return;
            }

            // disable the button
            $(btn).attr('disabled', true);
            // api request
            base.requestApi('/api/page/del', data, btn, true, function (res) {
                if (res.error.code == 0) {
                    $('.listData .row[data-id="' + id + '"]').remove();
                    if (page) {
                        setTimeout(function () {
                            window.location.href = site_url + 'admin/page.html';
                        }, 1000)
                    }
                    base.showTip('ok', '删除成功！');
                }
            });
            e.stopImmediatePropagation();
        });
    };

    /**
     * del page
     * @param btn
     */
    exports.delAllPage = function (btn) {
        $(btn).live('click', function (e) {
            var data = [];
            $(".list .listData input.chk").each(function () {
                if ($(this).attr('checked') == true || $(this).attr('checked') == 'checked') {
                    data.push($(this).attr('data-id'));
                }
            });

            //  has no selected
            if (data.length == 0) {
                base.showTip('err', '请选择需要删除的项', 3000);
                return;
            }

            // confirm
            var cm = window.confirm('你确定需要删除选中的数据吗？');
            if (!cm) {
                return;
            }

            // disable the button
            $(btn).attr('disabled', true);
            // api request
            base.requestApi('/api/page/delAll', {'data': data}, btn, true, function (res) {
                if (res.error.code == 0) {
                    for (var i = 0; i < data.length; i++) {
                        $('.listData .row[data-id="' + data[i] + '"]').remove();
                    }
                    base.showTip('ok', '操作成功！');
                }
            });
            e.stopImmediatePropagation();
        });
    };

    /**
     * add page
     */
    exports.setPage = function (btn) {
        // 初始化编辑器
        var editor = require('app/app.editor');
        editor.init('#pageForm #txtContent');

        $(btn).live('click', function (e) {
            // params
            var title = $('#pageForm #txtTitle').val().trim();
            var alias = $('#pageForm #txtAlias').val().trim();
            var content = $('#pageForm #txtContent').val();
            var published = $('#pageForm input[name="published"]:checked').val();
            var layout = $('#pageForm input[name="layout"]:checked').val();

            // if update
            var id = $(this).attr('data-id');

            if (checkNull(title, '#pageForm #txtTitle') == false) {
                return false;
            }

            var data = {
                'name': title,
                'alias': alias,
                'content': content,
                'layout': layout,
                'published': published
            };

            if (id) {
                // update params
                data = $.extend({}, data, {'id': id});
            }

            // current button
            var _this = this;

            // api request
            base.requestApi('/api/page/set', data, _this, true, function (res) {
                console.log(res);
                if (res.error.code == 0) {
                    if (id) {
                        window.location.reload();
                        base.showTip('ok', '操作成功！');
                    } else {
                        window.location.reload();
                        base.showTip('ok', '操作成功！');
                    }
                }
                $(_this).attr('disabled', false);
            });
            e.stopImmediatePropagation();
        });
    };

    function checkNull(str, field) {
        if (str.length == 0) {
            $(field).focus().parent().find('.status').html('不能为空');
            return false;
        } else {
            $(field).parent().find('.status').html('');
        }
        return true;
    }

});