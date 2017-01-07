<?php
use App\Blog\Helper\SideData;

$list = SideData::getLatest();
foreach ($list as $item) {
    ?>
    <li><i class="count"><?php echo date('m-d', $item['created']); ?></i> <i class="icon   icon-angle-right"></i> <a
        href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
        title="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></a>
<?php
}
