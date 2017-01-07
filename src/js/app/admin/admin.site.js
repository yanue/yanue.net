// admin user,group,menus,permission settings
define(function (require, exports) {

    var base = require('app/admin/admin.base');//公共函数

    exports.setMainPostCat = function (btn, type) {
        $(btn).live('click', function (e) {
            var data = [];

            $('#focusForm .row').each(function () {
                // change data
                var title = $(this).find('.txtTitle').val().trim();
                var pos = $(this).find('.pos').text().trim();
                var cid = $(this).find('.cat option:selected').val().trim();
                var name = $(this).find('.cat option:selected').text().trim();
                var pic = $(this).find('.txtPic').val().trim();
                if (title && cid) {
                    var tmp = {
                        "pos": pos,
                        "cat": {"id": cid, "name": name},
                        "title": title,
                        "pic": pic
                    };
                    data.push(tmp);
                }
            });
            if (data.length != $('#focusForm .row').length) {
                common.showTip('err', '所有项不能为空', 3000);
                return false;
            }
            //var a = JSON.stringify(data);
            // console.log(a);
            var data = {'data': data, 'type': type }

            // disable the button
            $(btn).attr('disabled', true);
            // api request
            common.requestApi('/api/site/setFocus', data, btn, true, function (res) {

                common.showTip('ok', '设置成功！即将跳转');
                setTimeout(function () {
                    window.location.reload();
                }, 1000);

                // cancel to disable the btn
                $(btn).attr('disabled', false);
            });
            e.stopImmediatePropagation();
        });
    }

    exports.setFocus = function (btn, type) {
        $(btn).live('click', function (e) {
            var data = [];

            $('#focusForm .row').each(function () {
                // change data
                var title = $(this).find('.txtTitle').val().trim();
                var url = $(this).find('.txtUrl').val().trim();
                var pic = $(this).find('.txtPic').val().trim();
                if (title && url && pic) {
                    var tmp = {
                        title: title,
                        url: url,
                        pic: pic
                    };
                    data.push(tmp);
                }
            });
            if (data.length != $('#focusForm .row').length) {
                common.showTip('err', '所有项不能为空', 3000);
                return false;
            }
            //var a = JSON.stringify(data);
            // console.log(a);
            var data = {'data': data, 'type': type }
            // disable the button
            $(btn).attr('disabled', true);
            // api request
            common.requestApi('/api/site/setFocus', data, btn, true, function (res) {

                common.showTip('ok', '设置成功！即将跳转');
                setTimeout(function () {
                    window.location.reload();
                }, 1000);

                // cancel to disable the btn
                $(btn).attr('disabled', false);
            });
            e.stopImmediatePropagation();
        });
    }
});