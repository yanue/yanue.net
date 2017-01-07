//拖拽
(function ($) {

    $.extend({
        //获取鼠标当前坐标
        mouseCoords: function (ev) {
            if (ev.pageX || ev.pageY) {
                return {x: ev.pageX, y: ev.pageY};
            }
            return {
                x: ev.clientX + document.body.scrollLeft - document.body.clientLeft,
                y: ev.clientY + document.body.scrollTop - document.body.clientTop
            };
        },
        //获取样式值
        getStyle: function (obj, styleName) {
            return obj.currentStyle ? obj.currentStyle[styleName] : document.defaultView.getComputedStyle(obj, null)[styleName];
//                return obj.currentStyle ? obj.currentStyle[styleName] : document.defaultView.getComputedStyle(obj,null).getPropertyValue(styleName);
        }
    });

    // 元素拖拽插件
    $.fn.dragDrop = function (options) {
        var opts = $.extend({}, $.fn.dragDrop.defaults, options);
        return this.each(function () {
            //是否正在拖动
            var bDraging = false;
            //移动的元素
            var moveEle = $(this);
            //点击哪个元素，以触发移动。
            //该元素需要是被移动元素的子元素（比如标题等）
            var focuEle = opts.focuEle ? $(opts.focuEle, moveEle) : moveEle;
            if (!focuEle || focuEle.length <= 0) {
                alert('focuEle is not found! the element must be a child of ' + this.id);
                return false;
            }

            // initDiffX|Y : 初始时，鼠标与被移动元素原点的距离
            // moveX|Y : 移动时，被移动元素定位位置 (新鼠标位置与initDiffX|Y的差值)
            // 如果定义了移动中的回调函数，该对象将以参数传入回调函数。
            var dragParams = {initDiffX: '', initDiffY: '', moveX: '', moveY: ''};

            //被移动元素，需要设置定位样式，否则拖拽效果将无效。
            moveEle.css({'position': 'absolute', 'cursor': 'move'});
            moveEle.attr('drag', 'true');
            //DOM写法： getElementById('***').onmousedown= function(event);
            focuEle.bind('mousedown', function (e) {

                //标记开始移动
                bDraging = true;

                //捕获事件。（该用法，还有个好处，就是防止移动太快导致鼠标跑出被移动元素之外）
                if (moveEle.get(0).setCapture) {
                    moveEle.get(0).setCapture();
                }

                //（实际上是鼠标当前位置相对于被移动元素原点的距离）
                // DOM写法：(ev.clientX + document.body.scrollLeft - document.body.clientLeft) - document.getElementById('***').style.left;

                dragParams.initDiffX = $.mouseCoords(e).x - moveEle.position().left;
                dragParams.initDiffY = $.mouseCoords(e).y - moveEle.position().top;

            });

            //移动过程
            focuEle.bind('mousemove', function (e) {
                e.preventDefault();
                window.event.returnValue = false;

                if (bDraging) {
                    //被移动元素的新位置，实际上鼠标当前位置与原位置之差
                    //实际上，被移动元素的新位置，也可以直接是鼠标位置，这也能体现拖拽，但是元素的位置就不会精确。
                    dragParams.moveX = $.mouseCoords(e).x - dragParams.initDiffX;
                    dragParams.moveY = $.mouseCoords(e).y - dragParams.initDiffY;
                    //是否限定在某个区域中移动.
                    //fixarea格式: [x轴最小值,x轴最大值,y轴最小值,y轴最大值]
                    if (opts.fixarea) {
                        if (dragParams.moveX < opts.fixarea[0]) {
                            dragParams.moveX = opts.fixarea[0]
                        }
                        if (dragParams.moveX > opts.fixarea[1]) {
                            dragParams.moveX = opts.fixarea[1]
                        }
                        if (dragParams.moveY < opts.fixarea[2]) {
                            dragParams.moveY = opts.fixarea[2]
                        }
                        if (dragParams.moveY > opts.fixarea[3]) {
                            dragParams.moveY = opts.fixarea[3]
                        }
                    }

                    //移动方向：可以是不限定、垂直、水平。
                    if (opts.dragDirection == 'all') {
                        //DOM写法： document.getElementById('***').style.left = '***px';
                        moveEle.css({'left': dragParams.moveX, 'top': dragParams.moveY});
                    }
                    else if (opts.dragDirection == 'vertical') {
                        moveEle.css({'top': dragParams.moveY});
                    }
                    else if (opts.dragDirection == 'horizontal') {
                        moveEle.css({'left': dragParams.moveX});
                    }
                    //如果有回调
                    if (opts.callback) {
                        //将dragParams作为参数传递
                        opts.callback.call(opts.callback, dragParams);
                    }
                }

            });

            //鼠标弹起时，标记为取消移动
            focuEle.bind('mouseup', function (e) {
                bDraging = false;
                moveEle.css({'cursor': 'move'});
                if (moveEle.get(0).releaseCapture) {
                    moveEle.get(0).releaseCapture();
                }
            });
        });
    };
    //默认配置
    $.fn.dragDrop.defaults =
    {
        focuEle: null,            //点击哪个元素开始拖动,可为空。不为空时，需要为被拖动元素的子元素。
        callback: null,            //拖动时触发的回调。
        dragDirection: 'all',    //拖动方向：['all','vertical','horizontal']
        fixarea: null            //限制在哪个区域拖动,以数组形式提供[minX,maxX,minY,maxY]

    };
})(jQuery);

