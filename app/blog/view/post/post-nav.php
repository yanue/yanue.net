<?php
$next = $this->next;
$prev = $this->prev;
?>
<span class="post-nav-prev">上一篇
    <?php if ($prev) {
        ?>
        <a href="<?php echo $this->baseUrl('post-' . $prev['id']); ?>"
           rel="prev" title="<?php echo $prev['title']; ?>"><?php echo $prev['title']; ?></a>
    <?php
    } else {
        echo '没有了';
    }
    ?>
</span>
<span class="post-nav-next">
    <?php if ($next) {
        ?>
        <a href="<?php echo $this->baseUrl('post-' . $next['id']); ?>"
           rel="prev" title="<?php echo $next['title']; ?>"><?php echo $next['title']; ?></a>
    <?php
    } else {
        echo '没有了';
    }
    ?>

    下一篇
</span>