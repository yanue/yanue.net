<?php

namespace App\Admin\Controller\Base;


use App\Admin\Controller\AppController;
use App\Admin\Lib\Authentication;
use App\Admin\Lib\DbManage;
use App\Admin\Lib\Menu;
use Library\Core\Config;
use App\Admin\Model\AdminModel;

class DataController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        Menu::init($this->view);
        $this->view->setLayout();
        // get uid
        $this->auth = new Authentication($this->view);
        $this->view->sign = $this->auth->createSign();

        $adminModel = new AdminModel();
        $this->view->userInfo = $adminModel->getUserInfo($this->uid);
    }

    public function indexAction()
    {


    }

    public function backupAction()
    {
        // 记录当前action

        // 加载备份类
        $dbConnent = Config::getSite('database');
        $dbConnent = $dbConnent['db.drivers.mysql'];
        // 数据库信息
        $host = $dbConnent['host'];
        $user = $dbConnent['user'];
        $pass = $dbConnent['pass'];
        $dbname = $dbConnent['name'];
        // 初始化导入导出类
        $dbManage = new DbManage($host, $user, $pass, $dbname, 'utf8');
        // 返回上一层目录
        $dir = WEB_ROOT . '/data/';
        // 取得实际路径
        $dir = realpath($dir) . '/backup/' . date('Ymd') . '_' . rand(1000, 9999) . '/';
        $this->view->msg = "<p style='color: #f00;'>请从下面选择备份选项及需要备份的表，然后点击备份按钮即可</p>";
        if (empty ($_POST ['backdo']) || empty ($_POST ['backup'])) {
            $this->view->done = false;
            // 获取所有数据库的表
            $tables = $dbManage->getTables();
            $this->view->tables = $tables;
        } else {
            // 提交数据进行备份

            // 备份全部
            if ($_POST ['backup'] == 'all') {
                // 是否完成备份
                if ($dbManage->backup(null, $dir, null)) {
                    // 没错误
                    $this->view->done = true;
                } else {
                    $this->view->done = false;
                }
            } else {
                // $tables = array ();
                $tables = $_POST ['tables'];
                foreach ($tables as $table) {
                    // 是否完成备份
                    if ($dbManage->backup($table, $dir, null)) {
                        // 没错误
                        $this->view->done = true;
                    } else {
                        $this->view->done = false;
                    }
                }
            }

            $this->view->msg = $dbManage->msg;
        }
    }

    public function restoreAction()
    {

        $dir = WEB_ROOT . '/data/';
        $bakDir = realpath($dir) . '/backup/';
        $this->view->bakDir = $bakDir;
        $this->view->msg = "<p style='color:#f00'>请从下面选择相应的版本和sql文件，然后点击导入即可</p>";
        if (isset($_POST ['sumbit'])) {
            $dir = $_POST ['bakdir'];
            $sqlFiles = $_POST ['sqlfiles'];
            if (empty ($dir) || empty ($sqlFiles)) {
                $this->view->done = false;
                $this->view->error = "<span style='color:#f00'>请从下面选择你要导入的sql版本和文件！</span>";
            } else {
                // 加载备份类
                $dbConnent = Config::getSite('database');
                $dbConnent = $dbConnent['db.drivers.mysql'];
                // 数据库信息
                $host = $dbConnent['host'];
                $user = $dbConnent['user'];
                $pass = $dbConnent['pass'];
                $dbname = $dbConnent['name'];
                // 初始化导入导出类
                $dbManage = new DbManage ($host, $user, $pass, $dbname, 'utf8');
                foreach ($sqlFiles as $sqlFile) {
                    // 是否完成备份
                    if ($dbManage->restore($bakDir . $sqlFile)) {
                        // 没错误
                        $this->view->done = true;
                    } else {
                        $this->view->done = false;
                    }
                }
                $this->view->done = true;
                $this->view->msg = $dbManage->msg;
            }
        }
    }

} 