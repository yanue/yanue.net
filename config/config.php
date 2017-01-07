<?php

# ==== php配置 ====
$config['timezone'] = "PRC";
$config['display_errors'] = false;
$config['debug'] = false;

# ==== 默认应用配置 =====
$config['module'] = "";
$config['controller'] = "index";
$config['action'] = "index";
$config['suffix'] = ".html"; # do not remove '.'

$config['domain.main'] = 'yanue.net';
$config['domain.src'] = 'src.yanue.net';
$config['domain.img'] = 'img.yanue.net';
$config['domain.map'] = 'map.yanue.net';
$config['domain.api'] = 'api.yanue.net';
$config['domain.admin'] = 'admin.yanue.net';


//  share session
ini_set("session.cookie_domain", '.' . $config['domain.main']);

// XunSearch app name
!defined('XUNSEARCH_APPNAME') AND define('XUNSEARCH_APPNAME', 'yanue');

return $config;
