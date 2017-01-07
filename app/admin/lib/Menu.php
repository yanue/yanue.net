<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 11/14/13
 * Time: 10:12 AM
 */

namespace App\Admin\Lib;

use Library\Core\View;

!defined('ADMIN_MENU_MINE') && define('ADMIN_MENU_MINE', 0);
!defined('ADMIN_MENU_NEWS') && define('ADMIN_MENU_NEWS', 2);
!defined('ADMIN_MENU_GLOBAL') && define('ADMIN_MENU_GLOBAL', 1);
!defined('ADMIN_MENU_PAGE') && define('ADMIN_MENU_PAGE', 4);

class Menu
{

    private static $topMenus = array(
        ADMIN_MENU_MINE => '我的面板',
        ADMIN_MENU_NEWS => '文章管理',
        ADMIN_MENU_PAGE => '静态页面',
        ADMIN_MENU_GLOBAL => '其他设置',
    );

    private static $navMenus = array(
        array('cid' => ADMIN_MENU_MINE, 'title' => '欢迎页', 'm' => '', 'c' => 'index', 'a' => 'index'),
        array('cid' => ADMIN_MENU_MINE, 'title' => '首页焦点图', 'm' => 'base', 'c' => 'global', 'a' => 'mainFocus'),
        array('cid' => ADMIN_MENU_MINE, 'title' => '文章焦点图', 'm' => 'base', 'c' => 'global', 'a' => 'postFocus'),
        array('cid' => ADMIN_MENU_MINE, 'title' => '首页分类图', 'm' => 'base', 'c' => 'global', 'a' => 'mainPlate'),
        array('cid' => ADMIN_MENU_MINE, 'title' => '我的信息', 'm' => 'base', 'c' => 'my', 'a' => 'index'),
        array('cid' => ADMIN_MENU_MINE, 'title' => '修改资料', 'm' => 'base', 'c' => 'my', 'a' => 'update'),

        // GLOBAL
        array('cid' => ADMIN_MENU_GLOBAL, 'title' => '数据备份', 'm' => 'base', 'c' => 'data', 'a' => 'backup'),
        array('cid' => ADMIN_MENU_GLOBAL, 'title' => '数据恢复', 'm' => 'base', 'c' => 'data', 'a' => 'restore'),
        array('cid' => ADMIN_MENU_GLOBAL, 'title' => '管理员列表', 'm' => 'base', 'c' => 'admin', 'a' => 'index'),
        array('cid' => ADMIN_MENU_GLOBAL, 'title' => '修改管理员', 'm' => 'base', 'c' => 'admin', 'a' => 'info', 'self' => true),
        array('cid' => ADMIN_MENU_GLOBAL, 'title' => '添加管理员', 'm' => 'base', 'c' => 'admin', 'a' => 'add'),

        // post
        array('cid' => ADMIN_MENU_NEWS, 'title' => '文章列表', 'm' => '', 'c' => 'post', 'a' => 'list'),
        array('cid' => ADMIN_MENU_NEWS, 'title' => '搜索文章', 'm' => '', 'c' => 'post', 'a' => 'search'),
        array('cid' => ADMIN_MENU_NEWS, 'title' => '文章分类', 'm' => '', 'c' => 'post', 'a' => 'cat'),
        array('cid' => ADMIN_MENU_NEWS, 'title' => '添加文章', 'm' => '', 'c' => 'post', 'a' => 'add'),
        array('cid' => ADMIN_MENU_NEWS, 'title' => '修改文章', 'm' => '', 'c' => 'post', 'a' => 'update', 'self' => true),

        // 静态页面
        array('cid' => ADMIN_MENU_PAGE, 'title' => '页面列表', 'm' => 'base', 'c' => 'page', 'a' => 'list'),
        array('cid' => ADMIN_MENU_PAGE, 'title' => '添加页面', 'm' => 'base', 'c' => 'page', 'a' => 'add'),
        array('cid' => ADMIN_MENU_PAGE, 'title' => '修改页面', 'm' => 'base', 'c' => 'page', 'a' => 'update', 'self' => true),
    );

    /**
     * init menus for view
     *
     * @param View $view
     */
    public static function init(View $view)
    {
        // set for view
        $view->navMenus = self::$navMenus;
        $view->topMenus = self::$topMenus;

        $controller = $view->controller;
        $action = $view->action;
        $view->curMenu = 0;
        foreach (self::$navMenus as $menu) {
            if ($menu['c'] == $controller && $menu['a'] == $action) {
                $view->curMenu = $menu['cid'];
            }
        }
    }


}