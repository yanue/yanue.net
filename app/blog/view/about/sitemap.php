<?php
use App\Blog\Helper\SideData;

?>
<section class="content">
    <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
        <small><i class="icon icon-angle-right"></i></small>
        关于
        <small><i class="icon icon-angle-right"></i></small>
        网站地图
    </div>
    <h1 class="page-title">网站地图</h1>

    <section class="container">
        <aside class="page-panel">
            <?php $this->render('about/side', false); ?>
        </aside>
        <main class="page-content">
            <div class="content-area">
                <div class="widget suggest">
                    <strong>站长推荐</strong>

                    <h2>前端工具箱</h2>

                    <p>
                        <a href="http://yanue.net/tool/css/format.html">CSS格式化美化</a>
                        <a href="http://yanue.net/tool/js/format.html">JS格式化压缩加密</a>
                        <a href="http://yanue.net/tool/js/json.html">JSON格式化及高亮美化</a>
                    </p>

                    <p class="line"></p>

                    <h2>地图作品</h2>

                    <p>
                        <a href="http://map.yanue.net/gps.html">GPS经纬度转换接口</a>
                        <a href="http://map.yanue.net">经纬度查询工具</a>
                        <a href="http://map.yanue.net/toLatLng">查询地名经纬度</a>
                    </p>
                </div>

                <div class="widget suggest" style="margin: 10px 0 0 0;">
                    <strong>博客栏目导航</strong>
                    <?php
                    $tags = SideData::getAllCats();
                    if ($tags) {
                        foreach ($tags as $k => $tag) {
                            if ($tag['parent_id'] == 0) {
                                ?>
                                <h2>
                                    <a href="<?php echo $this->baseUrl('topic/' . ($tag['alias'] ? $tag['alias'] : $tag['id'])); ?>"><?php echo $tag['name']; ?></a>
                                </h2>

                                <p>
                                    <?php
                                    foreach ($tags as $k2 => $tag2) {
                                        if ($tag2['parent_id'] == $tag['id']) {
                                            ?>
                                            <a href="<?php echo $this->baseUrl('post/topic/' . $tag2['id']); ?>"><?php echo $tag2['name'] ?></a>
                                        <?php } ?>
                                    <?php
                                    }
                                    ?>
                                </p>
                            <?php
                            }
                        }
                    }
                    ?>
                </div>

                <!-- Duoshuo Comment BEGIN -->
                <section class="comment-area">
                    <div class="ds-thread" data-title="网站地图"></div>
                </section>
        </main>
        <div class="clear"></div>
    </section>
</section>