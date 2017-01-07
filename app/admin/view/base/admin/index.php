<?php
$users = $this->admins;
?>
<div class="" id=''>
    <table class='list'>
        <caption>
            <b class="captitle">管理员列表</b>
        </caption>

        <tr class="head">
            <th width='36px'>ID</th>
            <th width='120px'>用户名</th>
            <th width=''>真实姓名</th>
            <th width='120px'>管理级别</th>
            <th width='112px'>最近登陆</th>
            <th width=''>操作</th>
        </tr>
        <tbody class="listData">
        <?php
        foreach($users as $user){
            ?>
        <tr class="row" chk_id=<?php echo $user['uid'];?> >
            <th class='name'><?php echo $user['uid'];?></th>
            <td>
                <a  href="<?php echo $this->ControllerUrl('/info/uid/'.$user['uid']); ?>" ><?php echo $user['user_name']; ?></a>
            </td>
            <td><?php echo $user['true_name']; ?></td>
            <td>

            </td>
            <td><?php echo \App\Admin\Lib\Func::formatTime($user['last_login']);?></td>
            <td>
                <a href="<?php echo $this->uri->getControllerUrl().'/info/uid/'.$user['uid'];?>" class='upBtn'>修改信息</a>
            </td>
        </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
