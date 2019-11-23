<?php
/**
 * PostModel.php
 *
 * @author     yanue <yanue@outlook.com>

 * @time     2013-11-15
 */

namespace App\Admin\Model;

use Library\Core\PDOModel;

class PageModel extends PDOModel
{
    public $table = 'page';
    private $_page = 'page';

    /**
     * has same record in where condition
     *
     * @param $where
     * @return mixed
     */
    public function isSame($where)
    {
        $q = $this->from($this->_page)->where($where)->select(null)->select('count(*) as count');
        return $q->fetch('count');
    }


    /**
     * get new by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        $q = $this->from($this->_page)->where(array('id', $id));
        return $q->fetch();
    }

    public function getByAlias($alias)
    {
        $q = $this->from($this->_page)->where(array('alias', $alias));
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
    public function getPageList($where = null, $page = 0, $limit = 20)
    {
        $limit = $page * $limit . ',' . $limit;
        $count = $this->from($this->_page)->select(null)->select('count(id) as count')->where($where)->fetch('count');
        $q = $this->from($this->_page)->where($where)->limit($limit)->order('created desc');
        $res = $q->fetchAll();

        return array('count' => intval($count), 'data' => $res);
    }

    public function add($data)
    {
        $res = $this->insertInto($this->_page, $data)->ignore();
        return $res->execute();
    }

    public function up($data, $where)
    {
//        print_r($data);
        $query = $this->update($this->_page)->set($data)->where($where);
//        echo $query->getQuery();
        return $query->execute();
    }

    public function del($where)
    {
        if (!$where) {
            return false;
        }
        $query = $this->deleteFrom($this->_page)->where($where);
        return $query->execute();
    }

}