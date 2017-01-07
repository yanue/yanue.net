<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 14-2-9
 * Time: 上午1:19
 */

namespace App\blog\controller;


use App\Blog\Helper\Meta;
use App\Blog\model\PostModel;
use Library\Util\Pagination;
use Library\Util\Validator;

class PostController extends AppController
{

    public function listAction()
    {
        $page = intval($this->uri->getParam('p'));
        if (!$page) {
            $this->noData(ERROR_INVALID_REQUEST_PARAM);
        }
        $page = intval($this->uri->getParam('p'));
        $curpage = $page <= 0 ? 0 : $page - 1;

        $limit = 10;
        $offset = $curpage * $limit;

        $m = new PostModel();
        $where = 'published=1';

        $data = $m->getList($offset, $limit, '', $where, 'created desc');
        $count = $m->count($where);
        if (!$data) { // data is not exists
            $this->noData(ERROR_DATA_NOT_EXISTS);
        }

        $allTage = $m->getAllCats();
        $this->view->list = $data;
        $this->view->cats = $allTage;
        $this->view->setLayout();
        $this->view->title = '';
        Pagination::instance($this->view)->showPage($page, $count, $limit);

    }

    public function detailAction()
    {
        $id = intval($this->uri->getParam('id'));
        if (!$id) {
            $this->noData(ERROR_INVALID_REQUEST_PARAM);
        }

        $m = new PostModel();
        $data = $m->getById($id);

        if (!$data) { // data is not exists
            $this->noData(ERROR_DATA_NOT_EXISTS);
        }

        $m->updateClick($id);
        $this->view->item = $data;
        $this->view->next = $m->getNext($id);
        $this->view->prev = $m->getPrev($id);
        $this->view->relate = $m->getList(0, 12, 'id,title,created', 'cid=' . $data['cid']);

        // meta
        $this->view->title = $data['title'] . ' - ' . Meta::$siteName;
        $this->view->keywords = $data['keywords'] . ' ' . $data['title'] . '-' . @$data['category']['name'] . ' - ' . Meta::$siteName;
        $this->view->description = mb_substr(strip_tags($data['content']), 0, 120, 'utf-8') . @$data['category']['name'] . ' - yanue.net - ' . Meta::$siteName . '‧' . Meta::$subName;

        $this->view->setLayout();
    }

    public function catAction()
    {
        $var = urldecode($this->uri->getParam('var'));
        $page = intval($this->uri->getParam('p'));
        $curpage = $page <= 0 ? 0 : $page - 1;

        $limit = 10;
        $offset = $curpage * $limit;

        if (!is_numeric($var) && !Validator::validateAliasName($var)) {
            $this->noData(ERROR_INVALID_REQUEST_PARAM);
        }
        $m = new PostModel();

        if (!is_numeric($var)) {
            $cat = $m->getCatByAlias($var);
            if (isset($cat['id']) && $cat['id'] > 0) {
                $cid = $cat['id'];
            } else { // 数据不存在
                $cid = 0;
                $this->noData(ERROR_DATA_NOT_EXISTS);
            }

        } else {
            $cid = $var;
            $cat = $m->getCatById($var);
            if (!$cat) {
                $this->noData(ERROR_DATA_NOT_EXISTS);
            }
        }

        $this->view->parent_cat = null;

        // 获取子栏目下的列表
        if ($cat['parent_id'] == 0) {
            $sub_cat = $m->getSubCats($cid);
            if ($sub_cat) {
                $sub_cat_ids = array_column($sub_cat, 'id');
                array_push($sub_cat_ids, $cid);
            } else {
                $sub_cat_ids = [$cid];
            }
        } else {
            $sub_cat_ids = [$cid];
            $parent_cat = $m->getCatById($cat['parent_id']);
            $this->view->parent_cat = $parent_cat;
        }

        $where = 'published=1 and cid in (' . implode(',', $sub_cat_ids) . ' ) ';
        $data = $m->getList($offset, $limit, '', $where, 'created desc');
        $count = $m->count($where);
        $this->view->list = $data;
        $this->view->cat = $cat;

        // meta
        $this->view->title = $cat['name'] . '第' . ($page + 1) . '页 - 分类目录 - ' . Meta::$siteName;
        $this->view->keywords = '网站类目：' . $cat['name'] . ' - 第' . ($page + 1) . '页 - ' . $cat['en_name'] . '-' . $cat['detail'] . '-' . Meta::$siteName;
        $this->view->description = '网站类目：' . $cat['name'] . ' 当前第' . ($page + 1) . '页' . $cat['detail'] . '  - yanue.net - ' . Meta::$siteName . '‧' . Meta::$subName;

        Pagination::instance($this->view)->showPage($page, $count, $limit);
    }

    public function tagAction()
    {
        $var = urldecode($this->uri->getParam('var'));
        $page = intval($this->uri->getParam('p'));
        $curpage = $page <= 0 ? 0 : $page - 1;

        $limit = 10;
        $offset = $curpage * $limit;

        $m = new PostModel();

        if (!is_numeric($var)) {
            $tag = $m->getTagByName($var);
            if (isset($tag['id']) && $tag['id'] > 0) {
                $cid = $tag['id'];
            } else { // 数据不存在
                $cid = 0;
                $this->noData(ERROR_DATA_NOT_EXISTS);
            }
        } else {
            $cid = $var;
            $tag = $m->getTagById($var);
            if (!$tag) {
                $this->noData(ERROR_DATA_NOT_EXISTS);
            }
        }
        $str = 'LOCATE(\'"id":' . $tag['id'] . '\',`tags`) > 0';
        $where = "published=1 and " . $str;
        $data = $m->getList($offset, $limit, '', $where, 'created desc');
        $count = $m->count($where);
        $this->view->list = $data;
        $this->view->cat = $tag;

        // meta
        $this->view->title = $tag['name'] . ' - 文章标签 - ' . Meta::$siteName;
        $this->view->keywords = '文章标签：' . $tag['name'] . ' - 第' . ($page + 1) . '页 - ' . '-' . Meta::$siteName;
        $this->view->description = '文章标签：' . $tag['name'] . ' 当前第' . ($page + 1) . '页  - yanue.net - ' . Meta::$siteName . '‧' . Meta::$subName;

        Pagination::instance($this->view)->showPage($page, $count, $limit);
    }

    public function wpAction()
    {
        $id = intval($this->uri->getParam('id'));
        if (!$id) {
            $this->noData(ERROR_INVALID_REQUEST_PARAM);
        }

        $m = new PostModel();
        $data = $m->getByWpId($id);

        if (!$data) { // data is not exists
            $this->noData(ERROR_DATA_NOT_EXISTS);
        }

        header('HTTP/1.1 301 Moved Permanently'); //发出301头部
        header('Location: ' . $this->baseUrl('post-' . $data['id'])); //跳转到我的新域名地址
    }

    public function tagsAction()
    {
    }

    public function categoryAction()
    {
    }
} 