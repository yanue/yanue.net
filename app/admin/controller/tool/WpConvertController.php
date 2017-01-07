<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 3/3/14
 * Time: 5:19 PM
 */

namespace App\Admin\Controller\Tool;


use App\Admin\Controller\AppController;
use App\Admin\Model\WpModel;

class WpConvertController extends AppController
{
    public function convAction()
    {
        $m = new WpModel();
        $m->conv();
        $this->view->disableLayout();
    }
}