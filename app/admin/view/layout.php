<?php
use Library\Core\Config;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>后台管理中心 - 半叶寒羽</title>
    <meta name="robots" content="noindex,nofollow"/>
    <meta name='author' content='yanue'/>
    <meta name='copyright' content=''/>
    <!--[if lt IE 9]>
    <script src="http://<?php echo Config::getItem('domain.src'); ?>/scripts/html5shiv.js')"
            type="text/javascript"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css"
          href="http://<?php echo Config::getItem('domain.src'); ?>/css/admin/base.css"/>
    <link rel="stylesheet" type="text/css"
          href="http://<?php echo Config::getItem('domain.src'); ?>/css/admin/content.css"/>

    <script type="text/javascript" src="http://<?php echo Config::getItem('domain.src'); ?>/js/sea.js"></script>
    <script>
        seajs.config({
            base: 'http://<?php echo Config::getItem('domain.src'); ?>/js',
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
    </script>
</head>
<body>
<!-- 头部区域 -->
<?php
$this->render('header', false);
?>
<!-- 内容区域 -->
<section id="main">
    <?php
    $this->render('sidebar', false);
    $this->render('showpath', false);
    ?>
    <section id="content">
        <?php $this->content(); ?>
    </section>
    <div class="clear">
    </div>
</section>
<div class="clear">
</div>
<!-- 底部区域 -->
<?php
$this->render('footer', false);
$this->render('ajaxStatus', false);
?>
</body>
</html>
