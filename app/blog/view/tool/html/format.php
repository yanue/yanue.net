<section class="content">
    <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
        <small><i class="icon icon-angle-right"></i></small>
        <a href="<?php echo $this->baseUrl('tool'); ?>">前端工具箱</a>
        <small><i class="icon icon-angle-right"></i></small>
        Html格式化美化压缩工具
    </div>
    <h2 class="page-title">
        <a href="<?php echo $this->baseUrl('tool'); ?>" class="f60">前端工具箱</a>
        <span class="sub"> - Html格式化美化压缩工具</span>
        <i class="icon icon-paper-clip" title="使用Ctrl+D加入收藏"></i>
    </h2>

    <section class="container">
        <aside class="page-panel">
            <?php $this->render('tool/side', false); ?>
        </aside>
        <main class="page-content">
            <section class="tool-area">
                <script type="text/javascript">
                    seajs.use('format/html-format', function (t) {
                        $(document).on('click', '#html-format-btn',
                            function () {
                                var code = $('#code-area').val();
                                formatCode = t.style_html(code);
                                $('#code-area').val(formatCode)
                            });
                        $(document).on('click', '#html-compress-btn',
                            function () {
                                var code = $('#code-area').val();
                                formatCode = t.compress(code);
                                $('#code-area').val(formatCode)
                            })
                    });


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

                <h1 class="tool-title">
                    <span class="shareAll right"></span>

                    <i class="icon icon-hand-right"></i> Html格式化美化压缩工具
                </h1>
                <textarea class="code-area" id="code-area" style="height: 300px;"></textarea>

                <p class="tool-btn">


                    <input type="button" value="格式化" class="btn btn-success" id="html-format-btn"/>
                    <input type="button" value="压　缩" class="btn btn-info" id="html-compress-btn"/>
                    <span>
                      (请在上面的区域输入<mark>html</mark>代码)
                    </span>
                </p>
            </section>

            <!--Duoshuo Comment BEGIN-->
            <div class="ds-thread"></div>
            <!--Duoshuo Comment END-->
        </main>
        <div class="clear"></div>
    </section>
</section>
