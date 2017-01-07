<?php
use Library\Core\Config;

$cats = $this->cats;
$allTags = $this->tags;
$post = $this->post;
?>
<script>
    seajs.use('app/admin/admin.post', function (api) {
        api.setPost('.submitBtn');
        api.setTags();
        api.delPost('.delPost', <?php echo $post['cid']?>);
    });
</script>
<section class="content">
    <h2 class="title">
        修改文章 |
        <input type="submit" class="btn-light submitBtn" value="确认修改" data-id="<?php echo $post['id']; ?>">
        <a class="delPost" href="javascript:;" data-id="<?php echo $post['id']; ?>"
           style="float: right;font-weight: normal;">删除</a>
    </h2>
    <?php if ($post) { ?>
        <form class="form" id="postForm" action="javascript:;">
            <div class="left-panel">
                <p>
                    <span class="label">标题</span>
                    <input type="text" class="txt" id="txtTitle" value="<?php echo $post['title']; ?>"
                           style="width: 320px;">
                    <span style="color: #f00;">*</span>
                    <a href="http://<?php echo Config::getItem('domain.main') . '/post-' . $post['id'] . '.html'; ?>">查看文章</a>
                    <span class="status"></span>
                </p>

                <p>
                    <span class="label">副标题</span>
                    <input type="text" class="txt" id="txtSubTitle" value="<?php echo $post['sub_title']; ?>"
                           style="width: 180px;">
                    <span class="status"></span>
                </p>

                <p><span class="label">预览图片</span>
                    <span class="upload-widget" data-unique="1"></span>
                    <input type="text" id="coverImg" class="txt" value="<?php echo $post['cover_img']; ?>"/>
                    <img src="<?php echo $post['cover_img']; ?>" class="imgPreview" alt=""
                         style="height: 60px;vertical-align: middle;"/>
                </p>

                <p>
                    <span class="label">正文</span>
                    <textarea name="txtContent" id="txtContent"
                              style="width: 100%;height: 400px;"><?php echo htmlentities($post['content']); ?></textarea>
                </p>

                <p>
                    <span class="label">关键字</span>
                    <input type="text" class="txt" id="keywords"
                           value="<?php echo $post['keywords']; ?>"/>
                </p>

                <p>
                    <span class="label">文章作者</span>
                    <input type="text" class="txt" id="author"
                           value="<?php echo $post['author']; ?>"/>
                </p>

                <p>
                    <span class="label">文章来源</span>
                    <input type="text" class="txt" id="source"
                           value="<?php echo $post['source']; ?>"/>
                </p>

                <p>
                    <span class="label">时间</span>
                    <input type="text" class="txt" id="created"
                           value="<?php echo date('Y-m-d H:i:s', $post['created']); ?>"/>
                    (可选,不填则为系统当前时间,填写则格式必需为 <?php echo date('Y-m-d H:i:s'); ?>)
                </p>
            </div>

            <aside class="right-panel">
                <section class="widget">
                    <p class="widget-title">
                        <span style="float: right;">
                            <a href="<?php echo $this->controllerUrl('cat') ?>">管理分类</a>
                        </span>
                        文章分类
                    </p>

                    <div class="widget-content">
                        <select name="cat" id="intCat" class="selc multi-selc" size="6">
                            <option value="">请选择</option>
                            <?php
                            foreach ($cats as $cat) {
                                if ($cat['parent_id'] == 0) {
                                    $selected = $cat['id'] == $post['cid'] ? 'selected=""' : '';
                                    echo '<option value="' . $cat['id'] . '" ' . $selected . '>【' . $cat['name'] . '】</option> ';
                                    foreach ($cats as $cat2) {
                                        if ($cat2['parent_id'] == $cat['id']) {
                                            $selected = $cat2['id'] == $post['cid'] ? 'selected=""' : '';
                                            echo '<option value="' . $cat2['id'] . '" ' . $selected . ' >　　|一' . $cat2['name'] . '</option>';
                                        }
                                    }
                                }
                            }
                            ?>
                            <option value=0>不指定</option>
                        </select>
                    </div>
                </section>

                <section class="widget tag-clouds">
                    <p class="widget-title">
                        标签
                    </p>

                    <div class="widget-content">
                        <p>
                            <input type="text" class="txt tagVal" value=""/>
                            <input type="button" class="btn btn-light addTag" value="添加"/>
                        </p>

                        <p>
                            多个标签请用英文逗号（,）或空格分开
                        </p>

                        <div class="tag-list">
                            <?php
                            $tags = json_decode($post['tags'], true);
                            if ($tags) {
                                foreach ($tags as $tag) {
                                    ?>
                                    <span class="tag" data-tag="<?php echo $tag['name']; ?>"
                                          data-id="<?php echo $tag['id']; ?>">
                                        <i class="icon icon-remove rm-tag">x</i> <?php echo $tag['name']; ?></span>
                                <?php
                                }
                            } ?>

                        </div>
                        <p>选择已有标签</p>

                        <div class="all-tags">
                            <?php
                            if ($allTags) {
                                foreach ($allTags as $tag) {
                                    ?>
                                    <a href="javascript:;" data-tag="<?php echo $tag['name']; ?>" class="tag"
                                       data-id="<?php echo $tag['id']; ?>"
                                       title="<?php echo $tag['count']; ?>个话题">
                                        <?php echo $tag['name']; ?>
                                    </a>
                                <?php
                                }
                            } ?>
                        </div>

                    </div>
                </section>

                <section class="widget">
                    <p class="widget-title">是否推荐</p>

                    <p class="widget-content">
                        是<input type="radio" name="recommend" class="recommend" id=""
                                value="1" <?php echo $post['recommend'] == 1 ? 'checked="checked"' : ''; ?> >
                        否<input type="radio" name="recommend" class="recommend" id=""
                                value="0" <?php echo $post['recommend'] == 0 ? 'checked="checked"' : ''; ?>>
                    </p>


                </section>

                <section class="widget">
                    <p class="widget-title">是否发布</p>

                    <p class="widget-content">
                        是<input type="radio" name="published" class="published" id=""
                                value="1" <?php echo $post['published'] == 1 ? 'checked="checked"' : ''; ?> >
                        否<input type="radio" name="published" class="published" id=""
                                value="0" <?php echo $post['published'] == 0 ? 'checked="checked"' : ''; ?> >
                        (发布后才会显示)
                    </p>
                </section>

                <p>
                    <input type="submit" class="btn-gray submitBtn" value="提交" data-id="<?php echo $post['id']; ?>">
                    <input type="reset" class="btn-light" value="重置">
                </p>
            </aside>
            <div class="clear"></div>
        </form>
    <?php } else { ?>
        <p>文章不存在!</p>
    <?php } ?>
</section>