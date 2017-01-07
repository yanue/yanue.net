<?php
use App\Blog\Helper\SideData;

$list = SideData::getByComments();
foreach ($list as $item) {
    ?>
    <li>
    <span class="count">
        <a
            href="<?php echo $this->baseUrl('post-' . $item['id']); ?>#comments"
            title="查看评论">
            <i class="icon icon-comments-alt"></i> <?php echo $item['comments']; ?>
        </a>
    </span> · <a
        href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
        title="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></a>
<?php
}
