<?php

namespace Library\Cache;

use Library\Cache\Exception\CacheDriverNotFoundException;
use Library\Core\Loader;

/**
 * Cache Driver Library Class
 *
 * This class enables you to create "Driver" libraries that add runtime ability
 * to extend the capabilities of a class via additional driver objects
 *
 * @package Library
 * @subpackage cache
 * @author wgwang
 * @copyright ainana
 * @since Version 1.0
 * @link
 *
 */
class DriverLibrary {
    
    /**
     * Array of drivers that are available to use with the driver class
     *
     * @var array
     */
    protected $validDrivers = array ();
    
    /**
     * Name of the current class - usually the driver class
     *
     * @var string
     */
    protected $libPath;
    
    /**
     * Get magic method
     *
     * The first time a child is used it won't exist, so we instantiate it
     * subsequents calls will go straight to the proper child.
     *
     * @param
     *            string	Child class name
     * @return object class
     */
    public function __get($child) {
        // Try to load the driver
        return $this->loadDriver ( $child );
    }
    
    /**
     * Load driver
     * --Separate load_driver call to support explicit driver load by library or user
     *
     * @param $driver Driver name (w/o parent prefix)
     * @return object object class
     * @throws Exception\CacheDriverNotFoundException
     */
    public function loadDriver($driver) {
        // Get CodeIgniter instance and subclass prefix
        // $prefix = config_item('cache.driver.class.prefix');
        $prefix = 'Cache';
        if (! isset ( $this->libPath )) {
            // Get library name without any prefix
            $this->libPath = rtrim ( get_class ( $this ) , $prefix) . 'Drivers\\';
        }
         $this->libPath;
        // spl autoload
        $_namespaceClass =  $this->libPath. $prefix. ucfirst ( $driver );

        // See if requested child is a valid driver
        if (! in_array ( $driver, $this->validDrivers )) {
            // The requested driver isn't valid!
            $msg = 'Invalid driver requested: ' . $_namespaceClass;
            throw new CacheDriverNotFoundException ( $msg );
        }

        // Did we finally find the class?
        if(class_exists($_namespaceClass,true)){
            // Instantiate, decorate and add child
            $obj = new $_namespaceClass ();
            $obj->decorate ( $this );
            $this->$driver = $obj;
        } else {
            $msg = 'Unable to load the requested driver: ' . $_namespaceClass;
            throw new CacheDriverNotFoundException ( $msg );
        }

        return $this->$driver;
    }
}
