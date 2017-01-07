<?php
$data = $this->data;
?>
<script>

    seajs.use('app/app.upload', function (api) {
        var callbackFn = function (res) {
            $('.form .logo').val(res.url);
            $('.form .pic-preview').attr('src', res.url).show();
        };
        api.load('pic', {}, callbackFn);
    });

    seajs.use('app/admin/admin.sys.links', function (api) {
        api.setLinks('#setLinksForm');
        api.del('.delBtn');
    })
</script>
<style>
    .form {
        position: relative;
    }

    .form .preview {
        position: absolute;
        width: 200px;
        height: 150px;
        top: 50px;
        right: 32px;
        overflow: hidden;
    }

    .form .preview .pic-preview {
        width: 200px;
        display: none;
        border: 1px solid #e0e0e0;
    }
</style>
<table class="list">
    <caption>友情链接</caption>
    <tr class="head">
        <th style='width:36px;'>ID</th>
        <th style='width:80px;'>网站logo</th>
        <th style='width:240px;'>网站名称</th>
        <th style='width:120px;'>联系人</th>
        <th style='width:160px;'>联系电话</th>
        <th style='width:50px;'>修改</th>
        <th style='width:50px;'>删除</th>
        <th>描述</th>
    </tr>
    <tbody class="listData">

    <?php if ($data) {
        foreach ($data as $item) {
            ?>
            <tr class="row middle" data-id="<?php echo $item['id']; ?>">
                <th class="name"><?php echo $item['id']; ?></th>
                <td><img class="logo" src="<?php echo $item['logo']; ?>" alt="" style="height: 36px;"/></td>
                <td class="txtTitle">
                    <?php if ($item['url']) { ?>
                        <a href="<?php echo $item['url']; ?>"><?php echo $item['name']; ?></a>
                    <?php } else { ?>
                        <?php echo $item['name']; ?>
                    <?php } ?>
                </td>
                <td class="center admin"><?php echo $item['admin']; ?></td>
                <td class="center contact"><?php echo $item['contact']; ?></td>
                <td class="center"><a href="javascript:;" class="updateBtn"
                                      data-id="<?php echo $item['id']; ?>">修改</a></td>
                <td class="center"><a href="javascript:;" class="delBtn" data-id="<?php echo $item['id']; ?>"
                        >删除</a>
                </td>
                <td class="txtDetail"><?php echo $item['detail']; ?></td>
            </tr>

        <?php
        }
    }
    ?>
    </tbody>
    <tr class="showpage">
        <th class="name">操作</th>
        <td colspan="9">
            <a href="javascript:;" class="addLinks btn-gray">添加链接</a>
        </td>
    </tr>
</table>

<section class="popup-widget" style="width: 600px;margin-left: -300px;height: 400px;margin-top: -200px;">
    <header class="popup-head">友情链接设置<span class="popup-close">x</span></header>
    <form action="javascript:;" class="form" id="setLinksForm">
        <main class="popup-content">
            <p class="field">
                <span class="label">logo</span>
                <input type="text" class="txt logo" maxlength="250" value=""/>
                <em id="container" class="upload-widget">
                    <a id="pickfiles" class="browse" href="javascript:;">选择图片</a>
                    <span id="filelist" style="display: inline-block;"></span>
                    <a id="uploadfiles" href="javascript:;" class="btn-light normal">上传</a>
                </em>
            </p>

            <p class="field">
                <span class="label">网站名称</span>
                <input type="text" class="txt txtTitle" value="" maxlength="30"/>
                <em class="red">*</em>
            </p>

            <p class="field">
                <span class="label">网站地址</span>
                <input type="text" class="txt txtUrl" value=""/>
                <em class="red">*</em>
            </p>

            <p class="field">
                <span class="label">联系人</span>
                <input type="text" class="txt txtAdmin" value=""/>
            </p>


            <p class="field">
                <span class="label">联系电话</span>
                <input type="text" class="txt txtContact" value=""/>
            </p>

            <p class="field">
                <span class="label">网站描述</span>
                <input type="text" class="txt txtDetail" maxlength="80" value="" style="width: 300px;"/>
            </p>

            <p class="field">
                <span class="label">排序</span>
                <input type="text" class="txt intSort" max="255" maxlength="3" value="0" style="width: 30px;"/>
            </p>

            <div class="preview">
                <img src="" class="pic-preview" alt=""/>
            </div>
        </main>
        <footer class="popup-foot">
            <input class="btn btn-gray submitBtn" type="submit"/>
            <input class="btn btn-gray" type="reset"/> (Enter键即可提交)
            <a class="popup-close">关闭窗口</a>
        </footer>
    </form>
</section>