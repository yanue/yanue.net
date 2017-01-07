<?php
/**
 * PostController.php
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @time     2013-11-14
 */

namespace App\Admin\Controller\Api;


use App\Admin\Lib\Authentication;
use App\Admin\Model\AdminModel;
use App\Admin\Model\PostModel;
use Library\Core\Controller;

class CatController extends Controller
{


    public function __construct()
    {
        parent::__construct();
    }

    public function scrollAction()
    {
        $cid = intval($this->uri->getParam('cid'));
        $page = intval($this->uri->getParam('p'));
        $where = ' published=1 and cover_img <>"" ';
        $where .= is_numeric($cid) && $cid >= 0 ? ' and cid = ' . $cid : '';
        $curpage = $page <= 0 ? 0 : $page - 1;
        $limit = 10;
        $postModel = new PostModel();
        $res = $postModel->getPostList($where, $curpage, $limit);
        $width = 220;
        if (!empty($res['data'])) {
            foreach ($res['data'] as &$val) {
                $val['time'] = date('Y-m-d H:i:s', intval($val['created']));
                $val['url']= $this->controllerUrl('detail-' . $val['id'] . '.html');
                if(empty($val['cover_img'])){
                    continue ;
                }
                $imgPro = getimagesize(WEB_ROOT . $val['cover_img']);
                $val['height'] = round($width / $imgPro['0'] * $imgPro['1']);
                $val['width'] = $width;

            }
        }

        if ($fun = $this->uri->getParam('callback')) {
            echo $fun . "(" . json_encode($res['data']) . ")";
        } else {
            echo json_encode($res['data']);
        }

    }
}