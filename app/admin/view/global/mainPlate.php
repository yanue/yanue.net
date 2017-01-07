<?php
$data = $this->data;
$data = json_decode($data, true);
$cats = $this->postCat;
?>
<script>
    seajs.use('app/admin/admin.site', function (n) {
        n.setMainPostCat('.setBtn', 'mainPlate');
    })
    seajs.use('app/app.upload', function (api) {
        <?php
        for($i=1;$i<=count( $data );$i++){
            ?>
        var upCoverFunc = function (res) {
            $('.pic<?php echo $i; ?>').val(res.data.url);
            $('.preview<?php echo $i; ?>').attr('src', app.site_url + res.data.url);
        };
        var coverOpt<?php echo $i; ?> = {
            'filelist': 'coverList<?php echo $i; ?>',
            'submit': 'uploadCover<?php echo $i; ?>',
            'browse_button': 'pickCover<?php echo $i; ?>',
            'container': 'containerCover<?php echo $i; ?>'
        };
        api.load('pic', coverOpt<?php echo $i; ?>, upCoverFunc);
        <?php
        }
        ?>
    });

</script>
<div class="" id=''>
    <form action="javascript:;" id="focusForm">
        <table class='list'>
            <caption>
                <b class="captitle">首页文章类别图文设置</b>
            </caption>
            <tr class="head">
                <th style='width:;'>序号</th>
                <th style='width:50px;'>位置</th>
                <th>文章类别及描述</th>
                <th style='width:250px'>图片地址</th>
                <th style='width:100px'>图片预览</th>
            </tr>
            <tbody class="listData">
            <?php
            foreach ($data as $k => $item) {
                $k++;
                ?>
                <tr class="row" data-id="<?php echo $k; ?>">
                    <th class="name middle"><?php echo $k; ?></th>
                    <td class="middle center green pos"><?php echo $item['pos']; ?></td>
                    <td class="middle">
                        <label class="gray">[ 类别 ]</label>
                        <select name="" class="cat">
                            <?php
                            foreach ($cats as $cat) {
                                if ($cat['parent_id'] == 0) {
                                    ?>
                                    <option
                                        value="<?php echo $cat['id']; ?>" <?php echo $item['cat']['id'] == $cat['id'] ? 'selected="selected"' : ''; ?>><?php echo $cat['name']; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                        <label class="gray">[ 一句话描述 ]</label>
                        <input type="text" class="txt txtTitle" value="<?php echo $item['title']; ?>"
                               style="width: 300px;"/>
                    </td>
                    <td class="middle">
                        <p>
                            <input type="text" class="txt txtPic pic<?php echo $k; ?>" data-id="<?php echo $k; ?>"
                                   value="<?php echo $item['pic']; ?>" style="width: 240px;"/>
                        </p>

                        <p>
                        <span id="containerCover<?php echo $k; ?>">
                            <a id="pickCover<?php echo $k; ?>" href="javascript:;">选择图片</a>
                            <span id="coverList<?php echo $k; ?>" style="display: inline-block;"></span>
                            <a id="uploadCover<?php echo $k; ?>" href="javascript:;" data-id="<?php echo $k; ?>">上传</a>
                         </span>
                        </p>
                    </td>
                    <td class="center middle">
                        <img src="<?php echo $item['pic']; ?>" class="preview<?php echo $k; ?>" alt=""
                             style="max-width: 100px;height: 60px;"/>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tr class="showpage">
                <th class="name">操作</th>
                <td colspan="8">
                    <input type="submit" value="设置" class="btn-gray setBtn"/>
                </td>
            </tr>
        </table>
    </form>
</div>