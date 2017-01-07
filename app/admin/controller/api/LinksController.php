<?php

namespace App\Admin\Controller\Api;

use App\Admin\Model\SiteLinksModel;
use Library\Util\Jsonp;
use Library\Util\Validator;

class LinksController extends ApiController
{

    public function getAction()
    {
        $id = $this->post('id');
        if (!$id) {
            Jsonp::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $m = new SiteLinksModel();
        $m->id = $id;

        if (!$m->exists()) {
            Jsonp::outError(ERROR_DATA_NOT_EXISTS);
        }

        Jsonp::outRight('获取数据成功', $m->toArray());
    }

    public function setAction()
    {
        $data = $this->post('data');
        $id = $this->post('id');

        if ($data['url'] && !Validator::validateUrl($data['url'])) {
            Jsonp::outError(CUSTOM_ERROR_MSG, '网站地址格式不正确！');
        }
        if ($data['logo'] && !Validator::validateUrl($data['logo'])) {
            Jsonp::outError(CUSTOM_ERROR_MSG, '图片地址格式不正确！');
        }
        if (!($data['name'] && $data['url'])) {
            Jsonp::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $data['sort'] = $data['sort'] & 0xff;

        $m = new SiteLinksModel();
        $log['data'] = $data;
        if ($id) {
            $log['type'] = 'update';
            $log['id'] = $id;
            $log['status'] = $id;

            $res = $m->up($data, $id);
        } else {
            $log['type'] = 'add';

            $data['created'] = time();
            $log['id'] = $res = $m->add($data);
        }

        if (!$res) {
            Jsonp::outError(ERROR_NOTHING_HAS_CHANGED);
        }

        Jsonp::outRight('操作成功', $res);
    }

    /**
     * del action
     *
     */
    public function delAction()
    {
        // get params
        $id = intval($this->post('id'));
        $cid = intval($this->post('cid'));

        $where = array('id' => $id);
        if ($cid) {
            $where['cid'] = $cid;
        }
        if (!$id && !is_array($id)) {
            Jsonp::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $m = new SiteLinksModel();

        $status = $m->del($id);
        if (!$status) {
            Jsonp::outError(ERROR_NOTHING_HAS_CHANGED, '删除失败！');
        }

        $log['data'] = $id;
        $log['status'] = $status;
        // out right info

        Jsonp::outRight('', $status);
    }


}