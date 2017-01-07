<?php

namespace App\Admin\Controller\Base;

use App\Admin\Controller\AppController;
use App\Admin\Lib\Paged;
use App\Admin\Lib\UserLog;
use App\Admin\Model\Base\SysLinksModel;
use App\Admin\Model\SiteLinksModel;
use Library\Util\Jsonp;
use Library\Util\Validator;

class LinksController extends AppController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        // get param
        $page = $this->uri->getParam('p', 0);
        $cid = $this->uri->getParam('cid');
        $curpage = $page <= 0 ? 0 : $page - 1;
        $limit = 20;
        $where = is_numeric($cid) && $cid >= 0 ? ' cid = ' . $cid : '';
        // get data
        $m = new SiteLinksModel();
        $data = $m->getList($limit * $curpage, $limit, null, $where);
        $paged = new Paged($this->view);
        $paged->showPage($page, $m->count(), $limit);

        $this->view->data = $data;
        $this->view->setLayout();

    }
}