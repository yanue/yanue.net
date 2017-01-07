<?php
/**
 * HtmlController.php
 *
 * @copyright    http://yanue.net/
 * @author        yanue <yanue@outlook.com>
 * @date        2013-08-20
 */

namespace App\Blog\Controller\Tool;


use Library\Core\Controller;

class JsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function formatAction()
    {
        $this->view->title = 'JS格式化美化加密工具';
        $this->view->keywords = 'css代码,格式化,压缩工具,css横排,css竖排工具,css解压工具';
        $this->view->description = ' CSS格式化美化压缩工具：横排,竖排解压,压缩,美化,功能完备';
        $this->view->setLayout();
    }

    public function jsonAction()
    {
        $this->view->title = 'json格式化及高亮美化';
        $this->view->keywords = 'json,格式化,高亮';
        $this->view->description = 'json格式化及高亮美化,json,格式化,高亮';
        $this->view->setLayout();
    }
}