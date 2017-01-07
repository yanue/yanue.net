<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 5/3/14
 * Time: 2:22 PM
 */

namespace App\Map\Controller;


use App\Map\Helper\Meta;
use Library\Core\Controller;

class AppController extends Controller
{

    public function __construct()
    {
        parent::__construct();
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