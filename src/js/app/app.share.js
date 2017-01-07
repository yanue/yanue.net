/*
 * 	 自定义分享功能
 @author : yanue
 @site : http://yanue.net/
 @time : 2012.09.25

 使用方法：
 shareParas = {
 'elem' : "#shareAll",//分享dom
 'iconbg' : '',// 分享按钮对于页面的路径
 'pics' : [ image_service + tmp.content ],//图片地址数组
 'title' : '',//分享标题
 'desc' : brief //分享描述
 };
 seajs.use('looklo_share', function(share) {
 share.init(shareParas);
 });
 */
define(function (require, exports) {
    var appShare = {}, options = {};

    appShare.share = {
        // 初始化
        'init': function (param) {
            options.title = "半叶寒羽  - " + (param.title || '半叶寒羽') + ' @半叶寒羽';
            options.desc = "半叶寒羽  - " + (param.desc || param.title || '半叶寒羽') + ' @半叶寒羽';
            options.url = param.url || location.href;
            options.pics = param.pics || [];
            options.comment = param.comment || '';
            options.iconbg = param.iconbg || 'http://' + app._CONFIG.domain.src + '/images/icon/share.png';
            var htm = '<span class="share-btn"><em class="share_tip">分享到</em>';
            htm += ' <a name="wbshare" class="share_icon" type="icon" id="wbshare" title="分享到新浪微博" href="javascript:;"></a>';
            htm += ' <a name="qqshare" class="share_icon" type="icon" id="qqshare" title="分享到qq社区" href="javascript:;"></a>';
            htm += ' <a name="tqshare" class="share_icon" type="icon" id="tqshare" title="分享到腾讯微博" href="javascript:;"></a>';
            htm += ' <a name="rrshare" class="share_icon" type="icon" id="rrshare" title="分享到人人网" href="javascript:;"></a>';
            htm += ' <a name="dbshare" class="share_icon" type="icon" id="dbshare" title="分享到豆瓣" href="javascript:;"></a>';
            //	htm += ' <a name="kxshare" class="share_icon" type="icon" id="kxshare" title="分享到开心网" href="javascript:;"></a>';
            //	htm += ' <a name="taoshare" class="share_icon" type="icon" id="taoshare" title="分享到淘宝网" href="javascript:;"></a>';
            //	htm += ' <a name="bdshare" class="share_icon" type="icon" id="bdshare" title="分享到百度空间" href="javascript:;"></a>';
//            htm += ' <a name="t163share" class="share_icon" type="icon" id="t163share" title="分享到网易微博" href="javascript:;"></a>';
//            htm += ' <a name="shshare" class="share_icon" type="icon" id="shshare" title="分享到搜狐" href="javascript:;"></a>';
            htm += '</span>';
            // 加载分享css
            css = this.initCss(options.iconbg);
            // 在页面中加入
            $(param.elem).html(htm + css);
            // 循环执行对应方法
            $(param.elem).find('a').live('click', function () {
                var obj = $(this).attr('name');
                eval("appShare.share." + obj + "()");// eval妙用啊
            });
        },

        // css初始化
        initCss: function (iconbg) {
            var cssStr = "<style>";
            cssStr += '.share-btn {display:inline-block;height:16px;light-height:16px;}';
            cssStr += '.share-btn a.share_icon{display:inline-block;width:20px;height:16px;background-image:url(' + iconbg + ');background-repeat: no-repeat;vertical-align:middle;}';
            cssStr += '.share-btn .share_tip{display:inline-block;line-height:16px;height:16px;padding:2px 0 0 0;vertical-align:middle;font-family:"微软雅黑";color:#666;font-size:12px;font-weight:normal;font-style:normal;}';
            cssStr += '.share-btn a#qqshare{background-position:0px -80px;}';
            cssStr += '.share-btn a#tqshare{background-position:0px -240px;}';
            cssStr += '.share-btn a#dbshare{background-position:0px -400px;}';
            cssStr += '.share-btn a#bdshare{background-position:0px -160px;}';
            cssStr += '.share-btn a#taoshare{background-position:0px -560px;}';
            cssStr += '.share-btn a#rrshare{background-position:0px -200px;}';
            cssStr += '.share-btn a#wbshare{background-position:0px -120px;}';
            cssStr += '.share-btn a#kxshare{background-position:0px -280px;}';
            cssStr += '.share-btn a#tieshare{background-position:0px -600px;}';
            cssStr += '.share-btn a#msnshare{background-position:0px -480px;}';
            cssStr += '.share-btn a#t163share{background-position:0px -720px;}';
            cssStr += '.share-btn a#shshare{background-position:0px -440px;}';
            cssStr += '.share-btn a#tyshare{background-position:0px -1000px;}';
            cssStr += "</style>";
            return cssStr;
        },

        // 新浪微博
        'wbshare': function (param) {
            var submitUrl = 'http://service.weibo.com/share/share.php';
            var wbShareparam = {
                url: options.url,
                type: '3',
                appkey: '207042031',
                title: options.desc,
                pic: options.pics[0],
                ralateUid: '@looklo',
                language: 'zh-cn',
                rnd: new Date().valueOf()
            };
            this._openUrl(wbShareparam, submitUrl);
            return false;
        },

        // 人人网
        'rrshare': function (param) {
            var rrShareParam = {
                url: options.url, // 默认为header中的Referer,如果分享失败可以调整此值为resourceUrl试试
                title: options.title,
                content: options.desc,
                image_src: options.pics
            };
            var submitUrl = 'http://www.connect.renren.com/sharer.do';
            this._openUrl(rrShareParam, submitUrl);
            return false;
        },

        // qq空间
        'qqshare': function (param) {
            var qqShareparam = {
                url: options.url,
                desc: options.desc,
                summary: '半叶寒羽 - 半叶寒羽', /* 分享摘要(可选) */
                title: options.title,
                site: '半叶寒羽', /* 分享来源 如：腾讯网(可选) */
                pics: options.pics
            };
            var submitUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey';
            this._openUrl(qqShareparam, submitUrl);
            return false;
        },

        // 腾讯微博
        'tqshare': function (param) {
            var tqShareparam = {
                c: 'share',
                a: 'index',
                url: options.url,
                desc: options.desc,
                title: options.title,
                site: '半叶寒羽', /* 分享来源 如：腾讯网(可选) */
                pic: options.pics[0], /* 分享图片的路径(可选) */
                appkey: '801242612'
            };
            var submitUrl = 'http://share.v.t.qq.com/index.php';
            this._openUrl(tqShareparam, submitUrl);
            return false;
        },

        // 淘宝
        'taoshare': function (param) {
            var tqShareparam = {
                url: location.href,
                desc: options.desc,
                title: options.title,
                site: '半叶寒羽', /* 分享来源 如：腾讯网(可选) */
                pics: options.pics, /* 分享图片的路径(可选) */
                appkey: '21180828'

            };
            var submitUrl = 'http://share.jianghu.taobao.com/share/addShare.htm';
            this._openUrl(tqShareparam, submitUrl);
            return false;
        },

        // 百度
        'bdshare': function (param) {
            var tqShareparam = {
                url: location.href,
                content: options.desc,
                title: options.title,
                linkid: 'yansueh',
                pics: options.pics, /* 分享图片的路径(可选) */
                appkey: ''
            };
            var submitUrl = 'http://hi.baidu.com/pub/show/share';
            this._openUrl(tqShareparam, submitUrl);
            return false;
        },

        // 豆瓣网
        'dbshare': function () {
            var tqShareparam = {
                href: options.url,
                desc: options.desc,
                name: options.title,
                linkid: 'looklo',
                image: options.pics[0],
                appkey: '0e1f49fc92bcafab2a5dddf5b8360f01'
            };
            var submitUrl = 'http://shuo.douban.com/!service/share';
            this._openUrl(tqShareparam, submitUrl);
            return false;
        },

        // 开心网
        'kxshare': function () {
            var shareparam = {
                url: options.url,
                content: options.desc,
                style: 11,
                time: new Date().valueOf(),
                sig: '',
                pic: options.pics
            };
            var submitUrl = 'http://www.kaixin001.com/rest/records.php';
            this._openUrl(shareparam, submitUrl);
            return false;
        },

        // 网易微博
        't163share': function () {
            var shareparam = {
                info: options.desc,
                source: options.url,
                images: options.pics
            };
            var submitUrl = 'http://t.163.com/article/user/checkLogin.do';
            this._openUrl(shareparam, submitUrl);
            return false;
        },

        // 搜狐微博
        'shshare': function () {
            var shareparam = {
                url: options.url,
                title: options.title,
                content: options.desc,
                pic: options.pics
            };
            var submitUrl = 'http://t.sohu.com/third/post.jsp';
            this._openUrl(shareparam, submitUrl);
            return false;
        },

        // 公用打开窗口
        '_openUrl': function (shareparam, submitUrl) {
            var temp = [];
            for (var p in shareparam) {
                temp
                    .push(p + '='
                        + encodeURIComponent(shareparam[p] || ''));
            }
            var url = submitUrl + "?" + temp.join('&');
            var wa = 'width=700,height=650,left=0,top=0,resizable=yes,scrollbars=1';
            window.open(url, 'looklo', wa);
        }

        // end
    };

    exports.share = function (param) {
        appShare.share.init(param);
    }
});
