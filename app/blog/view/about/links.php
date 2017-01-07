<section class="content">
    <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
        <small><i class="icon icon-angle-right"></i></small>
        关于
        <small><i class="icon icon-angle-right"></i></small>
        友情链接
    </div>
    <h1 class="page-title">友情链接</h1>

    <section class="container">
        <aside class="page-panel">
            <?php $this->render('about/side', false); ?>
        </aside>
        <main class="page-content">
            <div class="widget suggest" style="margin: 10px 0 0 0;">
                <strong>友情链接</strong>

                <h2>
                    GPS
                </h2>

                <p>
                    <a href="http://www.gpsspg.com/" target="_blank">GPSspg — 探索地区，分享信息！</a>
                </p>

                <h2>
                    技术博客
                </h2>

                <p>
                    <a href="http://www.vkilo.com/" target="_blank">怪叔叔的博客</a>
                </p>
            </div>

            <!-- Duoshuo Comment BEGIN -->
            <section class="comment-area">
                <div class="ds-thread"
                     data-title="友情链接"></div>
            </section>
        </main>
        <div class="clear"></div>
    </section>
</section>