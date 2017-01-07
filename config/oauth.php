<?php
// oauth config for third site

// weibo
$config['weibo'] = [
    'appid'     =>'1969432941',
    'appkey'    =>'81f3e21744bafc1a750161f135c5e586',
    'callback'  =>'/user/oauth/wbback'
];

// qq
$config['qq'] = [
    'appid'     =>'100568791',
    'appkey'    =>'de0b712f16f8fa27d8d0f9738b9eebe3',
    'callback'  =>'/user/oauth/qqback'
];

// renren
$config['renren'] = [
    'appid'     =>'220486',
    'appkey'    =>'4b05e054150745af893bae55be62f2b9',
    'appsert'   =>'ce50c6ee497a4f5d9d014953fd282f0f',
    'callback'  =>'/user/oauth/rrback'
];

// douban
$config['douban'] = [
    'appid' =>'01cd3472b9bd583a05679d582d3808ae',
    'appkey'    =>'7b0989f14e08482a',
    'callback'  =>'/user/oauth/dbback'
];

return $config;