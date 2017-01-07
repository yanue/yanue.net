<?php
namespace Library\Util;

if ( ! defined('LIB_PATH')) exit('No direct script access allowed');
/**
 * cookie 处理类
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */


class Cookie
{

    const OneHour = 3600;

    /**
     * Returns true if there is a cookie with this name.
     *
     * @param string $name
     * @return bool
     */
    static public function exists($name)
    {
        $name = md5($name);
        return isset($_COOKIE[$name]);
    }

    /**
     * Returns true if there no cookie with this name or it's empty, or 0,
     * or a few other things. Check http://php.net/empty for a full list.
     *
     * @param string $name
     * @return bool
     */
    static public function isEmpty($name)
    {
        $name = md5($name);
        return empty($_COOKIE[$name]);
    }

    /**
     * Get the value of the given cookie. If the cookie does not exist the value
     * of $default will be returned.
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    static public function get($name, $default = '')
    {
        if(self::exists($name)) {
            $name = md5($name);
            $value = $_COOKIE[$name];
            $value = iconv('GBK', 'UTF-8', $value);
            $value = Encrypt::instance()->setData($value)->decode();
            return unserialize($value);
        }
        else {
            return $default;
        }
    }

    /**
     * Get all the value of cookies.
     *
     * @return mixed
     */
    static public function getAll()
    {
        return $_COOKIE;
    }

    /**
     * Set a cookie. Silently does nothing if headers have already been sent.
     *
     * @param string $name
     * @param mixed $value
     * @param mixed $expiry
     * @param string $path
     * @param string $domain
     * @return bool
     */
    static public function set($name, $value, $expiry = self::OneHour, $path = '/', $domain = false)
    {
        $name = md5($name);
        $value = serialize($value);
        $value = Encrypt::instance()->setData($value)->encode();
        $retval = false;
        if (!headers_sent())
        {
            if ($domain === false) {
                $domain = $_SERVER['HTTP_HOST']!='localhost' ? $_SERVER['HTTP_HOST'] : '';
                if(!empty($domain)) {
                    $domain = explode('.', $domain);
                    $domain = array_reverse($domain);
                    $ext = current($domain);
                    $main = next($domain);
                    $domain = $main . "." . $ext;
                }
            }

            if ($expiry === -1) {
                $expiry = 1893456000; // Lifetime = 2030-01-01 00:00:00
            }
            elseif (is_numeric($expiry)) {
                $expiry += time();
            }
            else {
                $expiry = strtotime($expiry);
            }

            $retval = setcookie($name, $value, $expiry, $path, $domain);
            if ($retval) {
                $_COOKIE[$name] = $value;
            }
        }
        return $retval;
    }

    /**
     * Delete a cookie.
     *
     * @param string $name
     * @param string $path
     * @param string $domain
     * @param bool $remove_from_global Set to true to remove this cookie from this request.
     * @return bool
     */
    static public function del($name, $path = '/', $domain = false, $remove_from_global = true)
    {
        $name = md5($name);
        $retval = false;
        if (!headers_sent())
        {
            if ($domain === false) {
                $domain = $_SERVER['HTTP_HOST']!='localhost' ? $_SERVER['HTTP_HOST'] : '';
            }
            if(!empty($domain)) {
                $domain = explode('.', $domain);
                $domain = array_reverse($domain);
                $ext = current($domain);
                $main = next($domain);
                $domain = $main . "." . $ext;
            }
            $retval = setcookie($name, '', time() - 3600, $path, $domain);

            if ($remove_from_global) {
                unset($_COOKIE[$name]);
            }
        }
        return $retval;
    }

    /**
     * 清除session
     *
     * @return boolean
     */

    public static function destroy()
    {
        // unset cookies
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
    }
}