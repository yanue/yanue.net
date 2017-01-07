<?php
$recommend = $this->recommend;
?>
<?php foreach ($recommend as $k => $item) {
    if ($k % 2 == 0 && $k > 0) {
        echo('</li><li>');
    }
    ?>
    <dl class="item">
        <dt>
            <a href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
               title="<?php echo $item['title']; ?>"
               rel="bookmark"><img data-src="<?php echo $item['cover_img'] ; ?>" data-width="90" data-height="60" data-rel="imgReady" alt=""/>
            </a>
        </dt>
        <dd>
            <h3 class="name">
                <a href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
                   title="<?php echo $item['title']; ?>"
                   rel="bookmark">
                    <?php echo $item['title']; ?>
                </a>
            </h3>

            <p class="detail"><?php echo $item['sub_title']; ?></p>
        </dd>
    </dl>

<?php
} ?>