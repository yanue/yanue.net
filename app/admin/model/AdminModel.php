<?php
namespace App\Admin\Model;

use Library\Core\PDOModel;

define('DEFAULT_PAGE_LIMIT',20);
class AdminModel extends PDOModel {

    protected $_admin_users = 'admin_users';

    public function __construct(){
        parent::__construct();
    }

    // check username
    public function checkUser($user){
        $query = $this->from($this->_admin_users)->
            select(null)->select('count(uid) as count')
            ->where('user_name',$user);

        return intval($query->fetch('count'));
    }

    // check passwd
    public function checkPasswd($uid,$passwd) {
        $query = $this->from($this->_admin_users)
            ->select(null)->select('count(uid) as count')
            ->where('uid = :uid and password = :passwd', array(':uid' => $uid,'passwd'=>$passwd));

        return intval($query->fetch('count'));
    }

    // check username
    public function checkEmail($email){
        $query = $this->from($this->_admin_users)->
            select(null)->select('count(uid) as count')
            ->where('email',$email);

        return intval($query->fetch('count'));
    }

    // login
    public function login($user,$passwd) {
        $query = $this->from($this->_admin_users)->where(array('user_name'=>$user,'password'=>$passwd));
        return $query->fetch();
    }

    // update user info
    public function updateUserInfo($data,$where){
        $res = $this->update($this->_admin_users)->set($data)->where($where);
        return $res->execute();
    }

    // add log
    public function addUser($data){
        $query = $this->insertInto($this->_admin_users,$data);
        return $query->execute();
    }

    // get user info
    public function getUserInfo($uid){
        $query = $this->from($this->_admin_users)->where('uid',$uid);
        return $query->fetch();
    }

    // admins list
    public function getUserList() {
        $query = $this->from($this->_admin_users)->orderBy('uid asc');
        $res = $query->fetchAll();
        return $res;
    }





}
