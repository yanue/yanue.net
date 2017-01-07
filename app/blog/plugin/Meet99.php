<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 3/31/14
 * Time: 6:10 PM
 */

namespace App\Blog\plugin;


use Library\Util\Curl;

class Meet99
{
    public $url = "http://www.meet99.com/lvyou";

    public static $_html = null;

    public function start()
    {
        $max_redirect = 3; // Skipable: default => 3
        $client = new Curl(array(
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ja; rv:1.9.2.3) Gecko/20140401 Firefox/24.01'
        ), $max_redirect);

        $client->setResponseSequenceFlag(true); // Skipable: default => false
        $client->setMetaRedirectFlag(false); // Skipable: default => true
        $client->setCompression('gzip,deflate'); // Skipable: default => ''

        $url = 'http://example.com/index.php';
        $client->get($this->url);
        print_r($client->currentResponse());
        die;
    }


}