<!DOCTYPE html>
<html>
<?php
use App\Map\Helper\StaticFileManager;
use Library\Core\Config;

?>
<head>
    <meta charset="utf-8"/>
    <title><?php echo isset($this->title) ? $this->title . ' - 半叶寒羽‧地图作品' : '半叶寒羽‧地图作品'; ?></title>
    <meta name='author' content='yanue'/>
    <meta name='copyright' content='map.yanue.net'/>
    <meta name="keywords"
          content="<?php echo isset($this->keywords) ? $this->keywords : '半叶寒羽‧地图作品'; ?>"/>
    <meta name="description"
          content="<?php echo isset($this->description) ? $this->description : '半叶寒羽‧地图作品'; ?>"/>

    <?php
    if ($this->controller == 'post' && $this->action = 'detail') {
        $id = $this->uri->getParam('id');
        if ($id) {
            ?>
            <link rel='canonical' href='<?php echo $this->baseUrl('post-' . $id); ?>'/>
        <?php
        }
    }
    ?>
        <link rel="stylesheet" type="text/css"
          href="https://<?php echo Config::getItem('domain.src'); ?>/css/base.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://<?php echo Config::getItem('domain.src'); ?>/css/map/content.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://<?php echo Config::getItem('domain.src'); ?>/css/font-awesome.min.css"/>
    <?php
    echo StaticFileManager::outputCss();
    ?>
    <!--[if lt IE 9]>
    <script src="https://<?php echo Config::getItem('domain.src'); ?>/js/html5shiv.js')"
            type="text/javascript"></script>
    <![endif]-->
    <script type="text/javascript" src="https://<?php echo Config::getItem('domain.src'); ?>/js/sea.js"></script>
    <script>
        seajs.config({
            base: 'https://<?php echo Config::getItem('domain.src'); ?>/js',
            charset: 'utf-8',
            timeout: 30000,
            alias: {
                'jquery': "jquery/jquery.last.js"
            },
            preload: ["jquery"]
        });

        var app = app || {};
        app.site_url = '<?php echo $this->baseUrl('',false); ?>';
        app._CUID = '<?php echo \Library\Util\Session::get('_CUID'); ?>';
        app._CUSR = '<?php echo \Library\Util\Session::get('_CUSR'); ?>';
        app._CONFIG = {
            domain: {
                'src': '<?php echo Config::getItem('domain.src'); ?>'
            }
        };
        seajs.use('app/app.imgReady', function () {
            $('img[data-rel="imgReady"]').each(function () {
                var img = $(this).attr('data-src');
                if (img) {
                    var _this = this;
                    imgReady(img, function () {
                        var w_width = $(_this).attr('data-width');
                        var w_height = $(_this).attr('data-height');
                        var css = resize_ready_img(w_width, w_height, this.width, this.height);
                        // 重置窗口并显示
                        $(_this).attr('src', img).css($.extend({}, css, {'display': 'block'})).removeAttr('data-src');
                    }, function () {

                    }, function () {

                    });
                }
            });
        });
    </script>
    <?php
    echo StaticFileManager::outputJs();
    ?>
    <script type="text/javascript">
        var duoshuoQuery = {short_name: "yanuemap"};
        (function () {
            var ds = document.createElement('script');
            ds.type = 'text/javascript';
            ds.async = true;
            ds.src = 'https://static.duoshuo.com/embed.js';
            ds.charset = 'UTF-8';
            (document.getElementsByTagName('head')[0]
                || document.getElementsByTagName('body')[0]).appendChild(ds);
        })();
    </script>
</head>
<body>
<?php $this->render('header', false); ?>
<main class="wrap">
    <?php $this->content(); ?>
</main>
<?php $this->render('footer', false); ?>