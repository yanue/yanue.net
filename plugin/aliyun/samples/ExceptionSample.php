<?php
require_once dirname(__DIR__) . '/aliyun.php';

use Aliyun\OSS\OSSClient;

use Aliyun\OSS\Exceptions\OSSException;

use Aliyun\Common\Exceptions\ClientException;

// Sample of handle exception
function handleExceptionSample() {
    try {
        $client = OSSClient::factory(array(
            'AccessKeyId' => 'your-access-key-id',
            'AccessKeySecret' => 'your-access-key-secret',
        ));
        $client->listBuckets();
    } catch (OSSException $ex) {
        echo "OSSException: " . $ex->getErrorCode() . " Message: " . $ex->getMessage();
    } catch (ClientException $ex) {
        echo "ClientExcetpion, Message: " . $ex->getMessage();
    }
}

handleExceptionSample();
