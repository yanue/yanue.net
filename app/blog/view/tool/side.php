<?php
$c = $this->controller;
$a = $this->action;
$m = $this->module;
?>

<ul class="page-menu">
    <li>
        <a href="<?php echo $this->baseUrl('tool/css/format'); ?>"
           class="<?php echo $m == 'tool' && $c == 'css' && $a == 'format' ? 'current' : ''; ?>">
             CSS格式化美化压缩工具
        </a>
    </li>
    <li>
        <a href="<?php echo $this->baseUrl('tool/html/format'); ?>"
           class="<?php echo $m == 'tool' && $c == 'html' && $a == 'format' ? 'current' : ''; ?>">
            HTML格式化压缩工具
        </a>
    </li>
    <li>
        <a href="<?php echo $this->baseUrl('tool/js/format'); ?>"
           class="<?php echo $m == 'tool' && $c == 'js' && $a == 'format' ? 'current' : ''; ?>">
            JS美化压缩加密工具
        </a>
    </li>
    <li>
        <a href="<?php echo $this->baseUrl('tool/js/json'); ?>"
           class="<?php echo $m == 'tool' && $c == 'js' && $a == 'json' ? 'current' : ''; ?>">
            JSON美化格式化工具
        </a>
    </li>
</ul>
<p style="margin: 36px;">
    <a href="<?php echo $this->baseUrl('tool'); ?>#comments" class="btn btn-success btn-block">
        提建议
        <i class="icon icon-angle-right"></i>
    </a>
</p>