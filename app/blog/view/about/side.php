<?php
$c = $this->controller;
$a = $this->action;
?>
<ul class="page-menu">
    <li>
        <a href="<?php echo $this->baseUrl('about'); ?>"
           class="<?php echo $c == 'about' && $a == 'index' ? 'current' : ''; ?>">
            关于本站
        </a>
    </li>
    <li>
        <a href="<?php echo $this->baseUrl('sitemap'); ?>"
           class="<?php echo $c == 'about' && $a == 'sitemap' ? 'current' : ''; ?>">
            网站地图
        </a>
    </li>
    <li>
        <a href="<?php echo $this->baseUrl('links'); ?>"
           class="<?php echo $c == 'about' && $a == 'links' ? 'current' : ''; ?>">
            友情链接
        </a>
    </li>
</ul>