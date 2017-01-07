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

// Sample of list buckets
function listBuckets(OSSClient $client) {
    $buckets = $client->listBuckets();

    foreach ($buckets as $bucket) {
        echo 'Bucket: ' . $bucket->getName() . "\n";
    }
}

// Sample of create Bucket
function createBucket(OSSClient $client, $bucket) {
    $client->createBucket(array(
        'Bucket' => $bucket,
    ));
}

// Sample of get Bucket Acl
function getBucketAcl(OSSClient $client, $bucket) {
    $acl = $client->getBucketAcl(array(
        'Bucket' => $bucket,
    ));

    $grants = $acl->getGrants();
    echo $grants[0];
}

// Sample of delete Bucket
function deleteBucket(OSSClient $client, $bucket) {
    $client->deleteBucket(array(
        'Bucket' => $bucket,
    ));
}

$keyId = 'your-access-key-id';

$keySecret = 'your-access-key-secret';

$client = createClient($keyId, $keySecret);

$bucket = 'your-bucket-name';

listBuckets($client);
createBucket($client, $bucket);
getBucketAcl($client, $bucket);
deleteBucket($client, $bucket);



