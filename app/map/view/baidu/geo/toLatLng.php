<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=CG8eakl6UTlEb1OakeWYvofh"></script>
<link rel="stylesheet" href=""/>
<?php
$title = $this->title;
$description = $this->description;
?>
<script type="text/javascript">
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
    seajs.use('app/map/map.baidu.geo', function (m) {
        m.toLatLng();
    });
</script>
<style>
    .infoDiv {
        padding: 8px;
        line-height: 160%;
    }

    #lat, #lng {
        width: 136px;
    }
</style>
<div class="content">
    <?php $this->render('geocode/choose', false); ?>

    <header style="background: #fcfcf9;margin: 8px 0;padding: 8px;">
        <form action="javascript:;">
            请输入地点：<input id="address" type="text" class="txt" value=""/>
            <input type="submit" class="input btn button geoBtn" value="解 析" id="geoBtn" onclick=""/>
        </form>
    </header>
    <aside class="panel">
        <div id="infoPanel">
            <p class="tip-title"><i class="icon icon-magic"></i> <span class="red">解析结果</span></p>

            <div style="" class="infoDiv">
                <p><b style="color: #f00"> 当前纬度：</b><input id="lat" class="disabled txt" name="lat" size="20"
                                                           type="text"
                                                           value="等待解析" disabled="disabled txt"></p>

                <p><b
                        style="color: #f00">
                        当前经度：</b><input id="lng" class="disabled txt" name="lng" size="20" type="text" value="等待解析"
                                        disabled="disabled"></p>

                <p><b style="display: none">当前位置经纬度：</b></p>

                <div id="info" class="infoDiv" style="display: none"></div>
                <p><b>最佳匹配地址：</b></p>

                <div class="infoDiv" id="endAddress">等待解析</div>
                <p><b>经纬度范围：</b></p>

                <div class="infoDiv" id="latLngRange">等待解析</div>
                <p><b>拖动解析指示：</b></p>

                <div id="markerStatus" class="infoDiv">请点击并拖动地标</div>
            </div>

            <p class="tip-title"><i class="icon icon-magic"></i> <span class="red">温馨提示</span></p>

            <div class="infoDiv">
                1. 请输入你要解析的地理位置<br> 2. 如果解析出来的位置有偏差 你可以拖动地图上的点进行重新解析
            </div>
            <p></p>

            <p class="tip-title"><i class="icon icon-magic"></i> <span class="red">实现功能</span></p>

            <div class="infoDiv">
                1. 鼠标经过提示经纬度<br> 2. 自动填充地名地点名称<br> 3. 输入完成后可直接点击enter键进行解析<br>
                4. 地理位置不准确，可以拖动重新解析<br> 5. 解析后经纬度信息显示完整
            </div>

            <p class="infoDiv">
                作者：<a href="http://yanue.net/" target="_blank">yanue</a><br>
                文章：<a href="http://yanue.net/archives/32.html" target="_blank">详细介绍</a>
                <a href="http://map.yanue.net/toLatLng.rar">源码下载</a>

            </p>
        </div>
    </aside>
    <div class="resArea" style="position: relative;">
        <div id="overTip" style="position: absolute;left: 0;top: 0;z-index: 99;display: none;background: #ffc;
	border: 1px solid #676767;
	font-family: arial, helvetica, sans-serif;
	font-size: 12px;
	padding: 4px;">
        </div>
        <div id="map_canvas" style="width: 100%;height: 600px;border: 1px solid #e0e0e0;"></div>
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

