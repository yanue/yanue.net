<script>
    seajs.use('app/admin/admin.page', function (api) {
        api.setPage('.submitBtn');
    })
</script>
<section class="content">
    <h2 class="title">添加静态页面</h2>

    <form class="form" id="pageForm" action="javascript:;">
        <p>
            <span class="label">标题</span>
            <input type="text" class="txt" id="txtTitle" value="" style="width: 320px;">
            <span style="color: #f00;">*</span>
            <span class="status"></span>
        </p>

        <p>
            <span class="label">别名</span>
            <input type="text" class="txt" id="txtAlias" value="" style="width: 120px;">
            <span style="color: #f00;">*</span>
            <span class="status">唯一的英文别名</span>
        </p>

        <p>
            <span class="label">正文</span>
            <textarea name="txtContent" id="txtContent" style="width: 840px;height: 400px;"></textarea>
        </p>

        <p>
            <span class="label">加载布局</span>
            <em class="normal">
                是<input type="radio" name="layout" class="layout" id="" value="1">
                否<input type="radio" name="layout" class="layout" id="" checked="checked" value="0">
                <span style="color: #f00;">*</span>(说明：这里表示是否默认包含头底，中间内容在上面区域输入)
            </em>
        </p>

        <p><span class="label">是否发布</span>
            <em class="normal">
                是<input type="radio" name="published" class="published" id="" checked="checked" value="1">
                否<input type="radio" name="published" class="published" id="" value="0">
                <span style="color: #f00;">*</span> (发布后才会显示)
            </em>
        </p>

        <p class="action">
            <span class="label"></span>
            <input type="submit" class="btn-gray submitBtn" id="addBtn" value="提交">
            <input type="reset" class="btn-light" value="重置">
        </p>
    </form>
</section>