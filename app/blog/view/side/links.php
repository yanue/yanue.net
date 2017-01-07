<?php
use App\Blog\Helper\SideData;

$tags = SideData::getLinks();
if ($tags) {
    foreach ($tags as $tag) {
        ?>
        <a href="<?php echo $tag['url']; ?>"><?php echo $tag['name']; ?></a>
    <?php
    }
}
