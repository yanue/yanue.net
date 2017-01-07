<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 5/11/14
 * Time: 12:11 PM
 */

namespace App\Map\Controller\Baidu;


use App\Map\Controller\AppController;
use App\Map\Helper\StaticFileManager;

class GeoController extends AppController
{
    public function toLatLngAction()
    {
        StaticFileManager::addJsFile('http://api.map.baidu.com/api?v=2.0&ak=CG8eakl6UTlEb1OakeWYvofh');
    }
} 