<?php

namespace Library\Cache;

use Library\Cache\Exception\CacheNoMethodException;
use \ReflectionObject;

/**
 * Driver Class
 *
 * This class enables you to create drivers for a Library based on the Driver Library.
 * It handles the drivers' access to the parent library
 *
 * @package Library
 * @subpackage Cache
 * @author wgwang
 * @copyright ainana
 * @since Version 1.0
 * @link
 *
 */
abstract class Driver {
    
    /**
     * Instance of the parent class
     *
     * @var object
     */
    protected $_parent;
    
    /**
     * List of methods in the parent class
     *
     * @var array
     */
    protected $_methods = array ();
    
    /**
     * List of properties in the parent class
     *
     * @var array
     */
    protected $_properties = array ();
    
    /**
     * Array of methods and properties for the parent class(es)
     *
     * @static
     *
     * @var array
     */
    protected static $_reflections = array ();
    
    /**
     * define a abstract method to init a driver to use
     * 
     * @return bool
     */
    public abstract function init();
    
    /**
     * Decorate
     *
     * Decorates the child with the parent driver lib's methods and properties
     *
     * @param
     *            object
     * @return void
     */
    public function decorate($parent) {
        $this->_parent = $parent;
        
        // Lock down attributes to what is defined in the class
        // and speed up references in magic methods
        
        $class_name = get_class ( $parent );
        
        if (! isset ( self::$_reflections [$class_name] )) {
            $r = new ReflectionObject ( $parent );
            
            foreach ( $r->getMethods () as $method ) {
                if ($method->isPublic ()) {
                    $this->_methods [] = $method->getName ();
                }
            }
            
            foreach ( $r->getProperties () as $prop ) {
                if ($prop->isPublic ()) {
                    $this->_properties [] = $prop->getName ();
                }
            }
            
            self::$_reflections [$class_name] = array (
                $this->_methods,
                $this->_properties 
            );
        } else {
            list ( $this->_methods, $this->_properties ) = self::$_reflections [$class_name];
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * __call magic method
     *
     * Handles access to the parent driver library's methods
     *
     * @param
     *            string
     * @param
     *            array
     * @return mixed
     */
    public function __call($method, $args = array()) {
        if (in_array ( $method, $this->_methods )) {
            return call_user_func_array ( array (
                $this->_parent,
                $method 
            ), $args );
        }
        
        $trace = debug_backtrace ();
        throw new CacheNoMethodException ( E_ERROR, "No such method '{$method}'", $trace [1] ['file'], $trace [1] ['line'] );
        exit ( EXIT_UNKNOWN_METHOD );
    }
    
    // --------------------------------------------------------------------
    
    /**
     * __get magic method
     *
     * Handles reading of the parent driver library's properties
     *
     * @param
     *            string
     * @return mixed
     */
    public function __get($var) {
        if (in_array ( $var, $this->_properties )) {
            return $this->_parent->$var;
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * __set magic method
     *
     * Handles writing to the parent driver library's properties
     *
     * @param
     *            string
     * @param
     *            array
     * @return mixed
     */
    public function __set($var, $val) {
        if (in_array ( $var, $this->_properties )) {
            $this->_parent->$var = $val;
        }
    }
}

/* End of file Driver.php */
/* Location: ./system/libraries/Driver.php */