<script type="text/javascript">
    seajs.use('app/admin/admin.manage',function(api){
        api.adminLogin('#loginDo','<?php echo $this->referer;?>');
	});
</script>
<div style="width:360px;margin:120px auto;border:1px solid #ccc;padding:60px 30px;background:;">
    <p style="text-align:center;font-family:'Microsoft Yahei';">管理员登陆</p>
    <form action="javascript:;" id="loginForm" class="form" style="" method="post">
        <p><span class="rowspan">用户名：</span> <input type="text" class="txt" id="user" value=""> <span id="status"></span></p>
        <p><span class="rowspan">密 码：</span> <input type="password" class="txt" id="passwd"> <span id="status"></span></p>
        <p class="action">
            <span class="rowspan"></span>
            <input type="submit" value="登陆" id='loginDo' class="deep_btn">
            <input type="reset" value="重置" class="deep_btn">
        </p>
    </form>
</div>