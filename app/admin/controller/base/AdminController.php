<?php

namespace App\Admin\Controller\Base;

use App\Admin\Controller\AppController;
use App\Admin\Model\AdminModel;

class AdminController extends AppController
{


    /**
     * admin list
     */
    public function indexAction()
    {
        $adminModel = new AdminModel();
        $res = $adminModel->getUserList();

        $this->view->admins = $res;
    }

    /**
     * add admin user
     */
    public function addAction()
    {
        $adminModel = new AdminModel();
        $this->view->userInfo = $adminModel->getUserInfo($this->uid);
    }

    /**
     * show admin user info
     * -- change info form
     */
    public function infoAction()
    {
        $uid = $this->uri->getParam('uid', 0);

        $adminModel = new AdminModel();
        $this->view->users = $adminModel->getUserList();
        $this->view->userInfo = $adminModel->getUserInfo($uid);
    }

} 