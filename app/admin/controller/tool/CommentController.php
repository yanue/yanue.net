<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 3/7/14
 * Time: 4:36 PM
 */

namespace App\Admin\Controller\Tool;


use App\Admin\Controller\AppController;

class CommentController extends AppController
{
    public function importAction()
    {
        $data = array();
        $data['short_name'] = 'yanue';
        $data['sercret'] = '';
        $data['users'] = array();
        $data['users'][] = array(
            'user_key' => 1,
            'name' => 'test',
        );
        $data['users'][] = array(
            'user_key' => 2,
            'name' => 'hehe',
        );
        $param = http_build_query($data, '', '&');
        echo $param;
        $this->view->disableLayout();
    }

} 