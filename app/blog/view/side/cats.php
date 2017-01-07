<?php
use App\Blog\Helper\SideData;

$tags = SideData::getAllCats();
if ($tags) {
    foreach ($tags as $k => $tag) {
        if ($tag['parent_id'] == 0) {
            ?>
            <p class="cat">
                <a href="<?php echo $this->baseUrl('topic/' . ($tag['alias'] ? $tag['alias'] : $tag['id'])); ?>"><?php echo $tag['name']; ?></a>
            </p>
        <?php
        }
    }
}
