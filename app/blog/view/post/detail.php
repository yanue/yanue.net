<?php
$item = $this->item;
$category = $item['category'];

?>
<div class="content">
    <section class="left-section">
        <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
            <small><i class="icon icon-angle-right"></i></small>
            <a href="<?php echo $this->baseUrl('topic/' . ($category['alias'] ? $category['alias'] : $category['id'])); ?>"
               title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a>
            <small><i class="icon icon-angle-right"></i></small>
            <span class="muted"><?php echo $item['title']; ?></span></div>
        <article class="post-detail">
            <header class="post-info">
                <h1 class="post-title">
                    <?php if ($this->admin_uid > 0) { ?>
                        <span class="right">
                                        <a class="edit"
                                           href="<?php echo $this->suburl('admin', 'post/update/id/' . $item['id']); ?>">编辑</a>
                                    </span>
                    <?php } ?>
                    <i class="icon icon-paper-clip" title="使用Ctrl+D加入收藏"></i>

                    <i class="circle">文</i> <a
                        href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"><?php echo $item['title']; ?></a>
                </h1>

                <div class="meta muted">
                    <span id="" class="shareAll right"></span>

                    <span>
                        <i class="icon icon-time"></i>
                        发表于 <?php echo \Library\Util\Time::formatHumaneTime($item['created']); ?>
                    </span>
                    - <span>
                      <a
                          href="<?php echo $this->baseUrl('post-' . $item['id'] . ''); ?>#comments"><i
                              class="icon icon-comments"></i> <?php echo $item['comments']; ?>条评论</a>
                    </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span title="浏览次数">
                        <i class="icon icon-bar-chart"></i> <?php echo $item['views']; ?> 次浏览
                    </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>
                        所属分类：<a
                            href="<?php echo $this->baseUrl('topic/' . ($category['alias'] ? $category['alias'] : $category['id'])); ?>"
                            title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a>
                    </span>
                </div>
            </header>
            <main class="post-content">
                <?php echo $item['sub_title'] ? '<blockquote>' . $item['sub_title'] . '</blockquote>' : ''; ?>
                <?php echo $item['content']; ?>
                <div class="copy">
                    转载请注明：<a href="<?php echo $this->baseUrl(''); ?>" title="半叶寒羽">半叶寒羽</a>
                    » <a href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
                         title="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></a>
                </div>
            </main>

            <footer class="post-footer">
                <span id="shareAll" class="shareAll right"></span>

                <div class="post-tags">继续浏览有关
                    <?php
                    $tags = json_decode($item['tags'], true);
                    if ($tags) {
                        foreach ($tags as $tag) {
                            ?>
                            <a href="<?php echo $this->baseUrl('tag/' . urlencode($tag['name'])); ?>"
                               rel="tag"><?php echo $tag['name']; ?></a>
                        <?php
                        }
                    } ?>
                </div>

            </footer>
            <nav class="post-nav">
                <?php $this->render('post/post-nav', false); ?>
                <div class="clear"></div>
            </nav>
            <section class="post-related">
                <h3 class="related-title">你可能还会对下面的内容感兴趣：</h3>
                <ul class="related-list">
                    <?php $this->render('post/relate/row', false); ?>
                </ul>
            </section>

            <div class="detail-ad">
                <script type="text/javascript">
                    var cpro_id="u2128929";
                    (window["cproStyleApi"] = window["cproStyleApi"] || {})[cpro_id]={at:"3",rsi0:"804",rsi1:"300",pat:"6",tn:"baiduCustNativeAD",rss1:"#FFFFFF",conBW:"1",adp:"1",ptt:"0",titFF:"%E5%BE%AE%E8%BD%AF%E9%9B%85%E9%BB%91",titFS:"",rss2:"#000000",titSU:"0",ptbg:"90",piw:"0",pih:"0",ptp:"0"}
                </script>
                <script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
            </div>
            <nav class="post-nav">
                <i class="circle">评</i> 评论内容<a name="comments"></a>
            </nav>
            <!-- Duoshuo Comment BEGIN -->
            <section class="comment-area">
                <div class="ds-thread" data-thread-key="<?php echo $item['id']; ?>"
                     data-title="<?php echo $item['title']; ?>"></div>
            </section>
            <!-- Duoshuo Comment END -->
        </article>
    </section>

    <aside class="sidebar">
        <?php $this->render('side/panel', false); ?>
    </aside>
    <div class="clear"></div>
</div>

<script>
    seajs.use('app/home/main', function (api) {
        api.prettfiy();
    });

    seajs.use(['jquery/jquery.last', 'jquery/jquery.fullscreengallery'], function () {
        $('.post-content img').fullScreenGallery();
    });

    seajs.use('app/app.share', function (api) {
        var shareParas = {
            'elem': ".shareAll",//分享dom
            'pics': ["<?php echo $item['cover_img']; ?>"],//图片地址数组
            'title': '<?php echo $item['title']; ?>',//分享标题
            'desc': '<?php echo preg_replace('/\s/',' ',addslashes (mb_substr(strip_tags($item['content']),0,120,'utf-8'))); ?>' //分享描述
        };
        api.share(shareParas);
    });
</script>

<!-- pic view -->
<main id="picView-overlay">
    <header id="overlay-header">[<?php echo $item['title']; ?>] 文章图片</header>
    <section id="picView-imgViewBox"></section>
    <a id="picView-close" class="picView-btn"></a>
    <a id="picView-next" class="picView-btn go-btn"></a>
    <a id="picView-prev" class="picView-btn go-btn"></a>
    <footer id="overlay-footer">
        <a id="zoomOut" class="zoom picView-btn" href="javascript:;"></a>
        <a id="zoomIn" class="zoom picView-btn" href="javascript:;"></a>
        <section id="count">
            <span id="current"></span><span id="all"></span>
        </section>
        <span class="right "
              style="margin: 0 20px 0 ;"><?php echo \Library\Util\Time::formatHumaneTime($item['created']); ?></span>
    </footer>
    <article id="overlay-panel">
        <h2 class="panel-title"><i class="circle">文</i><?php echo $item['title']; ?></h2>

        <p class="panel-meta">
            <span>
            <i class="icon icon-time"></i>
                <?php echo \Library\Util\Time::formatHumaneTime($item['created']); ?>
            </span>
            - <span>
            <a
                href="<?php echo $this->baseUrl('post-' . $item['id'] . ''); ?>#comments"><i
                    class="icon icon-comments"></i> <?php echo $item['comments']; ?>条评论</a>
            </span>
            <span title="浏览次数">
            浏览 <?php echo $item['views']; ?> 次
            </span>
        </p>

        <div class="comment-wrap"></div>
    </article>
</main>
