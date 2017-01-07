<?php
/**
 * CssController.php
 *
 * @copyright    http://yanue.net/
 * @author        yanue <yanue@outlook.com>
 * @date        2013-08-19
 */

namespace App\Blog\Controller\Tool;


use App\blog\controller\AppController;

class CssController extends AppController
{
    public function formatAction()
    {
        $this->view->sitename = '半叶寒羽';
        $this->view->title = 'css格式化美化压缩工具';
        $this->view->keywords = 'css代码,格式化,压缩工具,css横排,css竖排工具,css解压工具';
        $this->view->description = ' CSS格式化美化压缩工具：横排,竖排,解压,压缩,美化,功能完备';
    }
}