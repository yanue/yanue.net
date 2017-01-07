<?php
/**
 * Created by PhpStorm.
 * User: yanue-mi
 * Date: 14-1-22
 * Time: 下午3:52
 */

namespace App\Admin\Controller;


use App\Admin\Lib\Authentication;
use App\Admin\Lib\Menu;
use Library\Core\Controller;
use Library\Util\Session;

class AppController extends Controller
{
    public $uid = null;

    public function __construct()
    {
        parent::__construct();
        Menu::init($this->view);

        // set layout
        $this->view->setLayout();
        // get uid
        $this->uid = Session::instance()->get('_CUID');
        $this->auth = new Authentication($this->view);
        $this->auth->checkUserStatus();
        $this->view->sign = $this->auth->createSign();
    }
} 