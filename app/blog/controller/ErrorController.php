<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 11/14/13
 * Time: 3:29 PM
 */

namespace App\Blog\Controller;


use App\Admin\Lib\Menu;
use Library\Core\Controller;

class ErrorController extends Controller{
    public function __construct(){
        parent::__construct();
        Menu::init($this->view);
    }

    /**
     * 404 page
     */
    public function indexAction(){
        $this->view->setLayout('layout','base/404',false);
    }
    
} 