<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 3/4/14
 * Time: 9:31 AM
 */

namespace App\Blog\Helper;


use Library\Core\View;

class Meta
{
    static $siteName = '半叶寒羽';
    static $subName = '专注Linux+PHP+HTML5技术';


    public static function set(View $view)
    {
        $uri = $view->uri->getMvcUri();
        $uri = ltrim($uri, '/' . $view->uri->getModule());
        $title = isset(static::$meta[$uri]['title']) ? static::$meta[$uri]['title'] : '';
        $fullName = self::$siteName . ' ‧ ' . self::$subName;
        $view->title = $title . ' - ' . self::$siteName . '‧' . self::$subName;
        $view->keywords = empty(static::$meta[$uri]['keywords']) ? $fullName : static::$meta[$uri]['keywords'];
        $view->description = empty(static::$meta[$uri]['description']) ? $fullName : static::$meta[$uri]['description'];
        $view->siteName = $fullName;
    }

    static $meta = array(
        'index/index' => array(
            'title' => '首页',
            'keywords' => '经纬度查询,GPS纠偏,地理解析,php+html5技术,Linux,js,jquery,mongodb,mysql,前端工具箱,stephp,google map,半叶寒羽,yanue,yanue.net',
            'description' => '半叶寒羽,专注Linux+PHP+HTML5技术.拥有丰富经纬查询转换,GPS纠偏,地理解析经验.网站主要包含技术文章,前端工具箱,地图经纬度GPS转换工具等板块.其中技术文章主要包含Linux,php,html5,google map等，內容部分源自网络.',
        ),

        'about/links' => array(
            'title' => '友情链接',
            'keywords' => '',
            'description' => '',
        ),

        'post/tags' => array(
            'title' => '文章标签',
            'keywords' => '',
            'description' => '',
        ),

        'post/category' => array(
            'title' => '网站类目',
            'keywords' => '',
            'description' => '',
        ),

        'about/sitemap' => array(
            'title' => '网站地图',
            'keywords' => '',
            'description' => '',
        ),

        'about/index' => array(
            'title' => '关于我',
            'keywords' => '',
            'description' => '',
        ),


    );

} 
