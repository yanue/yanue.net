<?php
$userInfo = $this->userInfo;
$users = $this->users;
?>
<script type="text/javascript">
    seajs.use('app/admin/admin.manage',function(api){
        api.updateAdmin('#infoBtn');
        api.resetPasswd('#passwdBtn');
    });
</script>
<h1 class="ctitle">修改管理员资料</h1>
<?php
if($userInfo['level'] == 0){
?>
<h2 class="title">
    选择管理员：
    【<?php
    foreach($users as $user ){
        ?>
        <a href="<?php echo $this->actionUrl('uid/' . $user['uid']); ?>" style="<?php echo $userInfo['uid'] == $user['uid'] ? 'color:red;' : '' ;?>"><?php echo $user['user_name'];?></a> |
    <?php
    }
    ?>
    】
</h2>
<h2 class="title">基本信息</h2>
<form class="form" id="updateAdminForm" action="javascript:;">
    <p><span class="rowspan">用户名</span> <input type="text" class="txt " id="user" value="<?php echo $userInfo['user_name']; ?>" >
        <span id="status">用于登陆</span></p>
    <p><span class="rowspan">真实姓名</span> <input type="text" class="txt" id="true_name" value="<?php echo $userInfo['true_name']; ?>"> <span id="status"></span> </p>
    <p><span class="rowspan">邮箱</span> <input type="text" class="txt" id="email" value="<?php echo $userInfo['email']; ?>"> <span id="status"></span></p>
    <p class="action">
        <span class="rowspan"></span>
        <input type="submit" class="deep_btn" id="infoBtn" uid="<?php echo $userInfo['uid'];?>" value="提交">
        <input type="reset" class="deep_btn" value="重置">
    </p>
</form>
<h2 class="title">重置密码</h2>
<form class="form" action="javascript:;" id="changePasswdForm">
    <p><span class="rowspan">输入新密码</span> <input type="text" class="txt" id="newPasswd" name="newPasswd" value=""> </p>
    <p class="action">
        <span class="rowspan"></span>
        <input type="submit" class="deep_btn" id="passwdBtn" uid="<?php echo $userInfo['uid'];?>"  value="提交">
        <input type="reset" class="deep_btn" value="重置">
    </p>
</form>
<?php
}else{
    echo '对不起，你没有权限！';
}
?>