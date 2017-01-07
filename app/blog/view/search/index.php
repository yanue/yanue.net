<?php
use Library\Core\Config;

$post = $this->post;
$cats = $this->cats;
$count = $this->count;
?>
<?php
$key = trim(filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING));
?>

<main class="content">
    <section class="left-section search-section">
        <div class="breadcrumbs"><i class="icon icon-flag"></i> 你的位置：<a href="<?php echo $this->baseUrl(''); ?>">首页</a>
            <small><i class="icon icon-angle-right"></i></small>
            搜索结果
        </div>
        <main class="section">
            <form action="/search" class="search-form" method="get">
                <label for="keywords">关键字</label>
                <input type="text" class="txt" name='key' value="<?php echo $key; ?>" style="width: 120px">
                <input type="submit" value="搜索" class="btn-gray" id="searchBtn">
                <?php if ($key) { ?>
                    <span>当前关键字： <em style="color: red;font-style: normal"><?php echo $key; ?></em> 结果：<em
                            style="color: red;font-style: normal"><?php echo $count; ?> </em>条</span>
                <?php } ?>
            </form>
            <section class="list">
                <?php
                if ($post) {
                    foreach ($post as $item) {
                        ?>
                        <article class="post">
                            <header class="post-head">
                                <div class="post-type">
                                    <a href="">
                                        <i class="icon icon-code" title=""></i>
                                    </a>
                                </div>

                                <h2 class="post-title">
                                    <a href="http://<?php echo Config::getItem('domain.main') . '/post-' . $item['id'] . '.html'; ?>"><?php echo $item['title']; ?></a>
                                </h2>

                                <p class="post-info">
                                        <span>
                                            <i class="icon icon-time" title="日期"></i>
                                            发布于 <?php echo \Library\Util\Time::formatHumaneTime(htmlspecialchars($item['created'])); ?>
                                        </span>
                                </p>
                            </header>
                            <section class="post-summary">
                                <p><?php echo mb_substr(strip_tags($item['content']), 0, 80, 'utf-8'); ?></p>
                            </section>
                        </article>
                        <?php
                    }
                }
                ?>
                <!-- empty result -->
                <?php if ($count === 0 && empty($error)): ?>
                    <div class="search-error" style="padding: 12px;line-height: 160%;font-size: 14px;">
                        <p class="text-error">找不到和 <em class="highlight"><?php echo htmlspecialchars($key); ?></em>
                            相符的内容或信息。</p>
                        <h5>建议您：</h5>
                        <ul>
                            <li>1.请检查输入字词有无错误。</li>
                            <li>2.请换用另外的查询字词。</li>
                            <li>3.请改用较短、较为常见的字词。</li>
                        </ul>
                    </div>
                <?php endif; ?>

        </main>
        <?php if ($post) { ?>
            <section class="pagination section">
                <?php \Library\Util\Pagination::display($this); ?>
            </section>
        <?php } ?>
    </section>
    <aside class="sidebar">
        <?php $this->render('side/panel', false); ?>
    </aside>
    <div class="clear"></div>
</main>