/**
 @author yanue

 功能：
 1.保证不会变形
 2.铺满所需窗口
 图片设置用法:
 <div style="width: 200px;height: 160px;overflow: hidden;border: 1px solid #eee;">
 <img src="" data-src="http://photos.tuchong.com/302087/f/6439990.jpg" alt="" data-width="200" data-height="160" data-rel="ready" class="imgReady"/>
 </div>
 js调用方法：
 $(function () {
    $('img.imgReady[data-rel="ready"]').each(function () {
        var img = $(this).attr('data-src');
        if (img) {
            var _this = this;
            imgReady(img, function () {
                var w_width = $(_this).attr('data-width');
                var w_height = $(_this).attr('data-height');
                var css = resize_ready_img(w_width, w_height, this.width, this.height);
                // 重置窗口并显示
                $(_this).attr('src', img).css(css).removeAttr('data-src').show();
            }, function () {

            }, function () {

            });
        }
    });
 })
 说明：
 1.请将外层div设为超出隐藏，并设置窗口大小
 2.data-src为原始图片的地址
 3.img上面设置属性data-width,data-height分别为外层的窗口大小
 4.data-rel="ready"为区分是否需要进行imgReady操作
 */


/**
 * imageReady v1.1
 * By qiqiboy, http://www.qiqiboy.com, http://weibo.com/qiqiboy, 2013/12/19
 */
var imgReady = (function () {
    var list = [],
        timer = null,
        prop = [
            ['width', 'height'],
            ['naturalWidth', 'naturalHeight']
        ],
        natural = Number(typeof (new Image()).naturalWidth == 'number'),//是否支持HTML5新增的 naturalHeight
        tick = function () {
            var i = 0;
            while (i < list.length) {
                list[i].end ? list.splice(i--, 1) : check.call(list[i]);
                i++;
            }
            list.length && (timer = setTimeout(tick, 50)) || (timer = null);
        },
        /** overflow: 检测图片尺寸的改变
         *  img.__width,img.__height: 初载入时的尺寸
         */
            check = function () {
            if (this.complete || this[prop[natural][0]] !== this.__width || this[prop[natural][1]] !== this.__height || this.readyState == 'loading') {
                this.end = true;
                this.onready(this, this);
            }
        };

    return function (_img, onready, onload, onerror) {
        onready = onready || new Function();
        onload = onload || new Function();
        onerror = onerror || new Function();
        var img = typeof _img == 'string' ? new Image() : _img;
        img.onerror = function () {// ie && ie<=8 的浏览器必须在src赋予前定义onerror
            img.end = true;
            img.onload = img.onerror = img.onreadystatechange = null;
            onerror.call(img, img);
            img = null;
        }
        if (typeof _img == 'string') img.src = _img;
        if (!img)return; //为了防止onerror触发后img=null
        if (img.complete) {
            img.onerror = null;
            onready.call(img, img);
            onload.call(img, img);
            img = null;
            return;
        }
        img.__width = img[prop[natural][0]];
        img.__height = img[prop[natural][1]];
        img.onready = onready;
        check.call(img);
        img.onload = img.onreadystatechange = function () {
            if (img && img.readyState && img.readyState != 'loaded' && img.readyState != 'complete') {
                return;
            }
            img.onload = img.onerror = img.onreadystatechange = null;
            !img.end && check.call(img);
            onload.call(img, img);
            img = null;
        }
        if (!img.end) {
            list.push(img);
            !timer && (timer = setTimeout(tick, 50));
        }
    }
})();

function resize_ready_img(w_w, w_h, i_w, i_h) {
    var ratio = i_w / i_h;
    var width = w_w != 0 ? w_w : 200;
    var height = w_h != 0 ? w_h : 200;
    var frameRatio = w_w / w_h;//外框大小比例
    var offestX = 0;
    var offestY = 0;
    var newImgWidth = 0;
    var newImgHeight = 0;
    if (ratio >= frameRatio) {
        //宽一点 最小边高 取高
        newImgHeight = height;
        newImgWidth = height * ratio;
        offestY = 0;
        offestX = (newImgWidth - width) / 2;
    } else {
        //窄一点 最小边宽 取宽
        newImgWidth = width;
        newImgHeight = width / ratio;
        offestX = 0;
        offestY = (newImgHeight - height) / 2;
    }

    // 判断
    var offestX_O = {'margin-left': '-' + parseInt(offestX) + 'px'}
    var offestY_O = {'margin-top': '-' + parseInt(offestY) + 'px'}
    var width_O = {'width': parseInt(newImgWidth) + 'px'}
    var height_O = {'height': parseInt(newImgHeight) + 'px'}

    return  $.extend({}, offestX_O, offestY_O, width_O, height_O);

}
