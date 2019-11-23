<?php
/**
 * PostController.php
 *
 * @author     yanue <yanue@outlook.com>

 * @time     2013-11-14
 */

namespace App\Admin\Controller\Api;


use App\Admin\Model\PageModel;
use Library\Util\Ajax;
use Library\Util\Validator;

class PageController extends ApiController
{


    /**
     * add post
     */
    public function setAction()
    {
        // get params
        $data['name'] = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
        $data['alias'] = trim($this->post('alias', '', FILTER_SANITIZE_STRING));
        $data['content'] = $this->post('content');
        $data['published'] = intval($this->post('published', 0, FILTER_SANITIZE_NUMBER_INT));
        $data['layout'] = trim(filter_input(INPUT_POST, 'layout', FILTER_SANITIZE_NUMBER_INT));
        $data['modified'] = time();
        // id
        $id = intval($this->post('id', 0, FILTER_SANITIZE_NUMBER_INT));

        // if title not exists
        if (!($data['name'] && $data['content'] && $data['alias'])) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        if (!Validator::validateAliasName($data['alias'])) {
            Ajax::outError(CUSTOM_ERROR_MSG, '别名只能为英文字母数字下线组成');
        }

        $pageModel = new PageModel();

        if ($id) {
            // if has same post
            if ($pageModel->isSame('`alias` = "' . $data['alias'] . '" and id !=' . $id)) {
                Ajax::outError(ERROR_DATA_HAS_EXISTS, '别名已经存在');
            }

            // insert
            $lastid = $pageModel->up($data, array('id' => $id));
            if (!$lastid) {
                Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '修改失败！');
            }
        } else {

            // if has same post
            if ($pageModel->isSame('alias = "' . $data['alias'] . '"')) {
                Ajax::outError(ERROR_DATA_HAS_EXISTS, '别名已经存在');
            }

            // insert
            $lastid = $pageModel->add($data);
            if (!$lastid) {
                Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '添加失败！');
            }
        }

        // out right info
        Ajax::outRight('操作成功！', $lastid);
    }

    /**
     * set post to published
     *
     */
    public function publishAction()
    {
        // get params
        $id = trim($this->request('id'));
        $is_published = intval($this->request('published'));
        $is_published = $is_published ? 1 : 0;
        if (!$id) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $newModel = new PageModel();
        $status = $newModel->up(array('published' => $is_published), array('id' => $id));
        if (!$status) {
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '修改失败！');
        }
        // out right info
        Ajax::outRight($status);
    }

    /**
     * set post to published
     *
     */
    public function delAction()
    {
        // get params
        $id = trim($this->request('id'));
        if (!$id) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $newModel = new PageModel();
        $status = $newModel->del(array('id' => $id));
        if (!$status) {
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '删除失败！');
        }
        // out right info
        Ajax::outRight($status);
    }

    /**
     * set post to published
     *
     */
    public function delAllAction()
    {
        // get params
        $ids = $this->request('data');
        if (!$ids) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $newModel = new PageModel();
        $status = $newModel->del(' id in ( ' . implode(',', $ids) . ' ) ');
        if (!$status) {
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '删除失败！');
        }
        // out right info
        Ajax::outRight($status);
    }

}