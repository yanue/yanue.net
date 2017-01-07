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
        <section class="left-top-ad">
            <script type="text/javascript">
                var cpro_id="u2128929";
                (window["cproStyleApi"] = window["cproStyleApi"] || {})[cpro_id]={at:"3",rsi0:"828",rsi1:"100",pat:"6",tn:"baiduCustNativeAD",rss1:"#FFFFFF",conBW:"1",adp:"1",ptt:"0",titFF:"%E5%BE%AE%E8%BD%AF%E9%9B%85%E9%BB%91",titFS:"",rss2:"#000000",titSU:"0",ptbg:"90",piw:"0",pih:"0",ptp:"0"}
            </script>
            <script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
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
