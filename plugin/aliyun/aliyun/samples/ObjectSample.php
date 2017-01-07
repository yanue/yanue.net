<?php
require_once dirname(__DIR__) . '/aliyun.php';

use Aliyun\OSS\OSSClient;

// Sample of create client
function createClient($accessKeyId, $accessKeySecret) {
    return OSSClient::factory(array(
        'AccessKeyId' => $accessKeyId,
        'AccessKeySecret' => $accessKeySecret,
    ));
}

function listObjects(OSSClient $client, $bucket) {
    $result = $client->listObjects(array(
        'Bucket' => $bucket,
    ));
    foreach ($result->getObjectSummarys() as $summary) {
        echo 'Object key: ' . $summary->getKey() . "\n";
    }
}

// Sample of put object from string
function putStringObject(OSSClient $client, $bucket, $key, $content) {
    $result = $client->putObject(array(
        'Bucket' => $bucket,
        'Key' => $key,
        'Content' => $content,
    ));
    echo 'Put object etag: ' . $result->getETag();
}

// Sample of put object from resource
function putResourceObject(OSSClient $client, $bucket, $key, $content, $size) {
    $result = $client->putObject(array(
        'Bucket' => $bucket,
        'Key' => $key,
        'Content' => $content,
        'ContentLength' => $size,
    ));
    echo 'Put object etag: ' . $result->getETag();
}

// Sample of get object
function getObject(OSSClient $client, $bucket, $key) {
    $object = $client->getObject(array(
        'Bucket' => $bucket,
        'Key' => $key,
    ));

    echo "Object: " . $object->getKey() . "\n";
    echo (string) $object;
}

// Sample of delete object
function deleteObject(OSSClient $client, $bucket, $key) {
    $client->deleteObject(array(
        'Bucket' => $bucket,
        'Key' => $key,
    ));
}

$keyId = 'your-access-key-id';

$keySecret = 'your-access-key-secret';

$client = createClient($keyId, $keySecret);

$bucket = 'your-bucket-name';

$key = 'your-object-key';

putStringObject($client, $bucket, $key, '123');

putStringObject($client, $bucket, $key, fopen('/path/to/file.txt', 'r'), filesize('/path/to/file.txt'));

getObject($client, $bucket, $key);

deleteObject($client, $bucket, $key);
