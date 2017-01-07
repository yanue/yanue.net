// for set channel suggest data
define(function (require, exports) {
    var common = require('app/admin/admin.base');//公共函数

    exports.setLinks = function (form) {
        var btn = form + ' .submitBtn';
        // set form
        function setForm(item) {
            $(form + ' .logo').val(item.logo);
            $(form + ' .txtTitle').val(item.name);
            $(form + ' .txtUrl').val(item.url);
            $(form + ' .txtAdmin').val(item.admin);
            $(form + ' .txtContact').val(item.contact);
            $(form + ' .intSort').val(item.sort);
            $(form + ' .txtDetail').val(item.detail);
            $(btn).attr('data-id', item.id);
        }

        // reset form
        function emptyForm() {
            // reset form
            $(form + ' .logo').val('');
            $(form + ' .txtTitle').val('');
            $(form + ' .txtUrl').val('');
            $(form + ' .txtAdmin').val('');
            $(form + ' .txtContact').val('');
            $(form + ' .intSort').val('');
            $(form + ' .txtDetail').val('');
            $(btn).removeAttr('data-id');
        }

        function setItem(item, id) {
            var obj = $('.listData .row[data-id="' + id + '"]');
            // set form data
            obj.find('.logo').attr('src', item.logo).text(item.name);
            obj.find('.admin').text(item.admin);
            obj.find('.contact').text(item.contact);
            obj.find('.sort').text(item.sort);
            obj.find('.txtDetail').text(item.detail);
        }

        // get data
        $('.row .updateBtn').live('click', function () {
            var id = $(this).attr('data-id');
            common.requestApi('/api/links/get', {'id': id}, btn, true, function (res) {
                if (res.error.code == 0) {
                    common.showTip('ok', '成功获取数据', 1000);
                    var item = res.data;

                    // set form data
                    setForm(item);

                    common.showPop('');
                }
            });
        });

        // add
        $('.addLinks').live('click', function () {
            emptyForm();
            common.showPop();
        });

        // submit
        $(btn).live('click', function (e) {
            // params
            var id = $(this).attr('data-id');
            var pic_url = $(form + ' .logo').val();
            var title = $(form + ' .txtTitle').val();
            var url = $(form + ' .txtUrl').val();
            var admin = $(form + ' .txtAdmin').val();
            var contact = $(form + ' .txtContact').val();
            var sort = $(form + ' .intSort').val();
            var detail = $(form + ' .txtDetail').val();

            var item = {
                'name': title,
                'logo': pic_url,
                'url': url,
                'admin': admin,
                'contact': contact,
                'sort': sort,
                'detail': detail
            };

            var data = null;
            if (id) {
                // for update
                var data = $.extend({}, {'id': id}, {'data': item});
            } else {
                // for insert
                var data = {'data': item};
            }

            // disable the button
            $(btn).attr('disabled', true);

            // api request
            common.requestApi('/api/links/set', data, btn, true, function (res) {
                if (res.error.code == 0) {
                    if (id) {
                        // set dom
                        setItem(item, id);
                    } else {
                        window.location.reload()
                    }
                    common.showTip('ok', '操作成功！');
                    common.hidePop();
                }
            });
            e.stopImmediatePropagation();
        })

    };

    // del
    exports.del = function (btn) {
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
            common.requestApi('/api/links/del', data, btn, true, function (res) {
                if (res.error.code == 0) {
                    $('.listData .row[data-id="' + id + '"]').remove();
                    common.showTip('ok', '删除成功！');
                }
            });

            e.stopImmediatePropagation();
        });
    }
});