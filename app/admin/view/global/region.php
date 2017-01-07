<style>
    .expand {
        text-decoration: none;
        padding: 0 8px 0 0;
        font-family: 'Courier New';
    }

    .operate {
        text-align: right;
    }

    .operate .add {
        cursor: pointer;
        display: none;
    }

    .isHide, .order_nu {
        width: 24px;
    }

    .level1 {
        /*background: #FCFAFE;*//*font-weight: bold;*/
    }

    .level2 {
        display: none;
    }

    .catUrl {
        width: 300px;
    }

    .module, .controller, .action {
        width: 120px;
    }

    .catLevel .current {
        color: #ff0000;
    }

    .catLevel td {
        padding: 6px;
        line-height: 240%;
        font-size: 14px;
        font-family: 'Microsoft Yahei';
    }

    #menus .listData .row {
        display: none;
    }

    #menus .listData .row.current {
        display: table-row;
    }
</style>
<script type="text/javascript">
    seajs.use('app/admin/admin.region', function (api) {
        api.setRegion();
    });
    seajs.use('app/admin/admin.region', function (x) {
        x.delRegion(".delItem");
    });
</script>
<?php
extract($this->data);
?>
<section>
    <form action="javascript:;">
        <table class='list' id="region">
            <caption>
                <b class="captitle">区域管理</b>
            </caption>

            <tr class="head">
                <th width='80px'>操作</th>
                <th width='24px'>区域编号</th>
                <th width='224px'>区域名称</th>
                <th width="20"><a href="javascript:;" class="addBtn">+</a></th>
            </tr>
            <tbody class="listData">
            <?php foreach ($data as $pitem) : ?>
                <?php if ($pitem['parent_id'] == 0): ?>
                    <tr class="row level1" data-id=<?php echo $pitem['id']; ?> data-pid="0">
                        <td class="center">
                            <a href="javascript:;" data-id="<?php echo $pitem['id']; ?>" class="delItem" >删除</a>
                            <span class="add" data-pid=<?php echo $pitem['id']; ?>  title='添加子菜单'>添加</span>
                            <a href="javascript:;" class="expand" data-status='hide' title='展开/隐藏'
                               data-cid=<?php echo $pitem['id']; ?>>[+]</a>

                        </td>
                        <td class='name'><?php echo $pitem['id']; ?></td>
                        <td><input class="txt regionName" type="text" value="<?php echo $pitem['name']; ?>"
                                   data-old="<?php echo $pitem['name']; ?>" /></td>
                        <td class="center"><a href="javascript:;" class="subBtn">确认</a></td>
                    </tr>
                    <?php foreach ($data as $item) : ?>
                        <?php if ($item['parent_id'] == $pitem['id']): ?>
                            <tr class="row level2" data-id="<?php echo $item['id']; ?>"
                                data-pid="<?php echo $pitem['id']; ?>" data-type="child">
                                <td class="center"><a href="javascript:;" data-id="<?php echo $item['id']; ?>" class="delItem" >-</a>
                                </td>
                                <td class='name'><?php echo $item['id']; ?></td>
                                <td>|一一 <input class="txt regionName" type="text" value="<?php echo $item['name']; ?>"
                                               data-old="<?php echo $item['name']; ?>"/></td>
                                <td class="center"><a href="javascript:;" class="subBtn">确认</a></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>

            </tbody>
            <tr class="showpage">
                <th class="name">操作</th>
                <th class="name"><a href="javascript:;" class="addBtn">添加区域</a></th>
                <td colspan="5"><input type="submit" value="提交" id="patchBtn" class="btn-gray"/></td>
            </tr>
        </table>
    </form>
</section>