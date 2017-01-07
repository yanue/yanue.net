<?php
use App\Blog\Helper\SideData;

$list = SideData::getPopular();
foreach ($list as $item) {
    ?>
    <li><i class="count"><?php echo $item['views']; ?></i> <i class="icon icon-star-half-empty"></i> <a
        href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
        title="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></a>
<?php
}
