<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 5/25/14
 * Time: 11:20 AM
 */

namespace App\Map\Helper;


class StaticFileManager
{
    protected static $_css = [];
    protected static $_js = [];

    public static function addJsFile($js)
    {
        array_push(self::$_js, $js);
        self::$_js = array_unique(self::$_js);
        return true;
    }

    public static function addCssFile($css)
    {
        array_push(self::$_css, $css);
        self::$_css = array_unique(self::$_css);
        return true;
    }

    public static function getCss()
    {
        return self::$_css;
    }

    public static function getJs()
    {
        return self::$_js;
    }

    public static function outputCss()
    {
        $str = '';
        foreach (self::$_css as $css) {
            $str = '<link rel="stylesheet" href="' . $css . '"/>' . "\n";
        }
        return $str;
    }

    public static function outputJs()
    {
        $str = '';
        foreach (self::$_js as $js) {
            $str = '<script src="' . $js . '" </script>' . "\n";
        }
        return $str;
    }
}
