<div class="widget suggest">
    <strong>站长推荐</strong>

    <h2>地图作品</h2>

    <p>
        <a href="<?php echo $this->suburl('map', '', false); ?>">经纬度批量查询工具</a>
        <a href="<?php echo $this->suburl('map', 'gps'); ?>">GPS经纬度转换接口</a>
        <a href="<?php echo $this->suburl('map', 'toLatLng', false); ?>">地名查询经纬度</a>
    </p>

    <h2>前端工具箱</h2>

    <p>
        <a href="<?php echo $this->baseUrl('tool/css/format'); ?>">CSS格式化美化</a>
        <a href="<?php echo $this->baseUrl('tool/js/format'); ?>">JS格式化压缩加密</a>
        <a href="<?php echo $this->baseUrl('tool/js/json'); ?>">JSON格式化及高亮美化</a>
    </p>

    <p class="line"></p>

</div>
<section class="ranking widget">
    <h2 class="tab-switch">
        <a href="javascript:;" data-tab="popular" class="tab current">热门文章</a>
        <a href="javascript:;" data-tab="comments" class="tab">评论排行</a>
        <a href="javascript:;" data-tab="latest" class="tab">最新发布</a>
    </h2>

    <script>
        seajs.use('app/home/main', function (m) {
            m.tabSwitch();
        });
    </script>
    <div class="list tab-post-list">
        <ul class="tab-content" data-tab="popular" style="">
            <?php $this->render('side/tab/popular', false); ?>
        </ul>

        <ul class="tab-content" data-tab="comments" style="display: none;">
            <?php $this->render('side/tab/comments', false); ?>
        </ul>

        <ul class="tab-content" data-tab="latest" style="display: none;">
            <?php $this->render('side/tab/latest', false); ?>
        </ul>
    </div>
</section>

<section class="links widget">
    <h2 class="title">
        <strong class="name">友情链接</strong>
        <span class="more"><a href="<?php echo $this->baseUrl('links'); ?>">more <i
                    class="icon icon-angle-right"></i></a></span>
    </h2>

    <div class="list">
        <div class="tags">
            <?php $this->render('side/links', false); ?>
        </div>
    </div>
</section>

<section class="cats widget">
    <h2 class="title">
        <strong class="name">分类目录</strong>
        <span class="more"><a href="<?php echo $this->baseUrl('category'); ?>">more <i
                    class="icon icon-angle-right"></i></a></span>
    </h2>

    <div class="list">
        <div class="cat-list">
            <?php $this->render('side/cats', false); ?>
            <div class="clear"></div>
        </div>
    </div>
</section>

<section class="tags-cloud widget">
    <h2 class="title">
        <strong class="name">热门标签</strong>
        <span class="more"><a href="<?php echo $this->baseUrl('tags'); ?>">more <i
                    class="icon icon-angle-right"></i></a></span>
    </h2>

    <div class="list">
        <div class="tags">
            <?php $this->render('side/tags', false); ?>
        </div>
    </div>
</section>


<?php if (false) { ?>
    <section class="comment widget">
        <h2 class="title">
            <strong class="name">最新评论</strong>
        </h2>
        <ul class="ds-recent-comments list" data-num-items="6" data-show-avatars="1" data-show-time="1"
            data-show-admin="1" data-excerpt-length="43"></ul>
    </section>
<?php } ?>

<section class="widget visitors">
    <h2 class="title">
        <strong class="name">最近访客</strong>
    </h2>
    <ul class="ds-recent-visitors list" data-num-items="16"></ul>
</section>