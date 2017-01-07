<?php
namespace App\Admin\Controller\Base;

use App\Admin\Lib\Authentication;
use App\Admin\Lib\Menu;
use App\Admin\Model\AdminModel;
use Library\Core\Controller;
use Library\Util\Session;

class MyController extends Controller
{

    public $uid = null;

    public function __construct()
    {
        parent::__construct();

        Menu::init($this->view);
        $this->auth = new Authentication($this->view);
        $this->view->sign = $this->auth->createSign();
        $this->uid = Session::instance()->get('_CUID');

        $this->view->setLayout();
    }

    // user index page
    public function indexAction()
    {
        $this->auth->checkUserStatus();
        $adminModel = new AdminModel();
        $this->view->userInfo = $adminModel->getUserInfo($this->uid);
    }

    // user login page
    public function loginAction()
    {
        if ($this->uid > 0) {
            header("location:" . $this->view->moduleUrl('my/index')); //限时跳转
        } else {
            $this->view->referer = $this->get('ref');
        }
    }

    // user info page
    public function updateAction()
    {
        $this->auth->checkUserStatus();
        $adminUserModel = new AdminModel();
        $this->view->userInfo = $adminUserModel->getUserInfo($this->uid);
    }

    // login out
    public function logoutAction()
    {
        unset($_SESSION);
        session_destroy();
        $url = $this->moduleUrl('my/login');
        header('Location:' . $url);
    }

    public function testAction()
    {
        $this->view->setLayout('layout', 'test/upload');
    }
}
