<?php
/**
 * Created by PhpStorm.
 * User: yanue-mi
 * Date: 14-1-22
 * Time: 下午3:56
 */

namespace App\Admin\Controller\Api;


use App\Admin\Lib\Authentication;
use Library\Core\Config;
use Library\Core\Controller;
use Library\Util\Session;

class ApiController extends Controller
{
    protected $_autoCheckLogin = true;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new Authentication($this->view);
        if ($this->_autoCheckLogin) {
            $this->auth->checkApiAuth();
        }
        $this->uid = Session::instance()->get('_CUID');
        Config::load('errcode');
    }
} 