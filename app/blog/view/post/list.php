<?php
$cats = $this->cats;
?>
<div class="content">
    <section class="left-section">
        <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
            <small><i class="icon icon-angle-right"></i></small>
            文章列表
        </div>
        <article class="post-detail">
            <header class="post-info">
                <h1 class="post-title">
                    <i class="icon icon-paper-clip"></i>

                </h1>

                <div class="meta muted">
                    <p class="">
                        分类检索：

                        <?php
                        foreach ($cats as $cat) {
                            ?>
                            <a href="<?php echo $this->baseUrl('topic/' . ($cat['alias'] ? $cat['alias'] : $cat['id'])); ?>"
                               title="<?php echo $cat['name']; ?>"><?php echo $cat['name']; ?></a>
                        <?php
                        }
                        ?>
                    </p>

                    <p>
                        <span title="文章数量">
                        </span>
                    </p>

                    <p>

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
            <?php \Library\Util\Pagination::display($this); ?>
        </section>
    </section>
    <aside class="sidebar">
        <?php $this->render('side/panel', false); ?>
    </aside>
    <div class="clear"></div>
</div>