<?php
$data = $this->data;
?>
<script>
    seajs.use('app/admin/admin.base', function (common) {
        common.selectCheckbox();
    });
    seajs.use('app/admin/admin.page', function (n) {
        n.pubPage('.pubBtn');
        n.delPage('.delBtn');
        n.delAllPage(".delAllSelected");
    })
</script>
<div class="" id=''>
    <table class='list'>
        <caption>
            <b class="captitle">静态页面列表</b>
        </caption>
        <tr class="head">
            <th style='width:36px'>ID</th>
            <th style='width:32px'>批量</th>
            <th style='width:160px'>英文别名</th>
            <th style='width:'>页面名称</th>
            <th style='width:50px'>修改</th>
            <th style='width:80px'>是否发布</th>
            <th style='width:50px'>删除</th>
            <th style='width:120px'>添加时间</th>
            <th style='width:120px'>修改时间</th>
        </tr>
        <tbody class="listData">
        <?php
        if ($data) {
            foreach ($data as $item) {
                ?>
                <tr class="row" data-id="<?php echo $item['id']; ?>">
                    <th class='name'><?php echo $item['id']; ?></th>
                    <td class="center"><input type="checkbox" class="chk" data-id="<?php echo $item['id']; ?>"/></td>
                    <td>
                        <a href="<?php echo $this->baseUrl('html/' . $item['alias']); ?>"
                           target="_blank"><?php echo $item['alias']; ?></a>
                    </td>
                    <td>
                        <a href="<?php echo $this->baseUrl('html/' . $item['alias']); ?>"
                           target="_blank"><?php echo $item['name']; ?></a>
                    </td>
                    <td class="center">
                        <a href="<?php echo $this->controllerUrl('update/id/' . $item['id']); ?>" class='upBtn'>修改</a>
                    </td>
                    <td class="center">
                        <?php if ($item['published'] == 0) { ?>
                            <a href="javascript:;" class='pubBtn' style="color: #ff0000;" data-status="1"
                               data-id="<?php echo $item['id']; ?>">发布</a>
                        <?php } else { ?>
                            <a href="javascript:;" class='pubBtn' data-id="<?php echo $item['id']; ?>" data-status="0">取消发布</a>
                        <?php } ?>
                    </td>
                    <td class="center">
                        <a href="javascript::" class='delBtn' data-id="<?php echo $item['id']; ?>">删除</a>
                    </td>
                    <td><?php echo \App\Admin\Lib\Func::formatTime($item['created']); ?></td>
                    <td><?php echo \App\Admin\Lib\Func::formatTime($item['modified']); ?></td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="9">
                    <p style="margin: 20px;color:#f00;"> 暂无内容 </p>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        <tr class="showpage">
            <th class="name">操作</th>
            <td colspan="8">
                <span>
                    [ <a href="javascript:;" class="selectAll">全选</a> ]
                    [ <a href="javascript:;" class="selectNone">全不选</a> ]
                    [ <a href="javascript:;" class="selectInvert">反选</a> ]
                    <a class="btn-light delAllSelected" href="javascript:;">批量删除</a>
                </span>
            </td>
        </tr>
        <tr class="showpage">
            <th class="name">分页</th>
            <td colspan="8">
                <?php $this->render('pagination'); ?>
            </td>
        </tr>
    </table>
</div>
