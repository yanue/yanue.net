<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 5/11/14
 * Time: 12:11 PM
 */

namespace App\Map\Controller\Google;


use App\Map\Controller\AppController;
use App\Map\Helper\StaticFileManager;

class GeoController extends AppController
{
    public function toLatLngAction()
    {
        StaticFileManager::addJsFile('https://maps.google.com/maps/api/js?sensor=false&libraries=places&language=zh');
    }
} 