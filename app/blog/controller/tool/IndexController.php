<?php
/**
 * IndexController.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.0 - 13-7-4
 */
namespace App\Blog\Controller\Tool;

use App\Home\Model\ArticleModel;
use Library\Core\Controller;


class IndexController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function indexAction(){
        $this->view->setLayout();
        $this->view->title = '';
        $this->view->keywords = 'css代码,格式化,压缩工具,css横排,css竖排工具,css解压工具,js/html格式化,美化,压缩,加密,解密,工具';
        $this->view->description = '半叶寒羽-前端工具箱:css/js/html格式化,美化,压缩,加密,解密工具箱';
    }

}