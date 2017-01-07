<?php

namespace App\Admin\Controller\Base;

use App\Admin\Controller\AppController;
use App\Admin\Lib\Paged;
use App\Admin\Model\PageModel;

class PageController extends AppController
{

    /**
     * admin list
     */
    public function listAction()
    {
        $page = $this->uri->getParam('p', 0);
        $curpage = $page <= 0 ? 0 : $page - 1;
        $limit = 16;

        $postModel = new PageModel();
        $res = $postModel->getPageList(array(), $curpage, $limit);
        $p = new Paged($this->view);
        $p->showPage($page, $res['count'], $limit);
        $this->view->data = $res['data'];
    }

    /**
     * add admin user
     */
    public function addAction()
    {
    }


    /**
     * show admin user info
     * -- change info form
     */
    public function updateAction()
    {
        $id = $this->uri->getParam('id', 0);

        $postModel = new PageModel();
        $data = $postModel->getById($id);

        $this->view->item = $data;
    }


}