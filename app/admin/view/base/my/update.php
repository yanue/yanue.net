<?php
$userInfo = $this->userInfo;
?>
<h2 class="title">修改个人信息</h2>
<h2 class="title">基本信息</h2>
<script type="text/javascript">
    seajs.use('app/admin/admin.manage',function(api){
        api.updateAdminInfo('#updateAdminForm #btnUpAdmin');
        api.changeAdminPasswd('#changePasswdForm #btnUpPass');
    })
</script>
<form class="form" id="updateAdminForm" action="javascript:;">
    <p><span class="rowspan">用户名</span> <input type="text" class="txt validate[required,minSize[3],maxSize[15],custom[onlyLetterNumber]]" id="user" value="<?php echo $userInfo['user_name']; ?>">
        <span id="status">用于登陆</span></p>
    <p><span class="rowspan">真实姓名</span> <input type="text" class="txt validate[required]" id="true_name" value="<?php echo $userInfo['true_name']; ?>"> <span id="status"></span> </p>
    <p><span class="rowspan">邮箱</span> <input type="text" class="txt validate[custom[email]]" id="email" value="<?php echo $userInfo['email']; ?>"> <span id="status"></span></p>
    <p class="action">
        <span class="rowspan"></span>
        <input type="submit" class="btn-gray" id="btnUpAdmin" value="提交">
        <input type="reset" class="btn-gray" value="重置">
    </p>
</form>
<h2 class="title">修改密码</h2>
<form class="form" action="javascript:;" id="changePasswdForm">
    <p><span class="rowspan">输入原密码</span> <input type="password" class="txt validate[required,minSize[6],maxSize[15]]" id="oldPasswd" value=""> <span id="status"></span></p>
    <p><span class="rowspan">输入新密码</span> <input type="password" class="txt validate[required,minSize[6],maxSize[15]]" id="newPasswd" name="newPasswd" value=""> </p>
    <p><span class="rowspan">重复新密码</span> <input type="password" class="txt validate[required,equals[newPasswd],minSize[6],maxSize[15]] id="new2passwd" value=""></p>
    <p class="action">
        <span class="rowspan"></span>
        <input type="submit" class="btn-gray" id="btnUpPass" value="提交">
        <input type="reset" class="btn-gray" value="重置">
    </p>
</form>