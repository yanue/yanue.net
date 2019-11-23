<?php
/**
 * PostController.php
 *
 * @author     yanue <yanue@outlook.com>

 * @time     2013-11-14
 */

namespace App\Admin\Controller\Api;

use App\Admin\Model\AdminModel;
use Library\Util\Ajax;
use Library\Util\Session;

class AdminController extends ApiController
{
    protected $_autoCheckLogin = false;

    /**
     * admin user login
     *
     */
    public function loginAction()
    {
        // get params
        $user = trim($this->request('user'));
        $passwd = $this->request('passwd');

        if (!($passwd && $user)) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }
        // check user
        $adminModel = new AdminModel();
        $hasUser = $adminModel->checkUser($user);
        if ($hasUser == 0) {
            Ajax::outError(ERROR_USER_IS_NOT_EXISTS);
        }

        // passwd crypt by md5 and hash
        $password = $this->auth->hash('sha1', $passwd);
        $userInfo = $adminModel->login($user, $password);
        if (!$userInfo) {
            Ajax::outError(ERROR_PASSWD_IS_NOT_CORRECT);
        }
        unset($userInfo['password']);
        // login
        Session::instance()->set('_CUID', $userInfo['uid']);
        Session::instance()->set('_CUSR', $userInfo['user_name']);

        // out right info
        Ajax::outRight('成功登陆');
    }

    /**
     * update admin user password
     */
    public function upassAction()
    {
        $this->auth->checkApiAuth();

        $oldPasswd = $this->request('oldPasswd');
        $newPasswd = $this->request('newPasswd');

        // miss param
        if (!($oldPasswd && $newPasswd)) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $adminModel = new AdminModel();

        // passwd has used md5 and hash
        $oldPassword = $this->auth->hash('sha1', $oldPasswd);
        $newPassword = $this->auth->hash('sha1', $newPasswd);

        $isCorrect = $adminModel->checkPasswd($this->uid, $oldPassword);
        // 0 is no found
        if ($isCorrect == 0) {
            Ajax::outError(ERROR_PASSWD_IS_NOT_CORRECT);
        }

        $data = array('password' => $newPassword, 'modified' => time());
        $adminModel->updateUserInfo($data, array('uid' => $this->uid));

        Ajax::outRight('修改密码成功！');
    }

    /**
     * reset another admin password
     */
    public function repassAction()
    {
        $this->auth->checkApiAuth();

        $uid = $this->request('uid');
        $newPasswd = $this->request('newPasswd');

        if (!($uid && $newPasswd)) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $adminModel = new AdminModel();

        // passwd has used md5 and hash
        $newPassword = $this->auth->hash('sha1', $newPasswd);

        $data = array('password' => $newPassword, 'modified' => time());
        $adminModel->updateUserInfo($data, 'uid = ' . $uid);

        // add log
        Ajax::outRight('密码重置成功！');
    }

    /**
     * update admin user info
     */
    public function uinfoAction()
    {
        $this->auth->checkApiAuth();

        $user_name = trim($this->request('user'));
        $true_name = $this->request('true_name');
        $email = $this->request('email');

        // load model
        $adminModel = new AdminModel();

        // get user info
        $userInfo = $adminModel->getUserInfo($this->uid);

        // check user
        if ($user_name != $userInfo['user_name']) {
            $isUsed = $adminModel->checkUser($user_name);
            if ($isUsed == 1) {
                Ajax::outError(ERROR_USER_HAS_BEING_USED);
            }
        }

        // check email
        if ($email && $userInfo['email'] != $email) {
            $isUsed = $adminModel->checkEmail($email);
            if ($isUsed == 1) {
                Ajax::outError(ERROR_EMAIL_HAS_BEING_USED);
            }
        }

        // update info
        $data = array('user_name' => $user_name, 'email' => $email, 'true_name' => $true_name, 'modified' => time());
        $adminModel->updateUserInfo($data, 'uid = ' . $_SESSION['_CUID']);

        Ajax::outRight('修改成功！');
    }

    /**
     * add Admin
     */
    public function addAdminAction()
    {
        $this->auth->checkApiAuth();

        // get params
        $user_name = trim($this->request('user'));
        $true_name = trim($this->request('true_name'));
        $email = $this->request('email');
        $passwd = $this->request('passwd');
        $adminModel = new AdminModel();

        if (!$passwd) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        // check user
        if ($user_name != $_SESSION['_CUSR']) {
            $isUsed = $adminModel->checkUser($user_name);
            if ($isUsed == 1) {
                Ajax::outError(ERROR_USER_HAS_BEING_USED);
            }
        } else {
            Ajax::outError(ERROR_USER_HAS_BEING_USED);
        }

        // get user info
        // check email
        if ($email) {
            $isUsed = $adminModel->checkEmail($email);
            if ($isUsed == 1) {
                Ajax::outError(ERROR_EMAIL_HAS_BEING_USED);
            }
        }

        // add log
        $adminModel = new AdminModel();

        $password = $this->auth->hash('sha1', $passwd);

        $data = array('user_name' => $user_name, 'true_name' => $true_name, 'email' => $email, 'password' => $password, 'level' => 1, 'create_time' => time(), 'modified' => time());

        $uid = $adminModel->addUser($data);
        if (!$uid) {
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED);
        }
        Ajax::outRight($uid);
    }

    /**
     * update admin info
     */
    public function updateAction()
    {
        $this->auth->checkApiAuth();
        // request params
        $user_name = trim($this->request('user'));
        $true_name = $this->request('true_name');
        $email = $this->request('email');
        $uid = intval($this->request('uid', 0));

        // load model
        $adminModel = new AdminModel();

        if (!$uid) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        // get user info
        $userInfo = $adminModel->getUserInfo($uid);

        // check user
        if ($user_name != $userInfo['user_name']) {
            $isUsed = $adminModel->checkUser($user_name);
            if ($isUsed == 1) {
                Ajax::outError(ERROR_USER_HAS_BEING_USED);
                exit;
            }
        }

        // check email
        if ($email && $userInfo['email'] != $email) {
            $isUsed = $adminModel->checkEmail($email);
            if ($isUsed == 1) {
                Ajax::outError(ERROR_EMAIL_HAS_BEING_USED);
                exit;
            }
        }

        // update info
        $data = array('user_name' => $user_name, 'email' => $email, 'true_name' => $true_name, 'modified' => time());
        $adminModel->updateUserInfo($data, 'uid = ' . $uid);

        // add log
        Ajax::outRight('管理员信息更新成功！');
    }


}