<?php
/**
 * PostModel.php
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @time     2013-11-15
 */

namespace App\Admin\Model;

use Library\Core\PDOModel;

class RegionModel  extends PDOModel{
    //use table  mall mall_district mall_nearby mall_nearby_attr

    public $table = "region";
    /**
     * get post cat by id
     *
     * @param $id
     * @return mixed
     */
    public  function getById( $id ){
        $q = $this->from($this->getTable())->where('id',$id);
        return $q->fetch();
    }
    
    /**
     * get all mall
     *
     * @return array
     */
    public function getAll(){
        $q = $this->from($this->getTable());
        return $q->fetchAll();
    }


    /**
     * get post list
     *
     * @param $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getPageList($where=null, $page=0,$limit = 20){
        $limit = $page*$limit.','.$limit;
        $count = $this->from($this->getTable())->select(null)->select('count(id) as count')->where($where)->fetch('count');
        $q = $this->from($this->getTable())->where($where)->limit($limit);
        $res = $q->fetchAll();
        return array('count'=>intval($count),'data'=>$res);
    }

    /**
     * add post
     *
     * @param $data
     * @return int
     */
    public function add($data){
        $res = $this->insertInto($this->getTable(), $data);
        $res->getQuery();
        $ret = $res->execute();
        return $ret;
    }

    /**
     * update post
     *
     * @param $data
     * @param $where
     * @return \Library\Fluent\PDOStatement
     */
    public function up($data,$where){
        $query = $this->update($this->table)->set($data)->where($where);
        return $query->execute();
    }

    /**
     * delete post
     * @param $where
     * @return \Library\Fluent\PDOStatement
     */
    public function del($where){
        $query = $this->deleteFrom($this->getTable())->where($where);
        return $query->execute();
    }
    
} 