<?php
$item = $this->item;
?>
<script>
    seajs.use('app/admin/admin.page', function (api) {
        api.setPage('.submitBtn');
        api.delPage('.delPage', 'page');
    });
</script>
<section class="content">
    <h2 class="title">
        修改静态页面 |
        <input type="submit" class="btn-light submitBtn" value="确认修改" data-id="<?php echo $item['id']; ?>">
        <a class="delPage" href="javascript:;" data-id="<?php echo $item['id']; ?>"
           style="float: right;font-weight: normal;">删除</a>
    </h2>
    <?php if ($item) { ?>
        <form class="form" id="pageForm" action="javascript:;">
            <p>
                <span class="label">页面名称</span>
                <input type="text" class="txt" id="txtTitle" value="<?php echo $item['name']; ?>"
                       style="width: 320px;">
                <span style="color: #f00;">*</span>
                <a href="<?php echo $this->baseUrl('html/' . $item['alias']) ?>" target="_blank">查看文章</a>
                <span class="status"></span>
            </p>

            <p>
                <span class="label">英文别名</span>
                <input type="text" class="txt" id="txtAlias" value="<?php echo $item['alias']; ?>"
                       style="width: 180px;">
                <span class="status"></span>
            </p>

            <p>
                <span class="label">正文</span>
                <textarea name="txtContent" id="txtContent" class="txt"
                          style="width: 840px;height: 400px;"><?php echo $item['content']; ?></textarea>
            </p>

            <p>
                <span class="label">加载布局</span>
                <em class="normal">
                    是<input type="radio" name="layout" class="layout" id=""
                            value="1" <?php echo $item['layout'] == 1 ? 'checked="checked"' : ''; ?>>
                    否<input type="radio" name="layout" class="layout"
                            id="" <?php echo $item['layout'] == 0 ? 'checked="checked"' : ''; ?> value="0">
                    <span style="color: #f00;">*</span>(说明：这里表示是否默认包含头底，中间内容在上面区域输入)
                </em>
            </p>

            <p><span class="label">是否发布</span>
                <em>
                    是<input type="radio" name="published" class="published" id=""
                            value="1" <?php echo $item['published'] == 1 ? 'checked="checked"' : ''; ?> >
                    否<input type="radio" name="published" class="published" id=""
                            value="0" <?php echo $item['published'] == 0 ? 'checked="checked"' : ''; ?> >
                    <span style="color: #f00;">*</span> (发布后才会显示)
                </em>
            </p>

            <p class="action">
                <span class="label"></span>
                <input type="submit" class="btn-gray submitBtn" value="提交" data-id="<?php echo $item['id']; ?>">
                <input type="reset" class="btn-light" value="重置">
            </p>
        </form>
    <?php } else { ?>
        <p>文章不存在!</p>
    <?php } ?>
</section>