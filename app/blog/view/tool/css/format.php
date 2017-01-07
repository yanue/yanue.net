<section class="content">
    <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
        <small><i class="icon icon-angle-right"></i></small>
        <a href="<?php echo $this->baseUrl('tool'); ?>">前端工具箱</a>
        <small><i class="icon icon-angle-right"></i></small>
        CSS格式化美化压缩工具
    </div>
    <h2 class="page-title">
        <a href="<?php echo $this->baseUrl('tool'); ?>" class="f60">前端工具箱</a>
        <span class="sub"> - CSS格式化美化压缩工具</span>
        <i class="icon icon-paper-clip" title="使用Ctrl+D加入收藏"></i>
    </h2>

    <section class="container">
        <aside class="page-panel">
            <?php $this->render('tool/side', false); ?>
        </aside>
        <main class="page-content">
            <section class="tool-area">
                <script type="text/javascript">
                    seajs.use('format/css-format', function (t) {
                        // 横排
                        $(document).on('click', '.css-horizontal-btn', function () {
                            var code = $('#code-area').val();
                            formatCode = t.toHorizontal(code);
                            $('#code-area').val(formatCode);
                        });
                        // 竖排
                        $(document).on('click', '.css-vertical-btn', function () {
                            var code = $('#code-area').val();
                            formatCode = t.toVertical(code);
                            $('#code-area').val(formatCode);
                        });
                        // 解压
                        $(document).on('click', '.css-format-btn', function () {
                            var code = $('#code-area').val();
                            formatCode = t.format(code);
                            $('#code-area').val(formatCode);
                        });
                        // 压缩
                        $(document).on('click', '.css-pack-btn', function () {
                            var code = $('#code-area').val();
                            formatCode = t.pack(code);
                            $('#code-area').val(formatCode);
                        });
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

                    <i class="icon icon-hand-right"></i> CSS格式化美化压缩工具
                </h1>

                <div>
                    <textarea class="code-area" id="code-area" style="height: 300px;"></textarea>
                </div>

                <p class="tool-btn">
                    <input type="button" value="横排" class="btn btn-warning css-horizontal-btn"/>
                    <input type="button" value="竖排" class="btn btn-info css-vertical-btn"/>
                    <input type="button" value="解压" class="btn btn-success css-format-btn"/>
                    <input type="button" value="压缩" class="btn btn-danger css-pack-btn"/>
                    <span>
                        (请在上面的区域输入<mark>css</mark>代码)
                    </span>
                </p>
            </section>

            <!-- Duoshuo Comment BEGIN -->
            <div class="ds-thread"></div>
            <!-- Duoshuo Comment END -->
        </main>
        <div class="clear"></div>
    </section>
</section>



