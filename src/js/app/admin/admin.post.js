// admin user,group,menus,permission settings
define(function (require, exports) {

    var base = require('app/admin/admin.base');//公共函数
    var site_url = app.site_url;

    /**
     * publish post or not
     *
     * @param btn
     * @param referer
     */
    exports.pubPost = function (btn) {
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
            base.requestApi('/api/post/publish', data, btn, true, function (res) {
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
     * del post
     * @param btn
     */
    exports.delPost = function (btn, cid) {
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
            base.requestApi('/api/post/del', data, btn, true, function (res) {
                if (res.error.code == 0) {
                    $('.listData .row[data-id="' + id + '"]').remove();
                    if (cid != undefined) {
                        setTimeout(function () {
                            window.location.href = site_url + 'admin/post/index/cid/' + cid;
                        }, 1000)
                    }
                    base.showTip('ok', '删除成功！');
                }
            });
            e.stopImmediatePropagation();
        });
    };

    /**
     * del post
     * @param btn
     */
    exports.delAllPost = function (btn) {
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
            base.requestApi('/api/post/delAll', {'data': data}, btn, true, function (res) {
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
     * add post
     */
    exports.setPost = function (btn) {
        // 初始化编辑器
        var editor = require('app/app.editor');
        editor.init('#postForm #txtContent');
        // 初始化上传
        uploadPic();

        $(btn).live('click', function (e) {
            // params
            var title = $('#postForm #txtTitle').val().trim();
            var subTitle = $('#postForm #txtSubTitle').val().trim();
            var cid = $('#postForm #intCat').val();
            var cover_img = $('#postForm #coverImg').val();
            var created = $('#postForm #created').val().trim();
            var source = $('#postForm #source').val();
            var author = $('#postForm #author').val();
            var keywords = $('#postForm #keywords').val();
            var content = $('#postForm #txtContent').val();
            var recommend = $('#postForm input[name="recommend"]:checked').val();
            var published = $('#postForm input[name="published"]:checked').val();
            // if update
            var id = $(this).attr('data-id');

            if (checkNull(title, '#postForm #txtTitle') == false) {
                return false;
            }

            if (isNaN(cid) || cid == '' || cid == null) {
                base.showTip('err', '请选择文章分类！', 3000);
                return false;
            }

            if (created) {
                if (false == IsDateTime(created)) {
                    base.showTip('err', '时间格式不正确！', 3000);
                    return false;
                }
            }

            // tags
            var tags = [];
            $('.tag-list .tag').each(function () {
                var tagName = $(this).attr('data-tag').trim();
                if (tagName) {
                    tags.push(tagName);
                }
            });
            tags = $.unique(tags);

            var data = {
                'title': title,
                'sub_title': subTitle,
                'cid': cid,
                'cover_img': cover_img,
                'created': created,
                'source': source,
                'author': author,
                'content': content,
                'keywords': keywords,
                'recommend': recommend,
                'published': published,
                'tags': tags
            };
            if (id) {
                // update params
                data = $.extend({}, data, {'id': id});
            }

            // current button
            var _this = this;
            // api request
            base.requestApi('/api/post/set', data, _this, true, function (res) {
                if (res.error.code == 0) {
                    if (id) {
                        window.location.reload();
                        base.showTip('ok', '操作成功！');
                    } else {
                        window.location.href = site_url + '/post/add/cid/' + cid;
                        base.showTip('ok', '操作成功！');
                    }
                }
                $(_this).attr('disabled', false);
            });
            e.stopImmediatePropagation();
        });
    };

    /**
     * del post
     * @param btn
     */
    exports.delCat = function (btn) {
        $(btn).live('click', function (e) {
            // params
            var id = $(this).attr('data-id');

            var data = {
                'id': id
            };

            // confirm
            var cm = window.confirm("严重警告！\r\n　　删除栏目后该栏目及子栏目原有的所有文章会将不能指定栏目，前台页面可能会出错哦！\r\n　　您确定需要进行此操作吗？");
            if (!cm) {
                return;
            }

            // disable the button
            $(btn).attr('disabled', true);
            // api request
            base.requestApi('/api/post/delCat', data, btn, true, function (res) {
                if (res.error.code == 0) {
                    window.location.reload();
                    base.showTip('ok', '删除成功！');
                }
            });
            e.stopImmediatePropagation();
        });
    };

    /**
     * mv cat and their children to new parent
     * @param btn
     */
    exports.mvCat = function (btn) {
        $(btn).live('click', function (e) {
            // params
            var cid = $(this).attr('data-cid');
            var toCid = $(this).parents('.row[data-id=' + cid + ']').find('select.mvCat').val();

            if (isNaN(toCid) || toCid == '') {
                base.showTip('err', '请选择栏目！', 3000);
                return false;
            }
            // confirm
            var cm = window.confirm("严重警告！\r\n　　移动栏目后该栏目及子栏目会移动到新栏目,如果栏目降级则栏目及子栏目所有文章会将移动到新栏目，前台页面可能会出错哦！\r\n　　您确定需要进行此操作吗？");
            if (!cm) {
                return;
            }
            var data = {
                'cid': cid,
                'toCid': toCid
            };
            // disable the button
            $(btn).attr('disabled', true);
            // api request
            base.requestApi('/api/post/mvCat', data, btn, true, function (res) {
                if (res.error.code == 0) {
                    window.location.reload();
                    base.showTip('ok', '移动栏目成功！');
                }
            });
            e.stopImmediatePropagation();
        });
    };

    /**
     * mv cat and their children to new parent
     * @param btn
     */
    exports.mvPost = function (btn) {
        $(btn).live('click', function (e) {
            // params
            var toCid = $('select.mvPostCat').val();

            if (isNaN(toCid) || toCid == '') {
                base.showTip('err', '请选择栏目！', 3000);
                return false;
            }
            var data = [];
            $(".list .listData input.chk").each(function () {
                if ($(this).attr('checked') == true || $(this).attr('checked') == 'checked') {
                    data.push($(this).attr('data-id'));
                }
            });

            //  has no selected
            if (data.length == 0) {
                base.showTip('err', '请选择需要移动的项', 3000);
                return;
            }

            // confirm
            var cm = window.confirm('你确定需要移动选中的文章到新分类吗？');
            if (!cm) {
                return;
            }

            // disable the button
            $(btn).attr('disabled', true);
            // api request
            base.requestApi('/api/post/mvPost', {'data': data, 'cid': toCid}, btn, true, function (res) {
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
     * menus cats
     */
    exports.setCats = function () {

        $("table#postCats .operate").live({
                mouseenter: function () {
                    $(this).find('.add').show();
                },
                mouseleave: function () {
                    $(this).find('.add').hide();
                }
            }
        );
        /**
         * expand the hide menus
         */
        $('table#postCats .expand').live('click', function (e) {
            var status = $(this).attr('data-status');
            var cid = $(this).attr('data-cid');
            var txt = $(this).text();
            // current is hidden
            if (status == 'hide') {
                $('#postCats tr[data-pid=' + cid + ']').show();
                $(this).attr('data-status', 'show');
                if (txt == '[ + ]') {
                    $(this).text('[ - ]');
                } else {
                    $(this).text('[-]');
                }
            } else {
                $('tr[data-pid=' + cid + ']').hide();
                $(this).attr('data-status', 'hide');
                if (txt == '[ - ]') {
                    $(this).text('[ + ]');
                } else {
                    $(this).text('[+]');
                }
            }
            e.stopImmediatePropagation();
        });

        /**
         * add action
         */
        $('table#postCats .add').live('click', function (e) {
            var parent_id = $(this).attr('data-pid');
            $('.expand[data-cid=' + parent_id + ']').attr('data-status', 'hide').text('[+]').trigger('click');
            // append empty row
            var str = '<tr class="row level2" data-pid="' + parent_id + '" style="display: table-row;">';
            str += '    <th class="name"></th>';
            str += '<td class="operate"></td>';
            str += '<td>|一一 <input class="txt catTitle" type="text" value=""></td>';
            str += '<td><input class="txt catEnTitle" type="text" value=""></td>';
            str += '<td><input class="txt catAlias" type="text" value=""></td>';
            str += '<td><input class="txt catDetail" type="text" value=""></td>';
            str += '</tr>';
            // append
            $('#postCats tr[data-id=' + parent_id + ']').after(str);
            e.stopImmediatePropagation();
        });
        var btn = '#catDoBtn';
        $(btn).live('click', function (e) {
            var data = [];
            $('#postCats .row').each(function () {
                var cid = $(this).attr('data-id');
                var parent_id = $(this).attr('data-pid');
                // change data
                var title = $(this).find('.catTitle').val().trim();
                var en_title = $(this).find('.catEnTitle').val().trim();
                var alias = $(this).find('.catAlias').val().trim();
                var detail = $(this).find('.catDetail').val().trim();

                //old data
                var old_title = $(this).find('.catTitle').attr('data-old-title');
                var old_alias = $(this).find('.catAlias').attr('data-old-alias');
                var old_enTitle = $(this).find('.catEnTitle').attr('data-old-enTitle');
                var old_detail = $(this).find('.catDetail').attr('data-old-detail');

                if (!(title == old_title && alias == old_alias && en_title == old_enTitle && detail == old_detail)) {
                    var menu = {
                        id: cid,
                        parent_id: parent_id,
                        name: title,
                        en_name: en_title,
                        alias: alias,
                        detail: detail
                    };
                    data.push(menu);
                }
            });
            if (data.length == 0) {
                base.showTip('err', '您未作任何的修改', 3000);
                return false;
            }
            //var a = JSON.stringify(data);
            // console.log(a);
            var data = {'postCats': data }
            console.log(data);
            // disable the button
            $(btn).attr('disabled', true);
            // api request
            base.requestApi('/api/post/setCats', data, btn, true, function (res) {

                base.showTip('ok', '栏目设置成功！即将跳转');
                setTimeout(function () {
                    window.location.reload();
                }, 1000);

                // cancel to disable the btn
                $(btn).attr('disabled', false);
            });
            e.stopImmediatePropagation();
        });
    }

    exports.setTags = function () {
        // add
        $('.addTag').live('click', function (e) {
            var val = $('.tagVal').val();
            var tags = val.split(',');
            var tmp = [];
            for (var i = 0; i < tags.length; i++) {
                var tag = tags[i];
                var tp = tag.split(' ');
                for (var j = 0; j < tp.length; j++) {
                    if (tp[j] && tp[j]) {
                        tmp.push(tp[j]);
                    }
                }
            }
            // old data
            $('.tag-list .tag').each(function () {
                var tagname = $(this).attr('data-tag');
                if (tagname) {
                    tmp.push(tagname)
                }
            });
            tmp = $.unique(tmp);
            var str = '';
            for (var k in tmp) {
                str += '<span class="tag" data-tag="' + tmp[k] + '"><i class="icon icon-remove rm-tag">x</i> ' + tmp[k] + '</span>';
            }
            $('.tag-list').html(str);
            $('.tagVal').val('');
            e.stopImmediatePropagation();
        });

        // remove
        $('.tag-list .tag  .rm-tag').live('click', function (e) {
            var _this = this;
            $(_this).parent().fadeOut();
            setTimeout(function () {
                $(_this).parent().remove();
            }, 500);
            e.stopImmediatePropagation();
        });

        // add from exists
        $('.all-tags .tag').live('click', function (e) {
            var tag = $(this).attr('data-tag');
            var tmp = [tag];
            // old data
            $('.tag-list .tag').each(function () {
                var tagname = $(this).attr('data-tag');
                if (tagname) {
                    tmp.push(tagname)
                }
            });
            tmp = $.unique(tmp);

            var str = '';
            for (var k in tmp) {
                str += '<span class="tag" data-tag="' + tmp[k] + '"><i class="icon icon-remove rm-tag">x</i> ' + tmp[k] + '</span>';
            }
            $('.tag-list').html(str);

            e.stopImmediatePropagation();
        });
    }

    function uploadPic() {
        seajs.use('app/app.upload', function (api) {
            api.upload('.upload-widget', {'type': 'pic'}, function (res) {
                $('#coverImg').val(res.url);
                $('.imgPreview').attr('src', res.url).show();
            });
        });

    }

    function IsDateTime(strDateTime) {
        // 先判断格式上是否正确
        var regDateTime = /^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;
        if (!regDateTime.test(strDateTime))
            return false;

        // 将年、月、日、时、分、秒的值取到数组arr中，其中arr[0]为整个字符串，arr[1]-arr[6]为年、月、日、时、分、秒
        var arr = regDateTime.exec(strDateTime);

        // 判断年、月、日的取值范围是否正确
        if (!IsMonthAndDateCorrect(arr[1], arr[2], arr[3]))
            return false;

        // 判断时、分、秒的取值范围是否正确
        if (arr[4] >= 24)
            return false;
        if (arr[5] >= 60)
            return false;
        if (arr[6] >= 60)
            return false;

        function IsMonthAndDateCorrect(nYear, nMonth, nDay) {
            // 月份是否在1-12的范围内，注意如果该字符串不是C#语言的，而是JavaScript的，月份范围为0-11
            if (nMonth > 12 || nMonth <= 0)
                return false;

            // 日是否在1-31的范围内，不是则取值不正确
            if (nDay > 31 || nMonth <= 0)
                return false;

            // 根据月份判断每月最多日数
            var bTrue = false;
            switch (nMonth) {
                case 1:
                case 3:
                case 5:
                case 7:
                case 8:
                case 10:
                case 12:
                    bTrue = true;    // 大月，由于已判断过nDay的范围在1-31内，因此直接返回true
                    break;
                case 4:
                case 6:
                case 9:
                case 11:
                    bTrue = (nDay <= 30);    // 小月，如果小于等于30日返回true
                    break;
            }

            if (!bTrue)
                return true;

            // 2月的情况
            // 如果小于等于28天一定正确
            if (nDay <= 28)
                return true;
            // 闰年小于等于29天正确
            if (IsLeapYear(nYear))
                return (nDay <= 29);
            // 不是闰年，又不小于等于28，返回false
            return false;
        }

        // 正确的返回
        return true;
    }

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