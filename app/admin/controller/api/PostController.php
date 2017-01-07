<?php
/**
 * PostController.php
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @time     2013-11-14
 */

namespace App\Admin\Controller\Api;


use App\Admin\Model\PostModel;
use Library\Util\Ajax;
use Plugin\XunSearch\Lib\XS;
use Plugin\XunSearch\Lib\XSDocument;

class PostController extends ApiController
{

    /**
     * set post to published
     *
     */
    public function publishAction()
    {
        // get params
        $id = trim($this->request('id'));
        $is_published = $this->request('published');
        $is_published = $is_published ? 1 : 0;
        if (!$id) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $newModel = new PostModel();
        $status = $newModel->updatePost(array('published' => $is_published), array('id' => $id));
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

        $newModel = new PostModel();
        $status = $newModel->delPost(array('id' => $id));
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

        $newModel = new PostModel();
        $status = $newModel->delPost(' id in ( ' . implode(',', $ids) . ' ) ');
        if (!$status) {
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '删除失败！');
        }
        // out right info
        Ajax::outRight($status);
    }

    /**
     * add post
     */
    public function setAction()
    {
        // get params
        $add_time = trim(filter_input(INPUT_POST, 'created', FILTER_SANITIZE_STRING));
        // set data
        $data['created'] = strtotime($add_time) ? strtotime($add_time) : time();
        $data['cid'] = intval(filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT));
        $data['title'] = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
        $data['content'] = $this->post('content');
        $data['sub_title'] = trim(filter_input(INPUT_POST, 'sub_title', FILTER_SANITIZE_STRING));
        $data['keywords'] = trim(filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_STRING));;
        $data['source'] = trim(filter_input(INPUT_POST, 'source', FILTER_SANITIZE_STRING));
        $data['author'] = trim(filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING));
        $data['recommend'] = intval(filter_input(INPUT_POST, 'recommend', FILTER_SANITIZE_NUMBER_INT));
        $data['published'] = intval(filter_input(INPUT_POST, 'published', FILTER_SANITIZE_NUMBER_INT));
        $data['cover_img'] = trim(filter_input(INPUT_POST, 'cover_img', FILTER_SANITIZE_URL));
        $data['modified'] = time();

        $id = intval(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));

        // if title not exists
        if (!($data['title'] && $data['content'])) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }
        // search doc field
        $search['title'] = $data['title'];
        $search['content'] = strip_tags($data['content']);
        $search['cid'] = $data['cid'];
        $search['created'] = time();

        $postModel = new PostModel();
        // 标签处理
        $tags = $this->post('tags');
        $allTags = $postModel->getAllTags();
        $allTags = array_column($allTags, 'name', 'id');
        $needUpdate = [];
        if ($tags) {
            $needAdd = array_diff($tags, $allTags);
            $needUpdate = array_diff($tags, $needAdd); // 已经存在的数据

            // new add tag
            $json_tags = array();

            foreach ($needAdd as $tag) {
                $tid = $postModel->addTag(array('name' => $tag));
                if ($tid) {
                    $arr['id'] = $tid;
                    $arr['name'] = $tag;
                    $json_tags[] = $arr;
                }
            }

            // new update tag
            foreach ($needUpdate as $tag) {
                $arr1['id'] = array_search($tag, $allTags);
                $arr1['name'] = $tag;
                $json_tags[] = $arr1;
            }
            $data['tags'] = json_encode($json_tags, JSON_UNESCAPED_UNICODE);
        } else {
            $data['tags'] = json_encode("");
        }
