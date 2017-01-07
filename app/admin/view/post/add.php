<?php

$cats = $this->cats;
$allTags = $this->tags;
?>
<script>
    seajs.use('app/admin/admin.post', function (api) {
        api.setPost('.submitBtn');
        api.setTags();
    });
</script>
<section class="content">
    <h2 class="title">
        添加文章
    </h2>

    <form class="form" id="postForm" action="javascript:;">
        <div class="left-panel">
            <p>
                <span class="label">标题</span>
                <input type="text" class="txt" id="txtTitle" value=""
                       style="width: 320px;">
                <span style="color: #f00;">*</span>
                <a href="http://"
                   target="_blank">查看文章</a>
                <span class="status"></span>
            </p>

            <p>
                <span class="label">副标题</span>
                <input type="text" class="txt" id="txtSubTitle" value=""
                       style="width: 180px;">
                <span class="status"></span>
            </p>

            <p><span class="label">预览图片</span>
                <input type="text" id="coverImg" class="txt" value=""/>
                <span class="upload-widget" data-unique="1"></span>
                <img src="" class="imgPreview" alt="" style="height: 60px;vertical-align: middle;"/>
            </p>

            <p>
                <span class="label">正文</span>
                <textarea name="txtContent" id="txtContent"
                          style="width: 100%;height: 400px;"></textarea>
            </p>

            <p>
                <span class="label">关键字</span>
                <input type="text" class="txt" id="keywords"
                       value=""/>
            </p>

            <p>
                <span class="label">文章作者</span>
                <input type="text" class="txt" id="author"
                       value="<?php echo \Library\Util\Session::get('_CUSR'); ?>"/>
            </p>

            <p>
                <span class="label">文章来源</span>
                <input type="text" class="txt" id="source"
                       value=""/>
            </p>

            <p>
                <span class="label">时间</span>
                <input type="text" class="txt" id="created"
                       value=""/>
                (可选,不填则为系统当前时间,填写则格式必需为 )
            </p>
        </div>

        <aside class="right-panel">
            <section class="widget">
                <p class="widget-title">
                        <span style="float: right;">
                            <a href="">管理分类</a>
                        </span>
                    文章分类
                </p>

                <div class="widget-content">
                    <select name="cat" id="intCat" class="selc multi-selc" size="6">
                        <option value="">请选择</option>
                        <?php
                        foreach ($cats as $cat) {
                            if ($cat['parent_id'] == 0) {
                                echo '<option value="' . $cat['id'] . '">【' . $cat['name'] . '】</option> ';
                                foreach ($cats as $cat2) {
                                    if ($cat2['parent_id'] == $cat['id']) {
                                        echo '<option value="' . $cat2['id'] . '">　　|一' . $cat2['name'] . '</option>';
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
                            value="1">
                    否<input type="radio" name="recommend" class="recommend" id="" checked="checked"
                            value="0">
                </p>


            </section>

            <section class="widget">
                <p class="widget-title">是否发布</p>

                <p class="widget-content">
                    是<input type="radio" name="published" class="published" id="" checked="checked"
                            value="1">
                    否<input type="radio" name="published" class="published" id=""
                            value="0">
                    (发布后才会显示)
                </p>
            </section>

            <p>
                <input type="submit" class="btn-gray submitBtn" value="提交" data-id="">
                <input type="reset" class="btn-light" value="重置">
            </p>
        </aside>
    </form>

</section>