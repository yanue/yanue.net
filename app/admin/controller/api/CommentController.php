<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 4/27/14
 * Time: 6:40 PM
 */

namespace App\Admin\Controller\Api;


class CommentController
{
    public function dsSyncAction()
    {
        $api = 'http://api.duoshuo.com/threads/counts.json';
        $param = 'short_name=yanuemi&threads=';
    }
} 