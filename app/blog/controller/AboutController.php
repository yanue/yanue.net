<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 14-2-8
 * Time: 下午8:54
 */

namespace App\blog\controller;


use App\Admin\Model\PageModel;
use App\Blog\model\PostModel;

class AboutController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $alias = $this->action;
        $alias = $alias == 'index' ? 'about' : $alias;
        if (in_array($alias, array('about'))) {
            $page = new PageModel();
            $item = $page->getByAlias($alias);
            $this->view->page = $item;
        }
    }

    public function indexAction()
    {
    }

    public function sitemapAction()
    {
        $m = new PostModel();
        $cats = $m->getAllCats();
        $this->view->cats = $cats;
    }

    public function aboutAction()
    {
    }

    public function linksAction()
    {
    }
} 