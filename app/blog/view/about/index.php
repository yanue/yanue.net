<?php
$page = $this->page;
?>
<main class="wrap">
    <section class="content">
        <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
            <small><i class="icon icon-angle-right"></i></small>
            关于
            <small><i class="icon icon-angle-right"></i></small>
            <?php echo $page['name'] ? $page['name'] : ''; ?>
        </div>
        <h1 class="page-title"><?php echo $page['name'] ? $page['name'] : ''; ?></h1>

        <section class="container">
            <aside class="page-panel">
                <?php $this->render('about/side', false); ?>
            </aside>
            <main class="page-content">
                <?php echo $page['content'] ? $page['content'] : ''; ?>
                <?php
                if ($this->action == 'contact') {
                    ?>
                    <script type="text/javascript"
                            src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
                    <script>
                        window.onload = function () {
                            //初始化地图
                            var location = new google.maps.LatLng(22.53091239740718, 114.02681951317527);
                            var map = new google.maps.Map(document.getElementById("map_canvas"), {
                                center: location,
                                zoom: 18,
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            });

                            var maker = new google.maps.Marker({map: map, position: location});
                            infowindow = new google.maps.InfoWindow({ content: '<h2 class="red">深圳智享时代科技有限公司</h2><p>公司地址：中国深圳福田区车公庙安华工业园6栋317A室</p>' });
                            infowindow.open(map, maker);
                            google.maps.event.addListener(maker, 'click', function () {
                                infowindow.open(map, maker);
                            });

                        }
                    </script>
                    <div id="map_canvas" style="margin: 20px 0 0 0;width: 920px;height: 400px;"></div>
                <?php
                }
                ?>
                <!-- Duoshuo Comment BEGIN -->
                <section class="comment-area">
                    <div class="ds-thread" data-thread-key="209"
                         data-title="关于我"></div>
                </section>
                <!-- Duoshuo Comment END -->
            </main>
            <div class="clear"></div>


        </section>
    </section>

</main>
