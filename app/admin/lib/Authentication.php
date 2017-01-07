<?php
namespace App\Admin\Lib;

use Library\Core\Config;
use Library\Core\View;
use Library\Util\Ajax;
use Library\Util\Session;

/**
 * oauth for admin module
 *  ---- use session
 *
 * Class Authentication
 * @package App\Admin\Lib
 */
class Authentication
{

    /**
     * session处理
     * @var Session
     */
    public $session = null;
    public $view = null;

    public function __construct(View $view = null)
    {
        $this->view = $view;
        $this->session = new Session();
        self::$errmsg = Config::load('errcode');
    }

    /**
     * error msg for defined code
     *
     * @source 参见当前module下configs/errorCode.php
     * @var null
     */
    public static $errmsg = null;

    /**
     * check api auth
     * -- check if user is login or not
     * -- check if sign is right or not
     * -- check user permission
     *
     * @return bool|string(json)
     */
    public function checkApiAuth()
    {
        $this->view->disableLayout();
        $this->setHead();
        $uid = $this->session->get('_CUID');
        // if not login
        if (!($uid > 0)) {
            Ajax::outError(ERROR_USER_HAS_NOT_LOGIN);
        }

        // check sign
        $sess_sign = $this->session->get('sign');
        $request_sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : null;
        $res = $request_sign == $sess_sign ? true : false;
        if (!$res) {
//            self::outError(ERROR_ILLEGAL_API_SIGNATURE);
        }

        return true;
    }

    /**
     *  check Login status and permission
     * -- check if user is login or not
     * -- check user permission
     *
     * @return bool|string
     */
    public function checkUserStatus()
    {
        // check if login or not
        $uid = $this->session->get('_CUID');
        if (!(is_numeric($uid) && $uid > 0)) {
            $loginUrl = $this->view->baseUrl('base/my/login');
            header("location:" . $loginUrl . '?ref=' . urlencode($this->view->uri->getFullUrl()));
        }

        return true;
    }

    /**
     * set api signature
     *
     * @return string
     */
    public function createSign()
    {
        $sign = $this->hash('sha1', md5('ainana' . time()));
        $this->session->set('sign', $sign);
        return $sign;
    }

    /**
     * @return bool
     */
    public function checkSign()
    {
        $request_sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : null;
        $sess_sign = $this->session->get('sign');
        $res = $request_sign == $sess_sign ? true : false;
        if (!$res) {
            Ajax::outError(ERROR_ILLEGAL_API_SIGNATURE);
        }
        return $res;
    }

    /**
     * 创建hash算法
     *
     * @param string $algo The algorithm (md5, sha1, whirlpool, etc)
     * @param string $data The data to encode
     * @param string $salt The salt (This should be the same throughout the system probably)
     * @return string The hashed/salted data
     */
    public function hash($algo, $data, $salt = '!@:\"#$%^&*<>?{}$^$@*^&*I@!')
    {

        $context = hash_init($algo, HASH_HMAC, $salt);
        hash_update($context, $data);

        return hash_final($context);

    }

    /**
     * 设置ajax跨域head
     */
    public function setHead()
    {
        header("Access-Control-Allow-Origin: *"); # 跨域处理
        header("Access-Control-Allow-Headers: origin, content-type, accept");
        header("Access-Control-Allow-Credentials: true");

        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

}