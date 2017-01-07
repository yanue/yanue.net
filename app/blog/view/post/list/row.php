<?php
$list = $this->list;
if ($list) {
    foreach ($list as $item) {
        ?>
        <article class="post" id="post-4">
            <?php if ($item['cover_img']) { ?>

                <dl class="post-img">
                    <dt>
                        <a href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
                           title="<?php echo $item['title']; ?>"
                           rel="bookmark"> <img data-src="<?php echo $item['cover_img']; ?>" data-width="160"
                                                data-height="100"
                                                data-rel="imgReady"/></a>
                    </dt>
                    <dd>
                        <header class="post-head">
                            <h2 class="post-title">
                                <?php if ($this->admin_uid > 0) { ?>
                                    <span class="right">
                                        <a class="edit" href="<?php echo $this->suburl('admin', 'post/update/id/' . $item['id']); ?>">编辑</a>
                                    </span>
                                <?php } ?>
                                <a href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
                                   title="<?php echo $item['title']; ?>"
                                   rel="bookmark"><?php echo $item['title']; ?></a>
                            </h2>

                            <p class="post-info">
                                        <span>
                                            <i class="icon icon-time" title="日期"></i>
                                            发布于 <?php echo \Library\Util\Time::formatHumaneTime($item['created']); ?>
                                        </span>
                                        <span>
                                            <i class="icon icon-bar-chart"
                                               title="点击"></i>    <?php echo $item['views']; ?>
                                        </span>
                                        <span>
                                              <a href="<?php echo $this->baseUrl('post-' . $item['id'] . '#comments'); ?>"
                                                 class="link-comments"
                                                 title="点击查看评论">
                                                  <i class="icon icon-comments"
                                                     title="评论"></i>
                                                  <?php echo $item['comments']; ?>
                                                  条评论 </a>
                                        </span>
                            </p>

                        </header>
                        <section class="post-summary">
                            <p>
                                <?php echo mb_substr(strip_tags($item['content']), 0, 87, 'utf-8'); ?>
                            </p>
                        </section>
                    </dd>
                </dl>
            <?php } else { ?>
                <header class="post-head">
                    <div class="post-type">
                        <a href="">
                            <i class="icon icon-code" title=""></i>
                        </a>
                    </div>
                    <h2 class="post-title">
                        <?php if ($this->admin_uid > 0) { ?>
                            <span class="right">
                                        <a class="edit" href="<?php echo $this->suburl('admin', 'post/update/id/' . $item['id']); ?>">编辑</a>
                                    </span>
                        <?php } ?>
                        <a href="<?php echo $this->baseUrl('post-' . $item['id']); ?>"
                           title="<?php echo $item['title']; ?>"
                           rel="bookmark"><?php echo $item['title']; ?></a>
                    </h2>

                    <p class="post-info">
                                        <span>
                                            <i class="icon icon-time" title="日期"></i>
                                            发布于 <?php echo \Library\Util\Time::formatHumaneTime($item['created']); ?>
                                        </span>
                                        <span>
                                            <i class="icon icon-bar-chart"
                                               title="点击"></i>    <?php echo $item['views']; ?>
                                        </span>
                                        <span>
                                              <a href="<?php echo $this->baseUrl('post-' . $item['id'] . '#comments'); ?>"
                                                 class="link-comments"
                                                 title="点击查看评论">
                                                  <i class="icon icon-comments"
                                                     title="评论"></i>
                                                  <?php echo $item['comments']; ?>
                                                  条评论 </a>
                                        </span>
                    </p>

                </header>
                <section class="post-summary">
                    <p>
                        <?php echo mb_substr(strip_tags($item['content']), 0, 120, 'utf-8'); ?>
                    </p>
                </section>

            <?php } ?>
        </article>
    <?php
    }
} ?>