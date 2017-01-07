<?php
$title = $this->title;
?>
<style>
    .panel {
        width: 320px;
        background: #fff;
        float: left;
        margin: 8px 0 0 0;
        z-index: 1
    }

    .content {

    }

    .input-area {
        padding: 0 0 10px 0;
    }

    .resArea {
        width: 870px;
        height: 100%;
        float: left;
        z-index: 100;
        margin: 8px 0 0 8px;
        border: 1px solid #f0f0f6;
        background: #FCFcF9;
    }

    #result {
        height: 360px;
        padding: 8px;
        list-style-type: decimal;
        list-style-position: inside;
        overflow-y: scroll;
    }

    .txt {
        border: 1px solid #e0e0e0;
        line-height: 18px;
        color: #666;
        padding: 3px 5px;
        background: #fefefe;
        width: 290px;
        height: 200px;
        margin: 8px;
        outline: none;
    }

    .button {
        color: #444;
        padding: 5px 8px;
        outline: none;
        border: 1px solid rgba(0, 0, 0, 0.1);
        cursor: pointer;
        border-radius: 5px;
        background-color: #F5F5F5;
        background-image: -moz-linear-gradient(center top, #F5F5F5, #F1F1F1);
        box-shadow: 0 0 5px rgba(120, 120, 120, .1), inset 0 0 2px #fff;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, .1);
        /*鼠标经过背景渐变过程*/
        -moz-transition: all .2s ease-in-out;
        -webkit-transition: all .2s ease-in-out;
        -o-transition: all .2s ease-in-out;
        -ms-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;
    }

    .button:focus, .button:hover {
        background-color: #fff;
        background-image: -moz-linear-gradient(center top, #fcfcfc, #F1F1F1);
        border: 1px solid #C6C6C6;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        color: #333333;
    }

    .btn-gray:hover {
        border: 1px solid #999;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        color: #000;
    }

    .txt:hover, .txt:focus {
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #ccc;
        background: #f9f9f9;
        color: #333;
    }

    .res-title {
        border-bottom: 1px solid #f0f0f6;
        line-height: 36px;
        padding: 0 0 0 12px;
        font-size: 16px;
        font-weight: normal;
        font-family: 'microsoft yahei';
        background: #FCF9F6;
    }

    .tip-title {
        border: 1px solid #f0f0f6;
        border-right: none;
        border-left: none;
        line-height: 36px;
        color: #666;
        padding: 0 0 0 10px;
        font-size: 13px;
        font-weight: normal;
        font-family: 'microsoft yahei';
        background: #fcfcf9;
    }

    .action {
        padding: 0 8px;
    }

    .copy {
        border: 1px solid #f0f0f6;
        border-right: none;
        background: #fcfcf9;
        width: 303px;
        padding: 8px;
        color: #666;
        margin: 20px 0 0;
        font-family: 'microsoft yahei';
        line-height: 160%;
    }
</style>

<div class="content">

    <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="http://yanue.net">首页</a>
        <small><i class="icon icon-angle-right"></i></small>
        关于
        <small><i class="icon icon-angle-right"></i></small>
        关于
    </div>
    <h1 class="page-title"><i class="icon icon-paper-clip"></i>Google Map经纬度地名批量转换工具</h1>
    <aside class="panel">
        <section class="input-area">
            <p class="tip-title"><span class="red">请输入地址</span>：每行一个地址</p>

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
            <p class="tip-title"><span class="red">请输入经纬度</span>：每行以纬度,经度形式</p>

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
            解析结果
        </h4>
        <ol id="result">

        </ol>
    </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
    seajs.use('app/map/map.google', function (map) {
        map.geocode();
    });
    seajs.use('app/app.share', function (api) {
        var shareParas = {
            'elem': ".shareAll",//分享dom
            'pics': [""],//图片地址数组
            'title': '<?php echo $this->title; ?>',//分享标题
            'desc': '<?php echo preg_replace('/\s/',' ',$this->description); ?>' //分享描述
        };
        api.share(shareParas);
    });
</script>