//        $data['content'] = htmlentities($data['content']);
        if ($id) {
            // if remove tag then minus the count
            $post = $postModel->getPost($id);
            $tags_arr = json_decode($post['tags'], true);
            if ($tags_arr) {
                $old_arr = array_column($tags_arr, 'name', 'id');
                if ($needUpdate) {
                    $old_remove = array_diff($old_arr, $needUpdate);
                    $old_need_add = array_diff($needUpdate, $old_arr);
                    if ($old_remove) {
                        // 原来被移除的标签count-1
                        $postModel->upTagCount('name in ("' . implode('","', $old_remove) . '")', " - 1 ");
                    }
                    if ($old_need_add) {
                        // 重新加1
                        $postModel->upTagCount('name in ("' . implode('","', $old_need_add) . '")', " + 1 ");
                    }
                }
            } else {
                $old_need_add = array_diff($needUpdate, []);

                if ($old_need_add) {
                    // 重新加1
                    $postModel->upTagCount('name in ("' . implode('","', $old_need_add) . '")', " + 1 ");
                }
            }

            // if has same post
            if ($postModel->isSame('title = "' . $data['title'] . '" and cid =' . $data['cid'] . ' and id !=' . $id)) {
                Ajax::outError(ERROR_TITLE_IS_EXISTS);
            }
            // update
            $res = $postModel->updatePost($data, array('id' => $id));
            if (!$res) {
                Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '修改失败！');
            }

            // add search doc
            $search['id'] = $id;
            $search['tags'] = $data['tags'];
            $this->setXSDoc($search, 'update');
        } else {
            // update the tag count 全部更新
            $postModel->upTagCount('name in ("' . implode('","', $needUpdate) . '")', " + 1 ");

            // if has same post
            if ($postModel->isSame('title = "' . $data['title'] . '" and cid =' . $data['cid'])) {
                Ajax::outError(ERROR_TITLE_IS_EXISTS);
            }

            // insert
            $res = $postModel->addPost($data);
            if (!$res) {
                Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '添加失败！');
            }

            // add search doc
            $search['id'] = $res;
            $search['tags'] = $data['tags'];

            $this->setXSDoc($search, 'add');
        }
        // out right info
        Ajax::outRight('添加成功！', $res);
    }

    private function setXSDoc($data, $method = "add")
    {
        $xs = new XS(XUNSEARCH_APPNAME);
        $index = $xs->index;
        $doc = new XSDocument();
        // 创建文档对象
        $doc->setFields($data);

        if ($method == 'add') {
            // 更新到索引数据库中
            return $index->add($doc);
        } else {
            // 更新到索引数据库中
            return $index->update($doc);
        }
    }

    /**
     * set cats
     */
    public function setCatsAction()
    {
        // request params
        $menuCats = $this->request('postCats');
        if (!$menuCats) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $postModel = new PostModel();
        // each do
        foreach ($menuCats as $cat) {
            $id = isset($cat['id']) ? $cat['id'] : null;
            unset($cat['id']); // for update it
            if ($id) {
                // update data
                $postModel->updateCats($cat, array('id' => $id));
            } else {
                //if $menu has no id ,then add this
                if ($cat['name']) {
                    $postModel->addCat($cat);
                }
            }
        }

        // log
        Ajax::outRight('栏目设置成功');
    }

    /**
     * del cat
     */
    public function delCatAction()
    {
        // get params
        $id = intval(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if (!$id) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $newModel = new PostModel();
        $status = $newModel->delCat($id);
        if (!$status) {
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '删除栏目失败！');
        }
        // out right info
        Ajax::outRight($status);
    }

    /**
     * mv cat to new cat
     */
    public function mvCatAction()
    {
        // get params
        $cid = intval(filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT));
        $toCid = intval(filter_input(INPUT_POST, 'toCid', FILTER_SANITIZE_NUMBER_INT));
        if (!($cid)) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        $newModel = new PostModel();
        $status = $newModel->mvCat($cid, $toCid);
        if (!$status) {
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '移动栏目失败！');
        }
        // out right info
        Ajax::outRight($status);
    }

    /**
     * mv post to new cat
     */
    public function mvPostAction()
    {
        // get params
        $cid = intval(filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT));
        $data = $this->post('data');
        if (!($data && $data)) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }
        $where = ' id in ( ' . implode(',', $data) . ' ) ';
        $newModel = new PostModel();
        $status = $newModel->updatePost(array('cid' => $cid), $where);
        if (!$status) {
            Ajax::outError(ERROR_NOTHING_HAS_CHANGED, '移动文章到新栏目失败！');
        }
        // out right info
        Ajax::outRight($status);
    }


}