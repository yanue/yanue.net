<?php
$userInfo = $this->userInfo;
?>
<h2 class="title">基本信息 <span class="normal">[ <a href="<?php echo $this->controllerUrl('update');?>">修改</a> ]</span> </h2>
<div>
    <p><span class="rowspan">用户名</span> <?php echo $userInfo['user_name']; ?></p>
    <p><span class="rowspan">真实姓名</span> <?php echo $userInfo['true_name']; ?></p>
    <p><span class="rowspan">邮箱</span> <?php echo $userInfo['email']; ?></p>
</div>
<!--
<h2 class="title">统计信息</h2>
<div>
    <p><span class="rowspan">登陆次数</span> <?php echo $userInfo['login_count']; ?> 次</p>
    <p><span class="rowspan">最近登录</span> <?php echo date("Y-m-d H:i:s",$userInfo['last_login']); ?></p>
    <p><span class="rowspan">最后登录IP</span> <?php echo $userInfo['last_ip']; ?></p>
</div>
-->