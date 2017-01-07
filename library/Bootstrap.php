<?php
namespace Library;

use Library\Core\Dispatcher;
use Library\Core\Exception;
use Library\Core\Loader;
use Library\Core\Router;
use Library\Core\Config;
use Library\Core\Debug;

define('VERSION', '2.0.8');

defined('LIB_PATH') || define('LIB_PATH', dirname(__FILE__));
defined('WEB_ROOT') || define('WEB_ROOT', realpath(dirname(__FILE__) . '/..'));

/**
 * 应用入口初始化 - Bootstrap.php
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @time     2013-07-11
 */
class Bootstrap
{

    /**
     * 记录时间和内存使用
     */
    public function __construct()
    {
        $GLOBALS['_startTime'] = microtime(TRUE);
        $GLOBALS['_error_404'] = false; // for set layout

        // 记录内存初始使用
        if (function_exists('memory_get_usage')) $GLOBALS['_startMemory'] = memory_get_usage();
    }

    /**
     * 应用初始化
     *
     */
    public function init()
    {
        // 初始化自动加载
        require_once LIB_PATH . '/core/' . 'Loader.php';
        $loader = new Loader();
        $loader->register();
        Config::load();

        // set time zone
        if (Config::getBase('timezone')) {
            date_default_timezone_set(Config::getBase('timezone'));
        }

        // 错误异常处理
        $this->_errorSetting();

        // 最终运行控制器的方法
        $this->_run();
    }

    /**
     * 错误异常处理
     *
     */
    private function _errorSetting()
    {
        # set display_errors
        ini_set('display_errors', intval(Config::getBase('display_errors')));

        if (Config::getBase('display_errors')) {
            error_reporting(E_ALL);
        }
        $exception = new Exception();
        // 监听内部错误 500 错误
        register_shutdown_function(array($exception, 'shutdown_handle'));
        // 设定错误和异常处理(调试模式有用)
        if (Config::getBase('debug')) {
            set_error_handler(array($exception, 'error_handler'));
            set_exception_handler(array($exception, 'exception_handler'));
        }

    }

    /**
     * 执行控制器并调用方法
     * --命名规则:
     * --骆驼峰命名规则,类名需要首字母大写
     * --控制器: 控制器名称+Controller.php 控制器类名和文件名相同 例: TestController.php,控制器类名:testController
     * --控制器方法: 方法名+action 例: testAction();
     * --控制器文件位于当前模块下的controller目录
     *
     *
     * param $string $modulePath 当前模块目录
     * param $string $controller 当前控制器名称
     * param $string $action 当前方法名称
     * return null
     */
    private function _run()
    {
        // 执行路由
        if ($conig = Config::getRouter()) {
            new Router($conig);
        }
        // 执行分发过程,获取mvc结构
        $disp = new Dispatcher();

        $controller = $disp->getController();
        $action = $disp->getAction();
        $module = $disp->getModule();
        $module_path = $module ? ucfirst($module) . '\\' : '';

        $_namespaceClass = '\App\\' . ucfirst(APP_NAME) . '\Controller\\' . $module_path . ucfirst($controller) . 'Controller';
        $actionName = $action . 'Action';

        // 判断当前请求的控制器,存在则自动加载
        if (class_exists($_namespaceClass, true)) {
            $controllerObj = new $_namespaceClass();
            if (method_exists($controllerObj, $actionName)) {
                //执行action预处理方法
                if (method_exists($controllerObj, 'actionBefore')) {
                    $controllerObj->actionBefore();
                }
                // 执行action方法
                try {
                    $controllerObj->$actionName();
                    $controllerObj->view->display();
                } catch (\Exception $e) {
                    Debug::log($e->getFile() . ':' . $e->getMessage());
                    Debug::log('Trace:' . $e->getTraceAsString());
                }
            } else {
                Debug::log("Action does not exists:" . $_namespaceClass . '->' . $actionName . '()');
                # 方法是否存在404处理
                $this->_error(' : action is not exists');
            }

        } else {
            Debug::log("Controller does not exists:" . $_namespaceClass);
            // 控制器不存在404错误处理
            $this->_error(' : controller is not exists');
        }
    }

    /**
     * 404错误页面显示
     * --判断是否ajax请求
     * --判断是否开启调试
     * --判断默认module下ErrorController->indexAction是否存在
     *
     */
    private function _error($msg = '')
    {
        // if ajax
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest';
        //输出404头信息
        header('HTTP/1.1 404 not found');

        // 判断是否以ajax方式请求
        if ($isAjax) {
            echo json_encode('');
            $GLOBALS['_error_404'] = true; // set error for tpl
        } else {
            // 默认以当前默认module下的ErrorController作为错误显示页面
            $_namespaceClass = '\App\\' . ucfirst(APP_NAME) . '\Controller\\' . 'ErrorController';
            $action = 'indexAction';
            // 模块方式输出,还是直接输出错误信息
            // 判断模板
            if (class_exists($_namespaceClass, true)) {
                $controllerObj = new $_namespaceClass();
                if (method_exists($_namespaceClass, $action)) {
                    $controllerObj->$action();
                    $controllerObj->view->display();
                } else {
                    echo '404 not found' . $msg;
                    $GLOBALS['_error_404'] = true; // set error for tpl
                }
            } else {
                echo '404 not found' . $msg;
                $GLOBALS['_error_404'] = true; // set error for tpl
            }

        }
    }

}