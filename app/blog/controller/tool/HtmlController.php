<?php
/**
 * HtmlController.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @date        2013-08-20
 */

namespace App\Blog\Controller\Tool;


use Library\Core\Controller;

class HtmlController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function formatAction(){
        $this->view->title = 'Html格式化美化工具';
        $this->view->keywords = 'Html代码,Html格式化,Html压缩工具,Html美化工具,半叶寒羽,yanue.net';
        $this->view->description = 'Html代码,Html格式化,Html压缩工具,Html美化工具,半叶寒羽-前端工具箱:css/js/html格式化,美化,压缩,加密,解密工具';
        $this->view->setLayout();
    }

}