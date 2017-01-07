<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 14-3-3
 * Time: 下午10:21
 */

namespace App\Admin\Model;


use Library\Core\PDOModel;

class WpModel extends PDOModel
{
    protected $_wp = 'wp_posts';
    protected $_post = 'post';
    protected $_wp_meta = 'wp_postmeta';

    public function conv()
    {
        $sql = $this->from($this->_wp . ' as wp')->select('wp.*,meta.meta_value as views')->leftJoin($this->_wp_meta . ' as meta on wp.id = meta.post_id')->where('meta_key="views"')->orderBy('id asc');
        $res = $sql->fetchAll();
        foreach ($res as $v) {
            $item = [
                'wp_id' => $v['ID'],
                'created' => strtotime($v['post_date_gmt']),
                'modified' => strtotime($v['post_modified_gmt']),
                'title' => $v['post_title'],
                'content' => $v['post_content'],
                'comments' => $v['comment_count'],
                'views' => $v['views']
            ];
            $this->addPost($item);
        }

    }

    public function addPost($data)
    {
        $res = $this->insertInto($this->_post, $data);
        return $res->execute();
    }
} 