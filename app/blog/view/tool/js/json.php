<section class="content">
    <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
        <small><i class="icon icon-angle-right"></i></small>
        <a href="<?php echo $this->baseUrl('tool'); ?>">前端工具箱</a>
        <small><i class="icon icon-angle-right"></i></small>
        JSON格式化及高亮美化工具
    </div>
    <h2 class="page-title">
        <a href="<?php echo $this->baseUrl('tool'); ?>" class="f60">前端工具箱</a>
        <span class="sub"> - JSON格式化及高亮美化工具</span>
        <i class="icon icon-paper-clip" title="使用Ctrl+D加入收藏"></i>
    </h2>

    <section class="container">
        <aside class="page-panel">
            <?php $this->render('tool/side', false); ?>
        </aside>
        <main class="page-content">
            <script type="text/javascript">
                seajs.use('format/json-format', function (f) {
                    f.init();
                    $(document).on('click', '#json-compress-btn', function () {
                        var code = $('#code-area').val();
                        formatCode = f.compress(code);
                        $('#code-area').val(formatCode)
                    })
                })

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
            <section class="tool-area">
                <h1 class="tool-title">
                    <span class="shareAll right"></span>

                    <i class="icon icon-hand-right"></i> JSON格式化及高亮美化工具
                </h1>
                <textarea class="code-area" id="code-area" style="height: 100px;"></textarea>

                <p class="tool-btn">
                    <input type="button" value="压　缩" class="btn btn-info" id="json-compress-btn"/>
                    <input type="button" value="格式化" class="btn btn-success" id="json-format-btn"/>
                    <span>缩进量</span>
                    <select id="TabSize">
                        <option value="2" selected="true">2</option>
                        <option value="4">4</option>
                        <option value="6">6</option>
                        <option value="8">8</option>
                    </select>
                    <span>
                      (请在上面的区域输入<mark>JSON</mark>代码)
                    </span>
                    <a href="javascript:;" class="SelectAllClicked" style="float: right;">全选结果</a>
                </p>
                <article class="tool-detail">
                    <div id="Canvas" class="Canvas"></div>
                </article>
            </section>

            <!--Duoshuo Comment BEGIN-->
            <div class="ds-thread">
            </div>
            <!--Duoshuo Comment END-->
        </main>
        <div class="clear"></div>
    </section>
</section>
