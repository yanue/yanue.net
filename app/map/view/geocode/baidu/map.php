<?php
$title = $this->title;
$description = $this->description;
?>
<script type="text/javascript">
    seajs.use('app/map/map.baidu', function (map) {
        map.geocode_map();
    });
    seajs.use('app/app.share', function (api) {
        var shareParas = {
            'elem': ".shareAll",//分享dom
            'pics': [""],//图片地址数组
            'title': '<?php echo $title; ?>',//分享标题
            'desc': '<?php echo preg_replace('/\s/',' ',$description); ?>' //分享描述
        };
        api.share(shareParas);
    });
    seajs.use('app/app.ad', function (ad) {
        ad.closeAd('.closeAd');
    });
</script>
<script src=""></script>
<script type="text/javascript"
        src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
<script type="text/javascript"
        src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
<div class="content">
    <?php $this->render('geocode/choose',false); ?>

    <aside class="panel">
        <section class="input-area">
            <p class="tip-title"><i class="icon icon-magic"></i> <span class="red">请输入地址</span>：每行一个地址</p>

            <p>
                <textarea name="" id="addrs" class="txt">深圳市腾讯大厦停车场
                    深圳市竹子林公交总站
                    深圳市车公庙
                </textarea>
            </p>

            <p class="action">
                <input type="button" value="解析经纬度" id="toLatLntBtn" class="button"/>
                <a href="javascript:;" class="clear">清空结果</a>
            </p>
        </section>

        <section class="input-area">
            <p class="tip-title"><i class="icon icon-location-arrow"></i> <span class="red">请输入经纬度</span>：每行以纬度,经度形式</p>

            <p>
                <textarea name="" id="latlng" class="txt">22.246417,113.941181
                    22.240865,114.017209
                </textarea>
            </p>

            <p class="action">
                <input type="button" value="解析地址" id="toAddrBtn" class="button"/>
                <a href="javascript:;" class="clear">清空结果</a>
            </p>
        </section>

    </aside>
    <section class="map">
        <div id="allmap" style="width: 100%;height: 100%;"></div>
    </section>
    <div class="resArea">
        <h4 class="res-title">
            <span id="" class="shareAll right"></span>
            <i class="circle">果</i> 解析结果
        </h4>
        <ol id="result">
            <li>请在左边相应区域输入经纬度或地址</li>
            <li>点击对应按钮即可</li>
        </ol>
        <div class="ad" data-area="map-right-test">
            <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DN58Ivy7kX8gcQipKwQzePCperVdZeJvipRe%2F8jaAHci5VBFTL4hn2d4fJ%2BObyK0z%2Bx%2FKLma%2BVNkMJkLqbRE%2FrLCapMPd%2FCYeaX%2BlBEf2Pn35RMDOcdoWg70CYBEjBf0rLxRPKN2FDAck%2FCKKDVvEs8YMXU3NNCg%2F"
               target="_blank"><img src="http://img01.taobaocdn.com/tps/i1/T1WTPNXdXfXXbCFbsb-100-100.jpg"/></a>
            <a
                href="http://s.click.taobao.com/t?e=m%3D2%26s%3DrwtOZlGrG4YcQipKwQzePCperVdZeJviEViQ0P1Vf2kguMN8XjClAr7VJS%2BSoHNzn0VGlIPb7UTsOYgMeIg%2Brd5FjB3SrU8ysKN7YqLtXJcCvKqgwfnrDudn1BbglxZYxUhy8exlzcq9AmARIwX9K2Zg%2BdzdQFOwfMRvoxSVDSdLyrb2g0H2G5JcxXijM%2BwneEHpPTskRHnPKdU%2FdTrgjbw4MC6y5nKlXF%2B87KN7TKeiZ%2BQMlGz6FQ%3D%3D"
                target="_blank"><img src="http://gtms04.alicdn.com/tps/i4/T10quBFMhbXXb6SDsI-120-240.jpg"/></a>
            <span class="right"><a href="javascript:;" class="closeAd" data-area="map-right-test">关闭广告</a></span>
            <script type="text/javascript">
                document.write('<a style="display:none!important" id="tanx-a-mm_14341575_3402198_22064370"></a>');
                tanx_s = document.createElement("script");
                tanx_s.type = "text/javascript";
                tanx_s.charset = "gbk";
                tanx_s.id = "tanx-s-mm_14341575_3402198_22064370";
                tanx_s.async = true;
                tanx_s.src = "http://p.tanx.com/ex?i=mm_14341575_3402198_22064370";
                tanx_h = document.getElementsByTagName("head")[0];
                if (tanx_h)tanx_h.insertBefore(tanx_s, tanx_h.firstChild);
            </script>
        </div>
        <h4 class="res-title">
            <i class="circle">用</i> 使用说明 <span class="right normal">版本 1.0</span>
        </h4>

        <section class="detail">
            <div class="left" style="">
                <p class="title"> 说明</p>
                <ol>
                    <li>实现无需加载地图快速转换经纬度及地址</li>
                    <li>根据Google Geocoding API接口实现，<a
                            href="https://developers.google.com/maps/documentation/geocoding/?hl=zh-cn" target="_blank">接口传送门
                            >></a></li>
                    <li>根据官方条件，每天查询请求不得超过2,500 个。</li>
                </ol>
                <p class="title" style="border-top: 1px solid #f0f0f6;">
                    相关文章
                    <span class="right">
                        <a href="http://yanue.net/topic/map.html" target="_blank">more <i
                                class="icon icon-angle-right"></i></a>
                    </span>
                </p>
                <ol>
                    <li>
                        <a href="http://yanue.net/post-23.html" title="(原创)谷歌地图地理解析和反解析geocode.geocoder详解"
                           target="_blank">(原创)谷歌地图地理解析和反解析geocode.geocoder详解</a>
                    </li>
                </ol>
            </div>

            <div class="right" style="float: left;width: 50%;margin: 0 0 0 -1px;">
                <p class="title">其他版本地图工具</p>
                <ol>
                    <li class="tlink"><a href="">百度地图经纬度地名批量快速转换工具 - 无需地图版</a></li>

                    <li class="tlink"><a href="">百度地图经纬度地名批量快速转换工具 - 无需地图版</a></li>

                    <li class="tlink"><a href="">百度地图经纬度地名批量快速转换工具 - 无需地图版</a></li>

                    <li class="tlink"><a href="">百度地图经纬度地名批量快速转换工具 - 无需地图版</a></li>
                </ol>
            </div>
        </section>
    </div>
    <div class="clear"></div>
</div>


<!-- Duoshuo Comment BEGIN -->
<nav class="" style="border-top:1px solid #f2f2f2;background-color:#FBFBFB;padding:10px 10px;font-size:14px;color:#bbb">
    <i class="circle">评</i> 意见反馈<a name="comments"></a>
</nav>
<section class="comment-area" style="background: #fff;padding: 8px;margin: 0 0 12px;">

    <div class="ds-thread"
         data-title="<?php echo $title; ?>"></div>
</section>
<!-- Duoshuo Comment END -->
