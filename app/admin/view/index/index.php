<?php
$topMenus = $this->topMenus;
$navMenus = $this->navMenus;
?>
<div id="welcome" style="padding: 10px;">
    <h1 class="title">欢迎进入后台管理中心！</h1>

    <p id='userinfo'>
        <?php
        $user = \Library\Util\Session::instance()->get('_CUSR');
        $uid = \Library\Util\Session::instance()->get('_CUID');
        ?>
        当前用户：<a
            href="<?php echo $this->baseUrl('admin/my/index') ?>"><?php
            if (!empty ($user)) {
                echo $user;
            } else {
                echo "请登陆";
            }
            ?>
        </a>
        |
        <a
            href="<?php echo $this->baseUrl('admin/my/update') ?>">修改个人信息</a>
        | <a href="<?php echo $this->baseUrl('admin/login/logout') ?>">退出登录</a>
    </p>

    <div id="daohang">
        <h2 class="title">快捷导航</h2>
        <table class="list">
            <?php
            foreach ($topMenus as $key => $cat) {
                ?>
                <tr class="<?php echo 'nav' . $key; ?>">
                    <th class="name">
                        <?php echo $cat; ?>
                    </th>
                    <td class="menuList" data-cid="<?php echo $key; ?>">
                        <?php
                        foreach ($navMenus as $menu) {
                            // 0 不隐藏   1 隐藏 2 当前action时不隐藏
                            if ($menu['cid'] == $key) {
                                $current = $menu['c'] == $this->controller && $menu['a'] == $this->action ? 'current' : '';
                                if (empty($menu['self']) || $current) {
                                    $url = !empty($menu['self']) && $current ? '' : $this->moduleUrl($menu['c'] . '/' . $menu['a']);
                                    ?>
                                    <a href="<?php echo $url; ?>"
                                       class="<?php echo $current; ?>"><?php echo $menu['title']; ?></a>　
                                <?php
                                }
                            }
                        } ?>
                    </td>

                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="clear"></div>
    <div class="sys">
        <h2 class="title">系统信息</h2>

        <p>php版本:<?php echo PHPVERSION(); ?></p>

        <p>服务器IP:<?php echo GetHostByName($_SERVER["SERVER_NAME"]); ?></p>

        <p>服务器主机名:<?php echo $_SERVER['SERVER_NAME']; ?></p>

        <p>服务器端口:<?php echo $_SERVER['SERVER_PORT']; ?></p>

        <p>服务器引擎:<?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>

        <p>通信协议:<?php echo $_SERVER['SERVER_PROTOCOL']; ?></p>

        <p>网站位置:<?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>

        <p>你的浏览器信息:<?php echo $_SERVER['HTTP_USER_AGENT']; ?></p>
    </div>
    <div class="sys">
        <h2 class="title">技术支持</h2>

        <p>
            <a href="http://yanue.net/" target='_blank'>yanue</a> | <a href="">查看帮助</a></a>
        </p>
    </div>
</div>
