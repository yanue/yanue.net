<?php
$title = $this->title;
$description = $this->description;
?>
<script type="text/javascript">
    seajs.use('app/map/map.google', function (map) {
        map.geocode();
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
    <div class="resArea">
        <h4 class="res-title">
            <span id="" class="shareAll right"></span>
            <i class="circle">果</i> 解析结果
        </h4>
        <ol id="result">
            <li>请在左边相应区域输入经纬度或地址</li>
            <li>点击对应按钮即可</li>
        </ol>

        <?php $this->render('geocode/ad', false); ?>

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
                        <a href="https://yanue.net/topic/map.html" target="_blank">more <i
                                class="icon icon-angle-right"></i></a>
                    </span>
                </p>
                <ol>
                    <li>
                        <a href="https://yanue.net/post-23.html" title="(原创)谷歌地图地理解析和反解析geocode.geocoder详解"
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
