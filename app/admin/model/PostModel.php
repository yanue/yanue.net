<?php
/**
 * PostModel.php
 *
 * @author     yanue <yanue@outlook.com>

 * @time     2013-11-15
 */

namespace App\Admin\Model;

use Library\Core\PDOModel;

class PostModel extends PDOModel
{
    private $_post = 'post';
    private $_post_cat = 'post_cat';
    private $_post_tag = 'post_tags';

    /**
     * get post cat by id
     *
     * @param $id
     * @return mixed
     */
    public function getCats($id)
    {
        $q = $this->from($this->_post_cat)->where('id', $id);
        return $q->fetch();
    }

    public function getCategoryById($id)
    {
        $q = $this->from($this->_post_cat)->where('id', $id);
        return $q->fetch('name');
    }

    /**
     * has same record in where condition
     *
     * @param $where
     * @return mixed
     */
    public function isSame($where)
    {
        $q = $this->from($this->_post)->where($where)->select(null)->select('count(*) as count');
        return $q->fetch('count');
    }

    /**
     * get all post cat
     *
     * @return array
     */
    public function getAllCats()
    {
        $q = $this->from($this->_post_cat)->orderBy('id asc');
        return $q->fetchAll();
    }

    /**
     * get new by id
     *
     * @param $id
     * @return mixed
     */
    public function getPost($id)
    {
        $q = $this->from($this->_post)->where('id', $id);
        return $q->fetch();
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

    /**
     * add post cat
     *
     * @param $data
     * @return int
     */
    public function addPostCat($data)
    {
        $res = $this->insertInto($this->_post_cat, $data);
        return $res->execute();
    }

    /**
     * update post cat
     *
     * @param $data
     * @param $where
     * @return \Library\Fluent\PDOStatement
     */
    public function updatePostCat($data, $where)
    {
        $query = $this->update($this->_admin_group)->set($data)->where($where);
        return $query->execute();
    }

    /**
     * add post
     *
     * @param $data
     * @return int
     */
    public function addPost($data)
    {
        $res = $this->insertInto($this->_post, $data);
        return $res->execute();
    }

    /**
     * update post
     *
     * @param $data
     * @param $where
     * @return \Library\Fluent\PDOStatement
     */
    public function updatePost($data, $where)
    {
        $query = $this->update($this->_post)->set($data)->where($where);
        return $query->execute();
    }

    /**
     * delete post
     * @param $where
     * @return \Library\Fluent\PDOStatement
     */
    public function delPost($where)
    {
        $query = $this->deleteFrom($this->_post)->where($where);
        return $query->execute();
    }

    public function updateCats($data, $where)
    {
        $query = $this->update($this->_post_cat)->set($data)->where($where);
        return $query->execute();
    }

    public function addCat($data)
    {
        $query = $this->insertInto($this->_post_cat, $data);
        return $query->execute();
    }

    public function delCat($id)
    {
				// check child cats
        $q = $this->from($this->_post_cat)->where('parent_id = ' . $id)->select(null)->select('id');
        $ret = $q->fetchAll();
        $wherein = '  in ( ' . $id;
        foreach ($ret as $child) {
            $wherein .= ',' . $child['id'];
        }
        $wherein .= ')';
//        echo $where;
        $query = $this->update($this->_post)->set(array('cid' => 0))->where(' cid ' . $wherein);
//        echo $query->getQuery();
        $query->execute();
        // final del
        $del = $this->deleteFrom($this->_post_cat)->where(' id ' . $wherein);
        $res = $del->execute();
        return $res;
    }

    public function mvCat($cid, $toCid)
    {
        // check child cats
        $q = $this->from($this->_post_cat)->where('parent_id = ' . $cid)->select(null)->select('id');
        $ret = $q->fetchAll('id');
        $inWhere = ' in ( ' . $cid;
        foreach ($ret as $child) {
            $inWhere .= ',' . $child['id'];
        }
        $inWhere .= ')';

        // update current cat and child to new parent
        $query = $this->update($this->_post_cat)->set(array('parent_id' => $toCid))->where('id ' . $inWhere);
        return $query->execute();
    }


    public function addTag($data)
    {
        $query = $this->insertInto($this->_post_tag, $data);
        return $query->execute();
    }

    public function upTagCount($where, $action = ' + 1')
    {
        $sql = 'update ' . $this->_post_tag . ' set count=count' . $action . ' where ' . $where;
        $res = $this->getPdo()->exec($sql);
        return $res;
    }

    public function getAllTags($where = null)
    {
        $q = $this->from($this->_post_tag)->where($where);
        return $q->fetchAll();
    }
}