<?php
namespace Library\Core;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 视图处理类
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class View
{

    /**
     * layout布局
     *
     */
    private $_layout = '';

    /**
     * layout下当前action内容模板
     *
     */
    private $_content = '';

    /**
     * set tpl in which module
     */
    private $setTplModule = true;

    /**
     * uri全局处理
     *
     */
    public $uri = null;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->uri = new Uri();
    }

    /**
     * 获取uri对象
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     *
     * @param array $add_arr
     * @param array $rm_arr
     * @param bool $getQueryString
     * @return string
     */
    public function setUrl($add_arr = array(), $rm_arr = array(), $getQueryString = false)
    {
        return $this->uri->setUrl($add_arr, $rm_arr, $getQueryString);
    }

    /**
     * baseUrl映射到view上
     *
     */
    public function baseUrl($uri = '', $setSuffix = true)
    {
        return $this->uri->baseUrl($uri, $setSuffix);
    }

    /**
     *  映射到任意子域名
     *
     * @param $subDomain
     * @param $uri
     * @param bool $setSuffix
     * @return string
     */
    public function subUrl($subDomain, $uri, $setSuffix = true)
    {
        $sub = 'domain.' . $subDomain;
        $subDomain = Config::getItem($sub);

        if (!$subDomain) {
            return $uri;
        }
        $url = 'http://' . $subDomain . '/';

        return $this->uri()->addUri($url, $uri, $setSuffix);
    }

    /**
     * ControllerUrl映射到controller上
     *
     */
    public function moduleUrl($uri = '', $setSuffix = true)
    {
        return $this->uri->getModuleUrl($uri, $setSuffix);
    }


    /**
     * ControllerUrl映射到controller上
     *
     */
    public function controllerUrl($uri = '', $setSuffix = true)
    {
        return $this->uri->getControllerUrl($uri, $setSuffix);
    }

    /**
     * actionUrl映射到controller上
     *
     */
    public function actionUrl($uri = '', $setSuffix = true)
    {
        return $this->uri->getActionUrl($uri, $setSuffix);
    }

    /**
     * render  -- to include template
     *
     * @param $file  模块下的文件路径段名，结尾不能包含 '/'
     * @param bool|string $setTplModule 是否引用模块或直接引用模块的名称
     * @param string $app 所引用的app
     */
    public function render($file, $setTplModule = true, $app = '')
    {
        // path
        $app_path = $this->uri->getAppPath();
        $app_name = rtrim($app_path, APP_NAME);
        $app_path = $app ? $app_name . '/' . $app : $app_path;

        // 判断是否启用当前模块下的试图
        if (is_bool($setTplModule)) {
            $modulename = $setTplModule == true ? $this->uri->getModule() . '/' : '';
        } else {
            // 手动设置的引用模块名称
            $modulename = $setTplModule;
        }

        // file
        $module_path = $modulename ? $app_path . '/view/' . $modulename : $app_path . '/view/';
        $file = $module_path . $file . '.php';
        if (file_exists($file)) {
            include $file;
        }
    }

    /**
     * set layout
     *
     * @param string $layout : 需要使用的布局模块名称
     * @param string $content : 当前action在layout内引用的内容模板
     * @return void;
     */
    /**
     *
     *
     * @param string $layout : 需要使用的布局模块名称
     * @param string $content : 当前action在layout内引用的内容模板
     * @param bool $setTplModule : 是否在当前模块下
     */
    public function setLayout($layout = 'layout', $content = '', $setTplModule = true)
    {
        // set layout
        $this->setTplModule = $setTplModule;
        $this->_layout = $layout ? $layout : 'layout';
        $this->_content = $content;
    }

    /**
     * set layout content
     *
     * @param string $content : 当前action在layout内引用的内容模板
     * @param bool $tplModule
     */
    public function setContent($content = '', $tplModule = true)
    {
        $this->setTplModule = $tplModule;
        $this->_content = $content;
    }

    /**
     * 禁用layout
     *
     */
    public function disableLayout()
    {
        $this->_layout = null;
    }

    /**
     * set layout
     *
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * include layout content
     *
     * 说明 : 加载layout布局下的当前action的内容模板,于layout模板内使用
     */
    public function content()
    {
        // 判断是否启用当前模块下的试图
        if (is_bool($this->setTplModule)) {
            $modulePath = $this->setTplModule == true ? $this->uri->getModule() . '/' : '';
        } else {
            // 手动设置的引用模块名称
            $modulePath = $this->setTplModule;
        }
        if ($this->_content) {
            include_once $this->uri->getAppPath() . '/view/' . ltrim($modulePath, '/') . $this->_content . '.php';
        } else {
            include_once $this->uri->getAppPath() . '/view/' . ltrim($modulePath, '/') . $this->uri->getController() . '/' . $this->uri->getAction() . '.php';
        }
    }

    /**
     * 模板显示功能
     *
     */
    public function display()
    {
        // 直接载入PHP模板
        if ($this->_layout) {
            $layout = $this->uri->getAppPath() . '/view/' . $this->_layout . '.php';
            if (file_exists($layout)) {
                include_once $layout;
            } else {
                if (Config::getBase('debug')) {
                    echo 'layout文件不存在：' . $layout;
                }
                echo Config::getBase('debug');
            }
        }
    }

    public function get($_name, $default = null, $filter = NULL)
    {
        $data = isset($_GET[$_name]) ? $_GET[$_name] : $default;
        if (!is_null($data) && is_int($filter) && $filter > 0) {
            return filter_var($data, $filter);
        } else {
            return $data;
        }
    }

    public function post($_name, $default = null, $filter = NULL)
    {
        $data = isset($_POST[$_name]) ? $_POST[$_name] : $default;
        if (!is_null($data) && is_int($filter) && $filter > 0) {
            return filter_var($data, $filter);
        } else {
            return $data;
        }
    }

    public function request($_name, $default = null, $filter = NULL)
    {
        $data = isset($_REQUEST[$_name]) ? $_REQUEST[$_name] : $default;
        if (!is_null($data) && is_int($filter) && $filter > 0) {
            return filter_var($data, $filter);
        } else {
            return $data;
        }
    }
}