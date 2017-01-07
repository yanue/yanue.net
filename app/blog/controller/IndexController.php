<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 14-2-8
 * Time: 下午8:54
 */

namespace App\blog\controller;


use Aliyun\OSS\OSSClient;
use App\Blog\model\PostModel;
use Library\Core\Config;

class IndexController extends AppController
{
    public function indexAction()
    {
        $m = new PostModel();
        $recommend = $m->getRecommend(6);
        $list = $m->getList(0, 10, null, array('published' => 1), 'created desc');

        $this->view->recommend = $recommend;
        $this->view->latest = $list;

    }

    public function testAction()
    {

        Config::load('aliyun');
        require_once WEB_ROOT . '/aliyun/aliyun.php';
        $client = OSSClient::factory(array(
            'AccessKeyId' => OSS_ACCESS_ID,
            'AccessKeySecret' => OSS_ACCESS_KEY,
        ));

//        print_r($client);
        $result = $client->listObjects(array(
            'Bucket' => 'yanue',
            'Prefix' => 'jpg'
        ));

//        print_r($result);
        foreach ($result->getObjectSummarys() as $summary) {
            echo 'Object key: ' . $summary->getKey() . "\n";
        }
//        print_r($result->getObjectSummarys());

        $objectListing = $client->listObjects(array(
            'Bucket' => 'yanue',
        ));

        $res = $client->putObject(array('Bucket' => 'yanue', 'Key' => 'jpg/1E/111.jpg', 'Content' => ''));

        print_r($res);


        $this->view->disableLayout();
    }


} 
