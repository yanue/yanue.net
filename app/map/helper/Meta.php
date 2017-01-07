<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 3/4/14
 * Time: 9:31 AM
 */

namespace App\Map\Helper;


use Library\Core\View;

class Meta
{
    static $siteName = '半叶寒羽';
    static $subName = '地图作品';

    public static function set(View $view)
    {
        $uri = $view->uri->getMvcUri();
        $title = isset(static::$meta[$uri]['title']) ? static::$meta[$uri]['title'] : '';
        $fullName = self::$siteName . ' ‧ ' . self::$subName;
        $view->title = $title;
        $view->keywords = empty(static::$meta[$uri]['keywords']) ? $fullName : static::$meta[$uri]['keywords'];
        $view->description = empty(static::$meta[$uri]['description']) ? $fullName : static::$meta[$uri]['description'];
        $view->siteName = $fullName;
    }

    static $meta = array(
        'index/index' => array(
            'title' => '首页',
            'keywords' => '经纬度转换,GPS纠偏,地理解析,php+html5技术,Linux,js,jquery,mongodb,mysql,前端工具箱,stephp,google map,半叶寒羽,yanue,yanue.net',
            'description' => '半叶寒羽,专注Linux+PHP+HTML5技术.拥有丰富经纬度转换,GPS纠偏,地理解析经验.网站主要包含技术文章,前端工具箱,地图经纬度GPS转换工具等板块.其中技术文章主要包含Linux,php,html5,google map等，內容部分源自网络.',
        ),

        'geocode/google/index' => array(
            'title' => 'Google Map经纬度地名批量快速转换工具 - 无需地图版',
            'keywords' => 'Google Map,经纬度,地址,地理解析,快速转换,无需地图版,Geocoder,Google Geocoding API接口实现,半叶寒羽,yanue,map.yanue.net',
            'description' => 'Google Map经纬度地名批量快速转换工具 - 无需地图版,根据Google Geocoding API接口,实现无需加载地图快速转换经纬度及地址,根据官方条件，每天查询请求不得超过2,500 个。快速无需加载地图，简单易用',
        ),

        'geocode/google/map' => array(
            'title' => 'Google Map经纬度地名批量快速转换工具 - 加载地图版',
            'keywords' => 'Google Map,经纬度,地址,地理解析,快速转换,无需地图版,Geocoder,Google Geocoding API接口实现,半叶寒羽,yanue,map.yanue.net',
            'description' => 'Google Map经纬度地名批量快速转换工具 - 无需地图版,根据Google Geocoding API接口,实现无需加载地图快速转换经纬度及地址,根据官方条件，每天查询请求不得超过2,500 个。快速无需加载地图，简单易用',
        ),

        'geocode/baidu/index' => array(
            'title' => 'Google Map经纬度地名批量快速转换工具 - 无需地图版',
            'keywords' => 'Google Map,经纬度,地址,地理解析,快速转换,无需地图版,Geocoder,Google Geocoding API接口实现,半叶寒羽,yanue,map.yanue.net',
            'description' => 'Google Map经纬度地名批量快速转换工具 - 无需地图版,根据Google Geocoding API接口,实现无需加载地图快速转换经纬度及地址,根据官方条件，每天查询请求不得超过2,500 个。快速无需加载地图，简单易用',
        ),

        'geocode/baidu/map' => array(
            'title' => 'Google Map经纬度地名批量快速转换工具 - 加载地图版',
            'keywords' => 'Google Map,经纬度,地址,地理解析,快速转换,无需地图版,Geocoder,Google Geocoding API接口实现,半叶寒羽,yanue,map.yanue.net',
            'description' => 'Google Map经纬度地名批量快速转换工具 - 无需地图版,根据Google Geocoding API接口,实现无需加载地图快速转换经纬度及地址,根据官方条件，每天查询请求不得超过2,500 个。快速无需加载地图，简单易用',
        ),

        'google/geo/toLatLng' => array(
            'title' => '在线查询经纬度 google查询地名返回经纬度 geocode geocoder的完整实例 根据google map api实现鼠标经过地图区域提示经纬度等',
            'keywords' => '功能最完善的地理解析经纬度 google map 地理解析经纬度 geocoder实例 geocode实例 鼠标经过地图区域提示经纬度 google map api应用',
            'description' => '运用google map api开发的地图系统，实现鼠标经过提示经纬度，自动填充地名地点名称，输入完成后可直接点击enter键进行解析，地理位置不准确，可以拖动重新解析，解析后经纬度信息显示完整'
        )

    );

} 