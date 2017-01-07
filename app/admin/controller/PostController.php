<?php

namespace App\Admin\Controller;

use App\Admin\Lib\Paged;
use App\Admin\Model\PostModel;
use Library\Core\Config;

class PostController extends AppController
{

    /**
     * admin list
     */
    public function listAction()
    {
        $page = $this->uri->getParam('p', 0);
        $cid = $this->uri->getParam('cid');
        $where = is_numeric($cid) && $cid >= 0 ? ' cid = ' . $cid : '';
        $curpage = $page <= 0 ? 0 : $page - 1;
        $limit = 16;

        $postModel = new PostModel();
        $cats = $postModel->getAllCats();
        $res = $postModel->getPostList($where, $curpage, $limit);
        $p = new Paged($this->view);
        $p->showPage($page, $res['count'], $limit);
        $this->view->post = $res['data'];
        $this->view->cats = $cats;
    }

    /**
     * add admin user
     */
    public function addAction()
    {
        $postModel = new PostModel();
        $cats = $postModel->getAllCats();
        $tags = $postModel->getAllTags();

        $this->view->tags = $tags;
        $this->view->cats = $cats;
    }


    /**
     * show admin user info
     * -- change info form
     */
    public function updateAction()
    {
        $id = $this->uri->getParam('id', 0);

        $postModel = new PostModel();
        $post = $postModel->getPost($id);
        $tags = $postModel->getAllTags();
        $cats = $postModel->getAllCats();
//print_r($post['content']);
        $this->view->cats = $cats;
        $this->view->tags = $tags;
        $this->view->post = $post;
    }

    /**
     * category
     */
    public function catAction()
    {
        $postModel = new PostModel();
        $cats = $postModel->getAllCats();
        $this->view->cats = $cats;
    }

    /**
     * admin list
     */
    public function searchAction()
    {
        $page = $this->uri->getParam('p', 0);
        $key = trim(filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING));

        $curpage = $page <= 0 ? 0 : $page - 1;
        $limit = 16;

        $count = 0;
        $this->view->post = null;
        $this->view->cats = null;
        if ($key) {
            $where = ' title like "%' . $key . '%" or sub_title  like  "%' . $key . '%"   or content  like  "%' . $key . '%"   or keywords  like  "%' . $key . '%"  ';
            $postModel = new PostModel();
            $cats = $postModel->getAllCats();
            $res = $postModel->getPostList($where, $curpage, $limit);

            $this->view->post = $res['data'];
            $this->view->cats = $cats;
            $count = $res['count'];
        }
        $p = new Paged($this->view);
        $p->showPage($page, $count, $limit);
    }


} 