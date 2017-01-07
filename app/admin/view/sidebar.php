<div id="sidebar">
    <div id="user">
        <?php
        if (isset($_SESSION['_CUID']) && $_SESSION['_CUID'] > 0) {
            ?>
            <p>
                后台欢迎您！
            </p>
            <p>
                用户: <a
                    href="<?php echo $this->baseUrl('base/my'); ?>"><?php echo isset($_SESSION['_CUSR']) ? $_SESSION['_CUSR'] : '请先登陆'; ?></a>
            </p>
            <p>
                [ <a href="<?php echo $this->baseUrl('base/my/update'); ?>">修改</a> ]
                [ <a href="<?php echo $this->baseUrl('base/my/logout'); ?>">退出</a> ]
            </p>
        <?php } else { ?>
            <p style="text-align:center;padding:10px 0 0 0;line-height:24px;font-size:14px;font-family:'Microsoft YaHei';">
                您还没有登录！</p>
            <p style="text-align:center;"><a href="<?php echo $this->baseUrl('base/my/login'); ?>">请先登录</a></p>
        <?php } ?>
    </div>
    <?php
    $topMenus = $this->topMenus;
    $navMenus = $this->navMenus;
    ?>
    <div id="sideMenu">
        <script>
            seajs.use('app/admin/admin.base', function (common) {
                common.expandMenu();
            })
        </script>

        <div id="menu">
            <?php
            foreach ($topMenus as $key => $cat) {
                ?>
                <div class="<?php echo 'nav' . $key; ?>">
                    <dl>
                        <dt class="menuTitle" data-cid="<?php echo $key; ?>"><?php echo $cat; ?></dt>
                        <dd class="menuList" data-cid="<?php echo $key; ?>"
                            style="display: <?php echo $this->curMenu == $key ? 'bloak' : 'none'; ?>">
                            <?php
                            foreach ($navMenus as $menu) {
                                // 0 不隐藏   1 隐藏 2 当前action时不隐藏
                                if ($menu['cid'] == $key) {
                                    $current = $menu['c'] == $this->controller && $menu['a'] == $this->action ? 'current' : '';
                                    if (empty($menu['self']) || $current) {
                                        $module = $menu['m'] ? $menu['m'] . '/' : '';

                                        $url = !empty($menu['self']) && $current ? '' : $this->baseUrl($module . $menu['c'] . '/' . $menu['a']);
                                        ?>
                                        <p>
                                            <a href="<?php echo $url; ?>" class="<?php echo $current; ?>">
                                                <?php echo $menu['title']; ?>
                                            </a>
                                        </p>
                                    <?php
                                    }
                                }
                            } ?>
                        </dd>
                    </dl>
                </div>
            <?php } ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
