<?php
use Library\Core\Config;

$cats = \App\Blog\Helper\SideData::getAllCats();
?>
<header class="header">
    <script>
        seajs.use('app/home/main', function (m) {
            m.topNav();
            m.showSubCat();
        });
    </script>
    <div class="top">
        <div class="wrap">
            <span class="right">
                <a href="<?php echo $this->baseUrl('sitemap'); ?>">网站地图</a>
                |
                <a href="<?php echo $this->baseUrl('about'); ?>">关于本站</a>
            </span>
            <ul class="top-nav">
                <li><a href="<?php echo $this->baseUrl(''); ?>">博客首页</a></li>
                <li><a href="<?php echo $this->baseUrl('tool'); ?>">前端工具箱</a></li>
                <!--                <li><a href="">在线手册</a></li>-->
                <!--                <li><a href="">各种查询</a></li>-->
                <li>
                    <h4><a href="<?php echo $this->suburl('map', '', false); ?>" class="top-alt" data-sub="map">地图作品 <i
                                class="icon arrow  icon-angle-down"></i></a></h4>

                    <p class="sub-nav" data-sub="map">
                        <a href="<?php echo $this->suburl('map', 'gps'); ?>">GPS经纬度转换</a>
                        <a href="<?php echo $this->suburl('map', '', false); ?>">经纬度查询工具</a>
                        <a href="<?php echo $this->suburl('map', 'toLatLng', false); ?>">查询地名经纬度</a>
                    </p>
                </li>
            </ul>
        </div>
    </div>
    <section class="wrap nav">
        <div class="site">
            <a href="<?php echo $this->baseUrl(''); ?>" class="logo"><img
                    src="https://<?php echo Config::getItem('domain.src'); ?>/images/logo.png"
                    alt=""/></a>

            <div class="sub-site">
                <a href="" class="site-name">地图作品</a>
                <span>GPS 经纬度转换 地理解析</span>
            </div>
        </div>
        <nav class="menu">
            <div class="main-cat">
                <?php
                if ($cats) {

                    foreach ($cats as $k => $cat) {
                        $current = '';
                        $block = '';
                        if ($cat['parent_id'] == 0) {
                            if ($this->controller == 'post' && $this->action == 'cat') {
                                //
                                if ($parent_cat = $this->parent_cat) {
                                    if ($parent_cat['id'] == $cat['id']) {
                                        $current = 'current';
                                        $block = 'display:block';
                                    }
                                }

                                $var = $this->uri->getParam('var');

                                if ($var == $cat['id'] || $var == $cat['alias']) {
                                    $current = 'current';
                                    $block = 'display:block';
                                }
                            } else {
                                $current = $k === 0 ? ' current' : '';
                                $block = $k === 0 ? 'display:block' : '';
                            }
                            ?>
                            <a href="<?php echo $this->baseUrl('topic/' . ($cat['alias'] ? $cat['alias'] : $cat['id'])); ?>"
                               class="showSub <?php echo $current; ?>"
                               data-rel="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?>
                                <em class="arrow" style="<?php echo $block ?>"></em>
                            </a>
                        <?php
                        }
                    }
                }
                ?>
                <div class="clear"></div>
            </div>

            <p class="sub-cat">
                <?php
                if ($cats) {
                    foreach ($cats as $n => $v) {
                        $display = '';
                        if ($v['parent_id'] == 0) {
                            if ($this->controller == 'post' && $this->action == 'cat') {
                                $var = $this->uri->getParam('var');

                                if ($var == $v['id'] || $var == $v['alias']) {
                                    $display = 'display:block';
                                }
                                if ($parent_cat = $this->parent_cat) {
                                    if ($parent_cat['id'] == $v['id']) {
                                        $display = 'display:block';
                                    }
                                }
                            }
                            ?>
                            <span class="cat-group" data-rel="<?php echo $v['id']; ?>"
                                  style="<?php echo $display; ?>">
                            <?php
                            $m = 0;
                            foreach ($cats as $k1 => $v1) {
                                if ($v1['parent_id'] == $v['id']) {
                                    $m++;
                                    ?>
                                    <a href="<?php echo $this->baseUrl('topic/' . ($v1['alias'] ? $v1['alias'] : $v1['id'])); ?>"><?php echo $v1['name']; ?></a>
                                <?php
                                }
                            }
                            if ($m == 0) {
//                                echo '暂无子类';
                            }
                            ?>
                            </span>
                        <?php
                        }
                    }
                }
                ?>
            </p>
        </nav>
        <div class="right">
            <form method="get" class="search-form" action="<?php echo $this->baseUrl('search'); ?>">
                <input type="text" class="search-input txt" name="q" placeholder="输入关键字搜索"/>
                <input class="btn btn-success search-btn" type="submit" value="搜索">
            </form>
        </div>
    </section>

</header>
