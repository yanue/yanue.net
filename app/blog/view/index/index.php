<div class="content">
    <section class="left-section">
        <section class="section recommend">
            <h2 class="title">
                <span class="more"><a href="javascript:;" class="go-next">换一换</a></span>
                <strong class="name">置顶推荐</strong>
            </h2>

            <div class="list">
                <script>
                    seajs.use('app/home/main', function (m) {
                        m.recommend();
                    });
                </script>
                <ul class="bxslider">
                    <li>
                        <?php $this->render('index/recommend/row', false); ?>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </section>
        <section class="section latest">
            <h2 class="title">
                <span class="more">
                    <a href="<?php echo $this->baseUrl('post/list/p/2'); ?>">
                        more <i class="icon icon-angle-right"></i>
                    </a>
                </span>
                <strong class="name">最新发布</strong>
            </h2>

            <section class="list">
                <?php $this->render('index/latest/row', false); ?>
            </section>
        </section>
        <div class="load-more">
            <a href="<?php echo $this->baseUrl('post/list/p/2'); ?>">加载更多</a>
        </div>
    </section>

    <aside class="sidebar">
        <?php $this->render('side/panel', false); ?>
    </aside>
    <div class="clear"></div>

</div>
