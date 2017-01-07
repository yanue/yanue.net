<?php
use App\Blog\Helper\SideData;

$tags = SideData::getTags(12);
if ($tags) {
    foreach ($tags as $tag) {
        ?>
        <a href="<?php echo $this->baseUrl('tag/' . urlencode($tag['name'])); ?>"><?php echo $tag['name']; ?>
            (<?php echo $tag['count']; ?>)</a>
    <?php
    }
}
