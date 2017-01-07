<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 5/3/14
 * Time: 3:14 PM
 */

namespace App\Map\controller\geocode;


use App\Map\controller\AppController;

class GoogleController extends AppController
{
    public function indexAction()
    {
        $this->view->title = 'as';
    }
} 