<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 14-2-10
 * Time: 下午10:32
 */

namespace App\blog\controller;


use App\Blog\Helper\Meta;
use Library\Core\Config;
use Library\Core\Controller;
use Library\Util\Session;

class AppController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        Config::load('errcode');
        $this->view->admin_uid = Session::instance()->get('_CUID');
        Meta::set($this->view);

        $this->view->setLayout();
    }

    protected function noData($code)
    {
        $this->view->setLayout('', 'base/noData', false);
        $this->view->errcode = $code;
        $this->view->display();
        exit;
    }

} 