(function ($) {

    $.fn.fullScreenGallery = function (settings) {
        settings = jQuery.extend({
            imageArray: [],
            activeImage: 0
        }, settings);

        //将图片地址放入数组
        $(this).each(function (i) {
            if ($(this).attr('src')) {
                settings.imageArray.push($(this).attr('src'))
            }
        });

        //获取窗口尺寸
        function _getWindowSize() {
            var winWidth = $(window).width();
            var winHeight = $(window).height();
            return [winWidth, winHeight];
        }

        //设置界面
        function _set_interface() {
            var html = $('.post-detail .comment-area').html();
            // cp comment
            $('#overlay-panel .comment-wrap').html(html);
            $('.post-detail .comment-area').html('');// 立即清空评论数据
            $("#picView-overlay").show();

            $('html,body').css({'overflow': 'hidden'});
            $('#picView-imgViewBox').css({
                width: (_getWindowSize()[0] - 400) + 'px',
                height: _getWindowSize()[1],
                lineHeight: _getWindowSize()[1] + 'px'
            });
            $('#picView-overlay').css({
                width: (_getWindowSize()[0] - 400),
                height: _getWindowSize()[1]
            });
            $('#overlay-panel').css({
                height: _getWindowSize()[1]
            });
            $('#all').html('/' + settings.imageArray.length);

            //下一张
            $('#picView-next').live('click', function (e) {
                settings.activeImage += 1;
                if (settings.activeImage > settings.imageArray.length - 1) {
                    settings.activeImage = 0
                }
                _createImg(settings.activeImage)
                e.stopImmediatePropagation();
            });
            //上一张
            $('#picView-prev').live('click', function (e) {
                settings.activeImage -= 1;
                if (settings.activeImage < 0) {
                    settings.activeImage = settings.imageArray.length - 1
                }
                _createImg(settings.activeImage)
                e.stopImmediatePropagation();
            });

            //关闭
            $('#picView-close').live('click', function (e) {
                _finish()
                e.stopImmediatePropagation();
            });

            //resize

            $(window).resize(function () {
                _resizeImg($('#theImg'))
            })
        }

        function _resizeImg(jqObj) {
            var arrPageSizes = _getWindowSize();
            $('#picView-imgViewBox').css({
                width: (arrPageSizes[0] - 400),
                height: (arrPageSizes[1] - 72)
            });
            $('#picView-overlay').css({
                width: (arrPageSizes[0] - 400),
                height: arrPageSizes[1]
            });
            $('#overlay-panel').css({
                height: arrPageSizes[1]
            });

            //如果图片高或者宽超过屏幕
            if (jqObj.attr('ow') > (arrPageSizes[0] - 400) || jqObj.attr('oh') > (arrPageSizes[1] - 72)) {
                var s1 = (arrPageSizes[0] - 400) / (arrPageSizes[1] - 72);
                var s2 = jqObj.attr('ow') / jqObj.attr('oh');

                if (s2 > s1) {
                    //横图 缩小
                    var smallHeight = parseInt((arrPageSizes[0] - 400) * jqObj.attr('oh') / jqObj.attr('ow'));
                    jqObj.animate({'width': arrPageSizes[0] - 400, 'height': smallHeight, 'left': 0, 'top': parseInt(((arrPageSizes[1] - 72) - smallHeight) / 2)});

                    jqObj.attr('zoom', 'small');
                    $('#zoomOut').show();
                    $('#zoomIn').hide();
                } else {
                    //竖图 缩小
                    var smallWidth = parseInt((arrPageSizes[1] - 72) * jqObj.attr('ow') / jqObj.attr('oh'));
                    jqObj.animate({'width': smallWidth, 'height': (arrPageSizes[1] - 72), 'left': parseInt((arrPageSizes[0] - 400 - smallWidth) / 2), 'top': 0}, function () {
                        jqObj.attr('zoom', 'small');
                        $('#zoomOut').show();
                        $('#zoomIn').hide();
                    });

                }


                function _zoomOut(jqObj) {

                    jqObj.animate({'width': jqObj.attr('ow'), 'height': jqObj.attr('oh'), 'left': parseInt((arrPageSizes[0] - 400 - jqObj.attr('ow')) / 2), 'top': parseInt(((arrPageSizes[1] - 72) - jqObj.attr('oh')) / 2)}, function () {
                        jqObj.attr('zoom', 'big');
                        $('#zoomOut').hide();
                        $('#zoomIn').show();
                    });
                }

                //缩放按钮
                $("#zoomOut").live('click', function () {
                    _zoomOut(jqObj);
                });

                $("#zoomIn").live('click', function () {
                    _resizeImg(jqObj);
                });

                jqObj.unbind('dblclick').bind('dblclick', function () {
                    if (jqObj.attr('zoom') == 'small') {
                        _zoomOut(jqObj);
                    }
                    if (jqObj.attr('zoom') == 'big') {
                        _resizeImg(jqObj);
                    }
                })

            } else {
                //如果图片高或者宽小于屏幕，直接读原图
                $('#zoomOut').hide();
                var ow = jqObj.attr('ow');
                var oh = jqObj.attr('oh');
                jqObj.animate({'width': 'ow', 'height': 'oh', 'top': ((arrPageSizes[1] - 72) - oh) / 2, 'left': (arrPageSizes[0] - 400 - ow) / 2});
            }
        }

        function _createImg(n) {

            settings.activeImage = n;
            var imgObj = '<img id="theImg" src="' + settings.imageArray[n] + '"/>';
            $("#picView-imgViewBox").html(imgObj).append('<div id="loading">大图加载中 请稍后...</div>');
            $("#current").html(settings.activeImage + 1);
            $("#theImg").dragDrop();
            var imgpreLoader = new Image();

            if ($.browser.msie) {
                imgpreLoader.onreadystatechange = function () {
                    $("#loading").remove();
                    $('#theImg').attr('ow', imgpreLoader.width).attr('oh', imgpreLoader.height);
                    _resizeImg($('#theImg'));
                    preLoadNeighbour()
                }
            }
            else {
                imgpreLoader.onload = function () {
                    if (imgpreLoader.complete == true) {
                        $('#loading').remove();
                        $('#theImg').attr('ow', imgpreLoader.width).attr('oh', imgpreLoader.height);
                        _resizeImg($('#theImg'));
                        preLoadNeighbour()
                    }
                }
            }
            imgpreLoader.onerror = function () {
                $('#picView-imgViewBox').html('<div id="loading">图片加载失败请点下一张...</div>');
                $('#zoomOut').hide();
                $('#zoomIn').hide();
            }
            imgpreLoader.src = settings.imageArray[n];
        }

        function preLoadNeighbour() {

            if ((settings.imageArray.length - 1) > settings.activeImage) {
                var nextImg = new Image();
                nextImg.src = settings.imageArray[settings.activeImage + 1];
            }
            if (settings.activeImage > 0) {
                var prevImg = new Image();
                prevImg.src = settings.imageArray[settings.activeImage - 1];
            }
        }


        function _finish() {
            var html = $('#overlay-panel .comment-wrap').html();
            $('.post-detail .comment-area').html(html);
            $('html,body').css({'overflow': ''});

            $('#overlay-panel .comment-wrap').html(''); //set empty
            $('#picView-overlay').fadeOut(function () {
            });
            // Show some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
            $('embed, object, select').css({ 'visibility': 'visible' });
        }


        function _keyboard_action(e) {
            var e = window.event || e;
            keycode = e.keyCode;
            if (keycode == 37) {
                settings.activeImage -= 1;
                if (settings.activeImage < 0) {
                    settings.activeImage = settings.imageArray.length - 1
                }
                _createImg(settings.activeImage)
            }
            if (keycode == 39) {

                settings.activeImage += 1;
                if (settings.activeImage > settings.imageArray.length - 1) {
                    settings.activeImage = 0
                }
                _createImg(settings.activeImage)
            }
            if (keycode == 27) {
                _finish()
            }
        }

        //键盘事件

        $(document).keydown(function (e) {
            _keyboard_action(e)
        });

        //获取点击的图片为第几个
        function _getIndex(obj) {
            var imgurl = $(obj).attr('src');
            for (var i = 0; i < settings.imageArray.length; i++) {
                if (imgurl == settings.imageArray[i]) {
                    return i;
                }
            }
            return 0;
        }

        return this.live('click', function (e) {
            _set_interface();
            _createImg(_getIndex(this));

            e.stopImmediatePropagation();
            e.stopPropagation();
            return false
        })

    }


})(jQuery);
