<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 14-2-9
 * Time: 上午12:41
 */

namespace App\Blog\model;


use Library\Core\PDOModel;

class PostModel extends PDOModel
{
    private $_post = 'post';
    private $_post_cat = 'post_cat';
    private $_post_tags = 'post_tags';
    private $_post_to_tag = 'post_to_tag';

    public function getRecommend($n = 2)
    {
        $q = $this->from($this->_post)->where(array('published' => 1, 'recommend' => 1))->limit($n)->orderBy('created desc,views desc');
        return $q->fetchAll();
    }


    public function getById($id)
    {
        $res = $this->from($this->_post)->where(array('id' => $id))->fetch();
        if (!$res) return false;
        if (!empty($res['cid'])) {
            $cat = $this->getCatById($res['cid']);
            $res['category'] = $cat;
        } else {
            $res['category'] = array('name' => '默认分类', 'alias' => 'default');
        }
        return $res;
    }

    public function getByWpId($wp_id)
    {
        $res = $this->from($this->_post)->where(array('wp_id' => $wp_id))->fetch();
        return $res;
    }


    public function getTags($post_id)
    {
        $res = $this->from($this->_post_tags)->where(array('post_id' => $post_id))->fetchAll();
        return $res;
    }

    public function getTagById($id)
    {
        $res = $this->from($this->_post_tags)->where(array('id' => $id))->fetch();
        return $res;
    }

    public function getTagByName($name)
    {
        $res = $this->from($this->_post_tags)->where(array('name' => $name));
        return $res->fetch();
    }

    public function getAllTags($n = 100)
    {
        $res = $this->from($this->_post_tags)->limit($n)->orderBy('count desc')->fetchAll();
        return $res;
    }

    public function updateClick($id)
    {
        $query = 'update ' . $this->_post . ' set views=views+1 where id = ' . $id;
        return $this->getPdo()->exec($query);
    }

    public function getNext($id, $cid = 0)
    {
        if ($cid) {
            $cid = "and cid=$cid ";
        } else {
            $cid = null;
        }
        $res = $this->findOne('SELECT * FROM ' . $this->_post . ' WHERE published = 1 and id > :id ' . $cid . ' order by id asc limit 1', array(
            'id' => $id
        ));
        return $res;
    }

    public function getPrev($id, $cid = 0)
    {
        if ($cid) {
            $cid = "and cid=$cid ";
        } else {
            $cid = null;
        }
        $result = $this->findOne('SELECT * FROM ' . $this->_post . ' WHERE published = 1 and id < :id ' . $cid . ' order by id desc limit 1', array(
            'id' => $id
        ));
        return $result;
    }

    /**
     * get cat info by id
     */
    public function getCatById($cid)
    {
        // get cat info
        $q = $this->from($this->_post_cat)->where('id = ' . intval($cid));
        return $q->fetch();
    }

    public function getSubCats($cid)
    {
        if (intval($cid)) {
            $q = $this->from($this->_post_cat)->where('parent_id = ' . intval($cid));
            return $q->fetch();
        }
        // get cat info
        return [];
    }

    public function getCatByName($name)
    {
        $res = $this->from($this->_post_cat)->where(array('name' => $name));

        return $res->fetch();
    }

    public function getCatByAlias($alias)
    {
        $res = $this->from($this->_post_cat)->where(array('alias' => $alias));
        return $res->fetch();
    }

    /**
     * get cat info by id
     */
    public function getAllCats()
    {
        // get cat info
        $q = $this->from($this->_post_cat)->orderBy('id asc');
        return $q->fetchAll();
    }

    public function getByKeyword($keyword, $page, $limit = 10, $sortBy = null)
    {
        $offset = $page * $limit . ', ' . $limit;
        $condition = "CONCAT(`title`,`sub_title`,`content`) LIKE '%$keyword%'";
        $query = $this->from($this->getTable())->select(null)->select('id,title,add_time')
            ->where($condition)
            ->limit($offset);
        if ($sortBy) {
            $query->orderBy($sortBy);
        }
        $ret = $query->fetchAll();
        return $ret;
    }

    /**
     * get post list
     *
     * @param $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getPostList($where = null, $page = 0, $limit = 20)
    {
        $limit = $page * $limit . ',' . $limit;
        $count = $this->from($this->_post)->select(null)->select('count(id) as count')->where($where)->fetch('count');
        $q = $this->from($this->_post)->where($where)->limit($limit)->order('created desc');
        $res = $q->fetchAll();
        foreach ($res as &$val) {
            $val['category'] = $this->getCategoryById($val['cid']);
        }

        return array('count' => intval($count), 'data' => $res);
    }

    public function getByKeywordCount($keyword)
    {
        $condition = "CONCAT(`title`,`sub_title`,`content`) LIKE '%$keyword%'";
        $query = $this->from($this->getTable())->select(null)->select("count(0) as count")
            ->where($condition)
            ->limit(10);
        $ret = $query->fetch('count');
        return $ret;
    }


} 