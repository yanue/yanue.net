<?php
use Library\Core\Config;

?>
<div id="header">
    <div id="logo">
        <a href="<?php echo $this->baseUrl(''); ?>" title="后台管理中心"></a>
    </div>
    <div id="nav">
        <span><a href="javascript:;" id="notice">使用须知</a> | <a href="http://yanue.net" target="_blank">帮助信息</a></span>
        <a href="<?php echo $this->baseUrl(''); ?>">控制面板</a>
        | <a href="https://<?php echo Config::getItem('domain.main'); ?>" target="_blank">网站首页</a>
        | <a href="">刷新本页</a>
        | <a href="<?php echo $this->baseUrl('base/my/logout'); ?>">退出登陆</a>
    </div>
</div>