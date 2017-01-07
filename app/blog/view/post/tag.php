<?php
$tag = $this->cat;
?>
<div class="content">
    <section class="left-section">
        <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
            <small><i class="icon icon-angle-right"></i></small>
            <a href="<?php echo $this->baseUrl('tag/' . ($tag['name'] ? $tag['name'] : $tag['id'])); ?>"
               title="<?php echo $tag['name']; ?>"><?php echo $tag['name']; ?></a>
        </div>
        <article class="post-detail">
            <header class="post-info">
                <h1 class="post-title">
                    <i class="icon icon-paper-clip"></i>
                    <a href="<?php echo $this->baseUrl('tag/' . $tag['name']); ?>"><?php echo $tag['name']; ?></a>
                </h1>

                <div class="meta muted">

                    <p>
                        <script>
                            shareParas = {
                                'elem': ".shareAll",//分享dom
                                'pics': [""],//图片地址数组
                                'title': '<?php echo $tag['name']; ?>',//分享标题
                                'desc': '<?php echo $tag['detail']; ?>' //分享描述
                            };
                            seajs.use('app/app.share', function (api) {
                                api.share(shareParas);
                            });
                        </script>
                        <span id="" class="shareAll right"></span>
                        <span title="文章数量">
                            <i class="icon icon-bar-chart"></i> <?php echo $this->pageCount; ?> 篇文章
                        </span>
                    </p>
                </div>
            </header>
        </article>
        <section class="section ">
            <h2 class="title">
                <strong class="name">文章列表</strong>
            </h2>

            <section class="list">
                <?php $this->render('post/list/row', false); ?>
            </section>
        </section>
        <section class="pagination section">
            <?php \Library\Util\Pagination::displayByRouter($this); ?>
        </section>
    </section>
    <aside class="sidebar">
        <?php $this->render('side/panel', false); ?>
    </aside>
    <div class="clear"></div>
</div>