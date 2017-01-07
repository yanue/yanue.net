<?php
use App\Blog\Helper\SideData;

$tags = SideData::getAllCats();
?>
<div class="content">
    <section class="left-section">
        <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
            <small><i class="icon icon-angle-right"></i></small>
            网站类目
        </div>
        <article class="post-detail">
            <header class="post-info">
                <h1 class="post-title">
                    <i class="icon icon-paper-clip"></i>
                    <a href="">网站类目</a>

                </h1>

                <div class="meta muted">
                    <p class="" style="margin: 0 0 12px;"></p>

                    <p>
                        <script>
                            shareParas = {
                                'elem': ".shareAll",//分享dom
                                'pics': [""],//图片地址数组
                                'title': '文章标签 - 半叶寒雨',//分享标题
                                'desc': '文章标签 - 半叶寒雨' //分享描述
                            };
                            seajs.use('app/app.share', function (api) {
                                api.share(shareParas);
                            });
                        </script>
                        <span id="" class="shareAll right"></span>
                        <span title="文章数量">
                            <i class="icon icon-bar-chart"></i> <?php echo count($tags); ?> 个分类
                        </span>
                    </p>
                </div>
            </header>
        </article>
        <section class="section ">
            <h2 class="title">
                <strong class="name">分类列表</strong>
            </h2>

            <section class="list" style="padding: 20px;line-height: 160%;">
                <ul>
                    <?php
                    if ($tags) {
                        foreach ($tags as $tag) {
                            if ($tag['is_parent'] == 0 && $tag['parent_id'] == 0) {
                                ?>
                                <li>
                                    <b><a href="<?php echo $this->baseUrl('tag/' . urlencode($tag['name'])); ?>"><?php echo $tag['name']; ?></a></b>
                                </li>

                                <?php
                                foreach ($tags as $tag2) {
                                    if ($tag2['parent_id'] == $tag['id']) {
                                        ?>
                                        <li>
                                            |-- <a
                                                href="<?php echo $this->baseUrl('tag/' . urlencode($tag2['name'])); ?>"><?php echo $tag2['name']; ?></a>
                                        </li>
                                    <?php
                                    }
                                }
                                ?>
                            <?php
                            }
                        }
                    }
                    ?>
                </ul>
            </section>
        </section>
    </section>
    <aside class="sidebar">
        <?php $this->render('side/panel', false); ?>
    </aside>
    <div class="clear"></div>
</div>