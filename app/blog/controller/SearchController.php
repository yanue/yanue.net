<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 14-2-8
 * Time: 下午8:54
 */

namespace App\blog\controller;

use App\Admin\Lib\Paged;
use App\Admin\Model\PostModel;

class SearchController extends AppController
{
    public function indexAction()
    {
        $page = $this->uri->getParam('p', 0);
        $key = trim(filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING));

        $curpage = $page <= 0 ? 0 : $page - 1;
        $limit = 16;

        $count = 0;
        $this->view->post = null;
        $this->view->cats = null;
        if ($key) {
            $where = ' title like "%' . $key . '%" or sub_title  like  "%' . $key . '%"   or content  like  "%' . $key . '%"   or keywords  like  "%' . $key . '%"  ';
            $postModel = new PostModel();
            $res = $postModel->getPostList($where, $curpage, $limit);

            $this->view->post = $res['data'];
            $count = $res['count'];
        }
        $p = new Paged($this->view);
        $this->view->count = $count;
        $p->showPage($page, $count, $limit);
    }
}