<?php
$relate = $this->relate;
if ($relate) {
    foreach ($relate as $item) {
        ?>
        <li><a href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
               title="<?php echo $item['title']; ?>"><i class="icon icon-star-empty"></i>
                <?php echo $item['title']; ?>
            </a><span></span></li>
    <?php
    }
}
?>
