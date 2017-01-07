<?php


namespace App\Blog\Helper;


use App\Admin\Model\SiteLinksModel;
use App\Blog\model\PostModel;

class SideData
{
    public static function getLatest($n = 10)
    {
        $m = new PostModel();
        $list = $m->getList(0, $n, null, array('published' => 1), 'created desc');
        return $list;
    }

    public static function getByComments($n = 10)
    {
        $m = new PostModel();
        $list = $m->getList(0, $n, null, array('published' => 1), 'comments desc');

        return $list;
    }

    public static function getPopular($n = 10)
    {
        $m = new PostModel();
        $list = $m->getList(0, $n, null, array('published' => 1), 'views desc');

        return $list;
    }

    public static function getTags($n = 50)
    {
        $m = new PostModel();
        $list = $m->getAllTags($n);
        return $list;
    }

    public static function getLinks()
    {
        $m = new SiteLinksModel();
        $list = $m->getList();
        return $list;
    }

    public static function getAllCats()
    {
        $m = new PostModel();
        $list = $m->getAllCats();
        return $list;
    }

} 