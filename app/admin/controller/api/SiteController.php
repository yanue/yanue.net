<?php
/**
 * BrandController.php
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @time     2013-11-14
 */

namespace App\Admin\Controller\Api;


use App\Admin\Lib\Authentication;
use App\Admin\Model\AdminModel;
use App\Admin\Model\BrandModel;
use App\Admin\Model\SiteModel;
use Library\Core\Controller;
use Library\Util\Ajax;

class SiteController extends Controller{
    public function __construct(){
        parent::__construct();
        $this->auth = new Authentication($this->view);
        $this->auth->checkApiAuth();
        $this->uid = $this->session->get('_CUID');
    }

    /**
     * set brand to published
     *
     */
    public function setFocusAction(){
        // get params
        $data = $this->request('data') ;
        $type = $this->request('type') ;
        if(!$data && in_array($type,array('postFocus'))){
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $m = new SiteModel();
        $status = $m->up(array($type=>json_encode($data,JSON_UNESCAPED_UNICODE)));
        if(!$status){
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED,'修改失败！');
        }
        // out right info
        Ajax::outRight('',$status);
    }

}