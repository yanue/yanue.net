<?php

/**
 * User: yanue
 * Date: 10/22/13
 * Time: 2:02 PM
 * Modified: wgwang 2013-10-25
 */
namespace Library\Core;

class Config {
    static $settings = null;
    
    /**
     * load config data into static scale
     * 
     * @param string $file            
     */
    public static function load($file = 'config') {
        if (! isset ( self::$settings [$file] )) {
            $appSettings = array ();
            $siteSettings = array ();
            $configFile = WEB_ROOT . '/app/' . APP_NAME . '/config/' . $file . '.php';
            if (file_exists ( $configFile )) {
                $appSettings = include ($configFile);
            }
            unset ( $configFile );
            
            $configFile = WEB_ROOT . '/config/' . $file . '.php';
            if (file_exists ( $configFile )) {
                $siteSettings = include ($configFile);
            }
            unset ( $configFile );
            self::$settings [$file] = array_merge ( $siteSettings, $appSettings );
            unset ( $siteSettings );
            unset ( $appSettings );
        }
        return self::$settings [$file];
    }
    
    /**
     * 获取基本配置信息
     *
     * @param
     *            $key
     * @return string
     */
    public static function getBase($key) {
        if (! isset ( self::$settings ['config'] )) {
            self::load ();
        }
        return isset ( self::$settings ['config'] [$key] ) ? self::$settings ['config'] [$key] : null;
    }
    
    /**
     * 获取任意配置，如果是非config文件的配置，需要先load
     * 
     * @param string $key            
     * @return Ambigous <string, NULL>|Ambigous <>|NULL
     */
    public static function getItem($key, $file = null) {
        if (empty ( $key )) {
            return null;
        }
        $val = self::getBase ( $key );
        if (! is_null ( $val )) {
            return $val;
        } else {
            foreach ( self::$settings as $conf ) {
                if (isset ( $conf [$key] )) {
                    return $conf [$key];
                }
            }
            return null;
        }
    }
    
    /**
     * 修改一个配置，如果是在非config文件中设置的，需要先load
     *
     * @param $key
     * @param $val
     * @param null $file
     * @return bool
     */
    public static function setItem($key, $val, $file = null) {
        if (empty ( $key ) || is_null ( $val )) {
            return false;
        }
        if (isset ( self::$settings [$key] )) {
            self::$settings [$key] = $val;
            return true;
        }
        foreach ( self::$settings as $kk => $conf ) {
            if (isset ( $conf [$key] )) {
                self::$settings [$kk] [$key] = $val;
                return true;
            }
        }
        return false;
    }
    
    /**
     * 从app->config获取配置信息
     *
     * @param
     *            $file
     * @param string $key            
     * @return string
     */
    public static function getApp($file, $key='') {
        if (!isset(self::$settings [APP_NAME] [$file])) {
            $full_file = WEB_ROOT . '/app/' . APP_NAME . '/config/' . $file . '.php';
            if (file_exists ( $full_file )) {
                self::$settings [APP_NAME] [$file] = include ($full_file);
            }
        }
        $appsettings = self::$settings [APP_NAME] [$file];
        if (! $key) {
            return $appsettings;
        }
        return isset ( $appsettings [$key] ) ? $appsettings [$key] : null;
    }
    
    /**
     * 从app->config获取配置信息
     * 在不同的APP地方调用时使用
     *
     * @param
     *            $file
     * @param string $key
     * @return string
     */
    public static function getAppNew($app, $file, $key='') {
        if (!isset(self::$settings [$app] [$file])) {
            $full_file = WEB_ROOT . '/app/' . $app . '/config/' . $file . '.php';
            if (file_exists ( $full_file )) {
                self::$settings [$app] [$file] = include ($full_file);
            }
        }
        $appsettings = self::$settings [$app] [$file];
        if (! $key) {
            return $appsettings;
        }
        return isset ( $appsettings [$key] ) ? $appsettings [$key] : null;
    }
    
    /**
     * 获取site->config配置文件信息
     *
     * @param
     *            $file
     * @param
     *            $key
     * @return null string
     */
    public static function getSite($file, $key=null) {
        $full_file = WEB_ROOT . '/config/' . $file . '.php';
        if (! isset(self::$settings ['config'] [$file])) {
            self::$settings ['config'] [$file] = include ($full_file);
        }
        if (! $key) {
            return self::$settings ['config'] [$file];
        }
        return isset ( self::$settings ['config'] [$file] [$key] ) ? self::$settings ['config'] [$file] [$key] : null;
    }
    
    /**
     * 获取app->router路由信息
     *
     * @return string
     */
    public static function getRouter(){

        $full_file = WEB_ROOT.'/app/'.APP_NAME.'/config/router.php';

        if( !isset(self::$settings['router'] ) ){
            self::$settings['router'] = file_exists($full_file) ? include( $full_file ) : null ;
        }else{

        }
    
        return self::$settings['router'];
    }
    
}