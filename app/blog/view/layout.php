<!DOCTYPE html>
<html>
<?php

use Library\Core\Config;

?>
<head>
    <meta charset="utf-8"/>
    <title><?php echo isset($this->title) ? $this->title : '半叶寒羽 ‧ 专注PHP+HTML5技术'; ?></title>
    <meta name='author' content='yanue'/>
    <meta name='copyright' content='yanue.net'/>
    <meta name="keywords"
          content="<?php echo isset($this->keywords) ? $this->keywords : '半叶寒羽 ‧ 专注PHP+HTML5技术'; ?>"/>
    <meta name="description"
          content="<?php echo isset($this->description) ? $this->description : '半叶寒羽 ‧ 专注PHP+HTML5技术'; ?>"/>
    <meta name="domain_verify"
          content="pmrgi33nmfuw4ir2ej4wc3tvmuxg4zlueiwcez3vnfsceorcgmytgnbsmzrtmyrxguzdiyzqhfrdeyjumvrtgmlfhazwkolfgbscelbcoruw2zktmf3gkir2ge2tcnbyg42donbwgi4dm7i">
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
          href="https://<?php echo Config::getItem('domain.src'); ?>/css/yanue.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://<?php echo Config::getItem('domain.src'); ?>/css/font-awesome.min.css"/>
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
        app.site_url = '<?php echo $this->baseUrl('', false); ?>';
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
</head>
<body>

<?php $this->render('header', false); ?>
<main class="wrap">
    <?php $this->content(); ?>
</main>
<?php $this->render('footer', false); ?>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?a37fc5942ae6900185918d0920950509";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>
</body>
</html>
