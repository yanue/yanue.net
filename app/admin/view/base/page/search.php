<?php
$post = $this->post;
$cats = $this->cats;
?>
<script>
    seajs.use('app/admin/admin.base',function(common){
        common.selectCheckbox();
    });
    seajs.use('app/admin/admin.post',function(n){
        n.pubPost('.pubBtn');
        n.delPost('.delBtn');
        n.mvPost('.mvPostBtn');
        n.delAllPost(".delAllSelected");
    })
</script>
<?php
    $key =  trim(filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING));
?>
<div class="" id=''>
    <table class='list'>
        <caption>
            <b class="captitle">文章列表</b>
        </caption>
        <tr class="showpage">
            <th style="vertical-align: middle">
                搜索
            </th>
            <td colspan="8">
                <form action="<?php echo $this->controllerUrl('search'); ?>" class="" method="get">
                    <label for="keywords">关键字</label>
                    <input type="text" class="txt" name='key' value="<?php echo $key ;?>" style="width: 120px">
                    <input type="submit" value="搜索" class="btn-gray" id="searchBtn">
                    <?php if($key){ ?>
                    <span>当前关键字： <em style="color: red;font-style: normal"><?php echo $key; ?></em> 结果：<em style="color: red;font-style: normal"><?php echo $this->total;?> </em>条</span>
                    <?php } ?>
                </form>
            </td>
        </tr>
        <tr class="head">
            <th width='36px'>ID</th>
            <th width='32px'>批量</th>
            <th width=''>标题</th>
            <th width='120px'>栏目</th>
            <th width='50px'>修改</th>
            <th width='80px'>是否发布</th>
            <th width='50px'>删除</th>
            <th width='120px'>添加时间</th>
            <th width='120px'>修改时间</th>
        </tr>
        <tbody class="listData">
        <?php
        if($post){
            foreach($post as $item){
                ?>
                <tr class="row" data-id="<?php echo $item['id'];?>" data-cid="<?php echo $item['cid'];?>">
                    <th class='name'><?php echo $item['id'];?></th>
                    <td class="center"><input type="checkbox" class="chk" data-id="<?php echo $item['id'];?>"/></td>
                    <td>
                        <a  href="<?php echo $this->baseUrl('post/detail-'.$item['id'].'.html'); ?>" target="_blank"><?php echo $item['title']; ?></a>
                        <?php if($item['recommend']){ ?>
                            <img src="<?php echo $this->baseUrl('assets/images/admin/jian.jpg');?>" alt=""/>
                        <?php } ?>
                    </td>
                    <td class="center">
                        <a  href="<?php echo $this->actionUrl('cid/'.$item['cid']); ?>" >
                            <?php echo $item['category'] ? $item['category'] : '默认分类' ; ?>
                        </a>
                    </td>
                    <td class="center">
                        <a href="<?php echo $this->controllerUrl('update/id/'.$item['id']); ?>" class='upBtn'>修改</a>
                    </td>
                    <td class="center">
                        <?php if (!$item['published']){ ?>
                            <a href="javascript:;" class='pubBtn' style="color: #ff0000;"  data-status="1" data-id="<?php echo $item['id']; ?>">发布</a>
                        <?php } else{?>
                            <a href="javascript:;" class='pubBtn' data-id="<?php echo $item['id']; ?>" data-status="0">取消发布</a>
                        <?php } ?>
                    </td>
                    <td class="center">
                        <a href="javascript::" class='delBtn' data-id="<?php echo $item['id']; ?>">删除</a>
                    </td>
                    <td><?php echo \App\Admin\Lib\Func::formatTime($item['created']);?></td>
                    <td><?php echo \App\Admin\Lib\Func::formatTime($item['modified']);?></td>
                </tr>
            <?php } }else{?>
            <tr>
                <td colspan="9">
                    <p style="margin: 20px;color:#f00;"> 暂无内容 </p>
                </td>
            </tr>
        <?php }?>
        </tbody>
        <tr class="showpage">
            <th class="name">操作</th>
            <td colspan="8">
                <span style="float: ;">
                    [ <a href="javascript:;" class="selectAll">全选</a> ]
                    [ <a href="javascript:;" class="selectNone">全不选</a> ]
                    [ <a href="javascript:;" class="selectInvert">反选</a> ]
                    <a class="btn-light delAllSelected" href="javascript:;">批量删除</a>
                </span>
                <span style="float: right;">
                        选中文章移到 >
                    <select name="" class="mvPostCat">
                        <option>请选择</option>
                        <?php
                        foreach($cats as $val){
                            if($val['parent_id'] == 0){
                                echo '<option value="'.$val['id'].'" >【'.$val['name'].'】</option> ';
                                foreach($cats as $val2){
                                    if($val2['parent_id']==$val['id']){
                                        echo '<option value="'.$val2['id'].'">　　|一'.$val2['name'] .'</option>';
                                    }
                                }
                            }
                        }
                        ?>
                    </select>
                    <input type="button" value="确认移动" class="btn-light mvPostBtn">
                </span>
            </td>
        </tr>
        <tr class="showpage">
            <th class="name">分页</th>
            <td colspan="8">
                <?php $this->render('pagination');?>
            </td>
        </tr>
    </table>
</div>
