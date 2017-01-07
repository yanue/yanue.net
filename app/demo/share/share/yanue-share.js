/*
* 	 自定义分享功能
@author : yanue
@site : http://yanue.net/
@time : 2012.07.02

 //使用方法：
 shareParas = {
     'elem'   : "#aa",//分享dom
     'iconbg' : './share.png',// 分享按钮对于页面的路径
     'pics'   : '',// 图片地址数组[]
     'url'    : 'http://yanue.net/',// 自定义分享的地址为空则当前页面
     'title'  : '半叶寒羽 - yanue.net - 我的技术分享博客',
     'desc'   : '半叶寒羽 - yanue.net - 关注WEB前端 探索HTML5 深入php' //分享描述
 };
 new share(shareParas);
*/


function share(param){
    this.init(param);
}
share.options = {
    'title':'半叶寒羽 - yanue.net - 我的技术分享博客',
    'desc':'半叶寒羽-yanue.net - 关注WEB前端 探索HTML5 深入phpyanue.net - 关注WEB前端 探索HTML5 深入php,半叶寒羽，yanue，yanue.net，WEB前端，探索HTML5，深入php，经纬度查询，地址解析，geocode，gps，google map，原创作品，php mvc框架，linux , jquery',
    'url':'http://yanue.net/',
    'pics':'',
    'comment':'',
    'iconbg':''
};
share.prototype = {
    // 初始化
    'init' : function(param) {
        var _this = this;
        share.options.title = param.title || share.options.title;
        share.options.desc = param.desc || share.options.desc;
        share.options.url = param.url || share.options.url || location.href;
        share.options.pics = param.pics || share.options.pics;
        share.options.comment = param.comment || '';
        share.options.iconbg = param.iconbg || './share.png';

        var htm = '<em class="share_tip">分享到：</em>';
        htm += ' <a name="wbshare" class="share_icon" type="icon" id="wbshare" title="分享到新浪微博" href="javascript:;"></a>';
        htm += ' <a name="qqshare" class="share_icon" type="icon" id="qqshare" title="分享到qq社区" href="javascript:;"></a>';
        htm += ' <a name="tqshare" class="share_icon" type="icon" id="tqshare" title="分享到腾讯微博" href="javascript:;"></a>';
        htm += ' <a name="rrshare" class="share_icon" type="icon" id="rrshare" title="分享到人人网" href="javascript:;"></a>';
        htm += ' <a name="dbshare" class="share_icon" type="icon" id="dbshare" title="分享到豆瓣" href="javascript:;"></a>';
        htm += ' <a name="kxshare" class="share_icon" type="icon" id="kxshare" title="分享到开心网" href="javascript:;"></a>';
        htm += ' <a name="taoshare" class="share_icon" type="icon" id="taoshare" title="分享到淘宝网" href="javascript:;"></a>';
        htm += ' <a name="bdshare" class="share_icon" type="icon" id="bdshare" title="分享到百度空间" href="javascript:;"></a>';
        htm += ' <a name="t163share" class="share_icon" type="icon" id="t163share" title="分享到网易微博" href="javascript:;"></a>';
        htm += ' <a name="shshare" class="share_icon" type="icon" id="shshare" title="分享到搜狐" href="javascript:;"></a>';

        // 加载分享css
        css = this.initCss(share.options.iconbg);
        // 在页面中加入
        $(param.elem).html(css+htm);
        // 点击相应图片后 执行对应方法
        $(param.elem).find('a').live('click', function() {
            var obj = $(this).attr('name');
            eval("_this." + obj + "()");// eval妙用啊
        });
    },

    // css初始化
    initCss : function(iconbg) {
        var cssStr = "<style>";
        cssStr += 'a.share_icon{display:inline-block;width:20px;height:16px;background-image:url(' + iconbg + ');background-repeat: no-repeat;}';
        cssStr += '.share_tip{display:inline-block;width:48px;line-height:16px;height:16px;vertical-align:top;font-family:"微软雅黑";color:#666;margin:2px 0 0 0;font-style:normal;font-size:12px;}';
        cssStr += 'a#qqshare{background-position:0px -80px;}';
        cssStr += 'a#tqshare{background-position:0px -240px;}';
        cssStr += 'a#dbshare{background-position:0px -400px;}';
        cssStr += 'a#bdshare{background-position:0px -160px;}';
        cssStr += 'a#taoshare{background-position:0px -560px;}';
        cssStr += 'a#rrshare{background-position:0px -200px;}';
        cssStr += 'a#wbshare{background-position:0px -120px;}';
        cssStr += 'a#kxshare{background-position:0px -280px;}';
        cssStr += 'a#tieshare{background-position:0px -600px;}';
        cssStr += 'a#msnshare{background-position:0px -480px;}';
        cssStr += 'a#t163share{background-position:0px -720px;}';
        cssStr += 'a#shshare{background-position:0px -440px;}';
        cssStr += 'a#tyshare{background-position:0px -1000px;}';
        cssStr += "</style>";
        return cssStr;
    },

    // 新浪微博
    'wbshare' : function() {
        var submitUrl = 'http://service.weibo.com/share/share.php';
        var wbShareparam = {
            url : share.options.url,
            type : '3',
            appkey : '207042031',
            title : share.options.desc,
            pic : share.options.pics[0],
            ralateUid : '@looklo',
            language : 'zh-cn',
            rnd : new Date().valueOf()
        };
        this._openUrl(wbShareparam, submitUrl);
        return false;
    },

    // 人人网
    'rrshare' : function() {
        var rrShareParam = {
            url : share.options.url, // 默认为header中的Referer,如果分享失败可以调整此值为resourceUrl试试
            title : share.options.title,
            content : share.options.desc,
            image_src : share.options.pics
        };
        var submitUrl = 'http://www.connect.renren.com/sharer.do';
        this._openUrl(rrShareParam, submitUrl);
        return false;
    },

    // qq空间
    'qqshare' : function() {
        var qqShareparam = {
            url : share.options.url,
            desc : share.options.desc,
            summary : '空间说 - 中国家饰家装社区互动购物平台',/* 分享摘要(可选) */
            title : share.options.title,
            site : '空间说',/* 分享来源 如：腾讯网(可选) */
            pics : share.options.pics
        };
        var submitUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey';
        this._openUrl(qqShareparam, submitUrl);
        return false;
    },

    // 腾讯微博
    'tqshare' : function() {
        var tqShareparam = {
            c : 'share',
            a : 'index',
            url : share.options.url,
            desc : share.options.desc,
            title : share.options.title,
            site : '空间说',/* 分享来源 如：腾讯网(可选) */
            pic : share.options.pics[0], /* 分享图片的路径(可选) */
            appkey : '801242612'
        };
        var submitUrl = 'http://share.v.t.qq.com/index.php';
        this._openUrl(tqShareparam, submitUrl);
        return false;
    },

    // 淘宝
    'taoshare' : function() {
        var tqShareparam = {
            url : location.href,
            desc : share.options.desc,
            title : share.options.title,
            site : '空间说',/* 分享来源 如：腾讯网(可选) */
            pics : share.options.pics, /* 分享图片的路径(可选) */
            appkey : '21180828'

        };
        var submitUrl = 'http://share.jianghu.taobao.com/share/addShare.htm';
        this._openUrl(tqShareparam, submitUrl);
        return false;
    },

    // 百度
    'bdshare' : function() {
        var tqShareparam = {
            url : location.href,
            content : share.options.desc,
            title : share.options.title,
            linkid : 'yansueh',
            pics : share.options.pics, /* 分享图片的路径(可选) */
            appkey : ''
        };
        var submitUrl = 'http://hi.baidu.com/pub/show/share';
        this._openUrl(tqShareparam, submitUrl);
        return false;
    },

    // 豆瓣网
    'dbshare' : function() {
        var tqShareparam = {
            href : share.options.url,
            desc : share.options.desc,
            name : share.options.title,
            linkid : 'looklo',
            image : share.options.pics[0],
            appkey : '0e1f49fc92bcafab2a5dddf5b8360f01'
        };
        var submitUrl = 'http://shuo.douban.com/!service/share';
        this._openUrl(tqShareparam, submitUrl);
        return false;
    },

    // 开心网
    'kxshare' : function() {
        var shareparam = {
            url : share.options.url,
            content : share.options.desc,
            style : 11,
            time : new Date().valueOf(),
            sig : '',
            pic : share.options.pics
        };
        var submitUrl = 'http://www.kaixin001.com/rest/records.php';
        this._openUrl(shareparam, submitUrl);
        return false;
    },

    // 网易微博
    't163share' : function() {
        var shareparam = {
            info : share.options.desc,
            source : share.options.url,
            images : share.options.pics
        };
        var submitUrl = 'http://t.163.com/article/user/checkLogin.do';
        this._openUrl(shareparam, submitUrl);
        return false;
    },

    // 搜狐微博
    'shshare' : function() {
        var shareparam = {
            url : share.options.url,
            title : share.options.title,
            content : share.options.desc,
            pic : share.options.pics
        };
        var submitUrl = 'http://t.sohu.com/third/post.jsp';
        this._openUrl(shareparam, submitUrl);
        return false;
    },

    // 公用打开窗口
    '_openUrl' : function(shareparam, submitUrl) {
        var temp = [];
        for ( var p in shareparam) {
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