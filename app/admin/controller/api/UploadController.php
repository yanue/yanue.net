<?php
/**
 * MallController.php
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @time     2013-11-14
 */

namespace App\Admin\Controller\Api;

use App\Admin\Lib\Upload;
use Library\Core\Config;
use Library\Core\Controller;
use Library\Util\Ajax;

class UploadController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->ajax = new Ajax();
    }

    /**
     * upload img
     */
    public function imgAction()
    {
        $upload = new Upload();
        $upload->upExt = 'jpg|jpeg|gif|png|bmp';
        $upload->maxAttachSize = 2097152; // 2M
        $buff = $upload->upOne();
        $file = $upload->saveFile($buff);
        if ($this->uri->getParam('from') == 'xheditor') {
            echo $msg = "{'msg':'" . 'http://' . Config::getItem('domain.img') . $file['url'] . "','err':''}"; //id参数固定不变，仅供演示，实际项目中可以是数据库ID
            exit;
        }
        $this->ajax->outRight('', array('url' => 'http://' . Config::getItem('domain.img') . $file['url']));
    }

    /**
     * upload video
     */
    public function videoAction()
    {
        $upload = new Upload();
        $upload->upExt = 'mp4|3gp|ogg|webm';
        $upload->maxAttachSize = 209715200; // 200M
        $buff = $upload->upOne();
        $file = $upload->saveFile($buff);
        if ($this->uri->getParam('from') == 'xheditor') {
            echo $msg = "{'msg':'" . 'http://' . Config::getItem('domain.img') . $file['url'] . "','err':''}"; //id参数固定不变，仅供演示，实际项目中可以是数据库ID
            exit;
        }
        $this->ajax->outRight('', array('url' => 'http://' . Config::getItem('domain.img') . $file['url']));
    }

    /**
     * upload video
     */
    public function fileAction()
    {
        $upload = new Upload();
        $upload->upExt = 'zip|rar|doc|xls|ppt|docx|pptx|xlsx';
        $upload->maxAttachSize = 20971520; // 20M
        $buff = $upload->upOne();
        $file = $upload->saveFile($buff);
        if ($this->uri->getParam('from') == 'xheditor') {
            echo $msg = "{'msg':'" . 'http://' . Config::getItem('domain.img') . $file['url'] . "','err':''}"; //id参数固定不变，仅供演示，实际项目中可以是数据库ID
            exit;
        }
        $this->ajax->outRight('', array('url' => 'http://' . Config::getItem('domain.img') . $file['url']));
    }

    /**
     * save remote img
     */
    public function saveimgAction()
    {
        $arrUrls = explode('|', $_REQUEST['urls']);
        $urlCount = count($arrUrls);
        $upload = new Upload();
        $upload->upExt = 'jpg|jpeg|gif|png|bmp';
        for ($i = 0; $i < $urlCount; $i++) {
            $fileinfo = $upload->saveRemoteImg($arrUrls[$i]);
            if (!empty($fileinfo['buff']) && !empty($fileinfo['ext'])) {
                $file = $upload->saveFile($fileinfo);
                $arrUrls[$i] = $file['url'];
            }
        }

        echo 'http://' . Config::getItem('domain.img') . implode('http://' . Config::getItem('domain.img') . '|', $arrUrls);

    }


}