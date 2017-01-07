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

    .level2 .catTitle {
        width: 142px;
    }

    .catAlias {
        width: 120px;
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

    .catDetail {
        width: 360px;
    }

    #menus .listData .row {
        display: none;
    }

    #menus .listData .row.current {
        display: table-row;
    }

    .mvCat {
        width: 208px;
    }
</style>
<script type="text/javascript">
    seajs.use('app/admin/admin.post', function (api) {
        api.setCats();
        api.delCat('.delCatBtn');
        api.mvCat('.mvCatBtn');
    });
</script>
<?php
$cats = $this->cats;
?>
<section>
    <form action="javascript:;">
        <table class='list' id="postCats">
            <caption>
                <b class="captitle">文章文章栏目设置</b>
            </caption>

            <tr class="head">
                <th style='width:24px'>ID</th>
                <th style='width:80px'>添加栏目</th>
                <th style='width:194px'>标题</th>
                <th style='width:176px'>英文标题</th>
                <th style='width:142px'>访问别名</th>
                <th style='width: 382px;'>描述</th>
                <th style="50px">删除</th>
                <th>移动</th>
            </tr>
            <tbody class="listData">
            <?php
            foreach ($cats as $cat1) {
                if ($cat1['parent_id'] == 0) {
                    ?>
                    <tr class="row level1" data-id=<?php echo $cat1['id']; ?> data-pid="0">
                        <th class='name'><?php echo $cat1['id']; ?></th>
                        <td class="operate">
                            <span class="add" data-pid=<?php echo $cat1['id']; ?>  title='添加子栏目'>添加</span>
                            <a href="javascript:;" class="expand" data-status='hide' title='展开/隐藏'
                               data-cid=<?php echo $cat1['id']; ?>>[+]</a>
                        </td>
                        <td><input class="txt catTitle" type="text" value="<?php echo $cat1['name']; ?>"
                                   data-old-title="<?php echo $cat1['name']; ?>"/></td>
                        <td><input class="txt catEnTitle" type="text" value="<?php echo $cat1['en_name']; ?>"
                                   data-old-enTitle="<?php echo $cat1['en_name']; ?>"/></td>
                        <td><input class="txt catAlias" type="text" value="<?php echo $cat1['alias']; ?>"
                                   data-old-alias="<?php echo $cat1['alias']; ?>"/></td>
                        <td><input class="txt catDetail" type="text" value="<?php echo $cat1['detail']; ?>"
                                   data-old-detail="<?php echo $cat1['detail']; ?>"/></td>
                        <td class="center">
                            <a href="javascript:;" class="delCatBtn" data-id=<?php echo $cat1['id']; ?>>删除</a>
                        </td>
                        <td>
                            移到 >
                            <select name="" class="mvCat" data-cid="<?php echo $cat1['id']; ?>">
                                <option value="">请选择</option>
                                <?php
                                foreach ($cats as $val) {
                                    if ($val['parent_id'] == 0 && $val['id'] != $cat1['id']) {
                                        $selected = $val['id'] == $cat['id'] ? 'selected=""' : '';
                                        echo '<option value="' . $val['id'] . '" ' . $selected . '>【' . $val['name'] . '】</option> ';
                                    }
                                }
                                ?>
                            </select>
                            <input type="button" value="确认移动" class="btn-light mvCatBtn"
                                   data-cid="<?php echo $cat1['id']; ?>">
                        </td>
                    </tr>
                    <?php
                    foreach ($cats as $cat2) {
                        if ($cat2['parent_id'] == $cat1['id']) {
                            ?>
                            <tr class="row level2" data-id="<?php echo $cat2['id']; ?>"
                                data-pid="<?php echo $cat1['id']; ?>">
                                <th class='name'><?php echo $cat2['id']; ?></th>
                                <td class="operate">
                                </td>
                                <td>|一一 <input class="txt catTitle" type="text" value="<?php echo $cat2['name']; ?>"
                                               data-old-title="<?php echo $cat2['name']; ?>"/></td>
                                <td><input class="txt catEnTitle" type="text" value="<?php echo $cat2['en_name']; ?>"
                                           data-old-enTitle="<?php echo $cat2['en_name']; ?>"/></td>
                                <td><input class="txt catAlias" type="text" value="<?php echo $cat2['alias']; ?>"
                                           data-old-alias="<?php echo $cat2['alias']; ?>"/></td>
                                <td><input class="txt catDetail" type="text" value="<?php echo $cat2['detail']; ?>"
                                           data-old-detail="<?php echo $cat1['detail']; ?>"/></td>
                                <td class="center">
                                    <a href="javascript:;" class="delCatBtn" data-id=<?php echo $cat2['id']; ?>>删除</a>
                                </td>
                                <td>
                                    移动 >
                                    <select name="" class="mvCat" data-cid="<?php echo $cat2['id']; ?>">
                                        <option>请选择</option>
                                        <?php
                                        foreach ($cats as $val) {
                                            if ($val['parent_id'] == 0 && $val['id'] != $cat1['id']) {
                                                $selected = $val['id'] == $cat['id'] ? 'selected=""' : '';
                                                echo '<option value="' . $val['id'] . '" ' . $selected . '>【' . $val['name'] . '】</option> ';
                                                foreach ($cats as $val2) {
                                                    if ($val2['parent_id'] == $val['id']) {
                                                        $selected = $val['id'] == $cat['id'] ? 'selected=""' : '';
                                                        echo '<option value="' . $val2['id'] . '" ' . $selected . ' >　　|一' . $val2['name'] . '</option>';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        <option value="0">最顶级</option>
                                    </select>
                                    <input type="button" value="确认移动" class="btn-light mvCatBtn"
                                           data-cid="<?php echo $cat2['id']; ?>">
                                </td>
                            </tr>
                        <?php
                        }
                    } ?>
                <?php
                }
            } ?>
            <tr class="row level1">
                <th class="name"></th>
                <td class="operate">
                </td>
                <td><input class="txt catTitle" type="text" value="" data-old-title=""></td>
                <td><input class="txt catEnTitle" type="text" value="" data-old-entitle=""></td>
                <td><input class="txt catAlias" type="text" value="" data-old-alias=""></td>
                <td><input class="txt catDetail" type="text" value="" data-old-detail=""></td>
                <td class="center">
                </td>
                <td>
                </td>
            </tr>
            </tbody>
            <tr class="showpage">
                <th class="name">操作</th>
                <td colspan="12"><input type="submit" value="设置栏目" id="catDoBtn" class="btn-gray"/></td>
            </tr>
        </table>
    </form>
</section>