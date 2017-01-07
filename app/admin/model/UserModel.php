<?php
/**
 * UserModel.php
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @time     2013-11-15
 */

namespace App\Admin\Model;

use Library\Core\PDOModel;

class UserModel extends PDOModel
{
    private $_user = 'users';
    private $_brand_cat = 'brand_cat';

    /**
     * get new by id
     *
     * @param $id
     * @return mixed
     */
    public function getUser($id)
    {
        $q = $this->from($this->_user)->where('uid', $id);
        return $q->fetch();
    }

    /**
     * get user list
     *
     * @param $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getUserList($where = null, $page = 0, $limit = 20)
    {
        $limit = $page * $limit . ',' . $limit;
        $count = $this->from($this->_user)->select(null)->select('count(uid) as count')->where($where)->fetch('count');
        $q = $this->from($this->_user)->where($where)->limit($limit)->order('created desc');
        $res = $q->fetchAll();
        if ($res) {
            foreach ($res as &$val) {
                if ($val['interested']) {
                    $val['subscribe_cat'] = $this->getCate(' id in ( ' . $val['interested'] . ' ) ');
                } else {
                    $val['subscribe_cat'] = array();
                }

            }
        }
        return array('count' => intval($count), 'data' => $res);
    }

    public function getUserSendList()
    {
        $sql = 'select * from users where subscribe=1 group by email';
        return $this->findAll($sql);
    }
    

    public function getCate($where)
    {
        $q = $this->from($this->_brand_cat)->where($where);
        return $q->fetchAll();
    }

    /**
     * delete user
     * @param $where
     * @return \Library\Fluent\PDOStatement
     */
    public function delUser($where)
    {
        $query = $this->deleteFrom($this->_user)->where($where);
        return $query->execute();
    }

}