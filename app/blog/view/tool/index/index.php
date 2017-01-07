<section class="content">
    <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
        <small><i class="icon icon-angle-right"></i></small>
        <a href="<?php echo $this->baseUrl('tool'); ?>">前端工具箱</a>
    </div>
    <h2 class="page-title">
        <a href="<?php echo $this->baseUrl('tool'); ?>" class="f60">前端工具箱</a>
        <i class="icon icon-paper-clip" title="使用Ctrl+D加入收藏"></i>
    </h2>

    <section class="container">
        <aside class="page-panel">
            <?php $this->render('tool/side', false); ?>
        </aside>
        <main class="page-content">
            <h1 style="text-align: center;font-weight: normal;line-height: 60px;font-size: 20px;">前端工具箱</h1>

            <section class="box">
                <h2 class="box-title"><i class="icon icon-flag-alt"></i> 工具导航地图</h2>
                <ul class="box-list">
                    <li><strong>Css相关类： </strong> <a href="<?php echo $this->baseUrl('tool/css/format'); ?>"
                                                     class="red">Css美化/格式化</a>
                    </li>
                    <li><strong>JS相关类：</strong> <a
                            href="<?php echo $this->baseUrl('tool/js/format'); ?>">JS格式化美化压缩加密工具</a> -
                        <a href="<?php echo $this->baseUrl('tool/js/json') ?>" class="darkorange">JSON格式化工具</a></li>
                    <li><strong>HTML相关类：</strong> <a href="<?php echo $this->baseUrl('tool/html/format'); ?>">HTML格式化压缩工具</a>
                    </li>
                </ul>
                <h3 class="box-title" style="margin: 36px 0 12px;border-bottom: 1px dotted #E0E0E0;">
                    <span class="shareAll right"></span> <i class="icon icon-comments-alt"></i> 意见和建议
                </h3>
            </section>
            <script>
                seajs.use('app/app.share', function (api) {
                    var shareParas = {
                        'elem': ".shareAll",//分享dom
                        'pics': [""],//图片地址数组
                        'title': '<?php echo '前端工具箱 - '.$this->title; ?>',//分享标题
                        'desc': '<?php echo '前端工具箱 - '.$this->description; ?>' //分享描述
                    };
                    api.share(shareParas);
                });
            </script>
            <!-- Duoshuo Comment BEGIN -->
            <div class="ds-thread"></div>
            <!-- Duoshuo Comment END -->
        </main>
        <div class="clear"></div>
    </section>
</section>
