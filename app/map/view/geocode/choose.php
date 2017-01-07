<?php
$title = $this->title;
$description = $this->description;

$arr = array(
    'geocode/google/index' => '谷歌地图 - 无需地图版',
    'geocode/google/map' => '谷歌地图 - js api版',
    'geocode/baidu/index' => '百度地图 - 无需地图版',
);
$uri = $this->uri->getMvcUri();
?>

<div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="http://yanue.net">首页</a>
    <small><i class="icon icon-angle-right"></i></small>
    <a href="http://map.yanue.net">地图作品</a>
    <small><i class="icon icon-angle-right"></i></small>
    <?php echo $title ?>
</div>
<h1 class="page-title"><i class="icon icon-paper-clip" title="使用CTRL+D进行收藏"></i><?php echo $title; ?>
</h1>

<div style="background: #fff;margin: 8px 0 0 0;padding: 12px 10px;">
    <span class="right" style="color: #999;">经纬度地名批量转换工具</span>
    切换版本：
    <?php
    foreach ($arr as $k => $v) {
        ?>
        [ <a href="<?php echo $this->baseUrl(rtrim($k, 'index')); ?>"
             class="<?php echo $uri == $k ? 'red' : ''; ?>"><?php echo $v ?></a> ]
    <?php
    }
    ?>

</div>