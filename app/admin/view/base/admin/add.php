<script type="text/javascript">
    seajs.use('app/admin/admin.manage',function(api){
        api.addAdminUser('#infoBtn');
    });
</script>
<h2 class="title">添加管理员</h2>
<?php
$userInfo = $this->userInfo;

if($userInfo['level'] == 0){
?>
<form class="form" id="addAdminForm" action="javascript:;">
    <p><span class="rowspan">用户名</span> <input type="text" class="txt validate[required,minSize[3],maxSize[15],custom[onlyLetterNumber]]" id="user" value=""><span id="status">用于登陆</span></p>
    <p><span class="rowspan">真实姓名</span> <input type="text" class="txt" id="true_name" value=""> <span id="status"></span> </p>
    <p><span class="rowspan">邮箱</span> <input type="text" class="txt validate[custom[email]]" id="email" value=""> <span id="status"></span></p>
    <p><span class="rowspan">输入密码</span> <input type="text" class="txt" id="newPasswd" name="newPasswd" value=""> </p>
    <p class="action">
        <span class="rowspan"></span>
        <input type="submit" class="deep_btn" id="infoBtn" value="提交">
        <input type="reset" class="deep_btn" value="重置">
    </p>
</form>
<?php
}else{
    echo '对不起，你没有权限！';
}
?>