<?php

namespace Library\Cache;

use Library\Core\Config;
use Library\Core\Debug;

/**
 * CodeIgniter Caching Class
 *
 * @package Library
 * @subpackage Cache
 * @author wgwang
 * @copyright ainana
 * @since Version 1.0
 *        @date 2013年10月15日
 * @link
 *
 */
class Cache extends DriverLibrary {
    
    /**
     *
     * @var Cache
     */
    private static $instance = null;
    /**
     * Valid cache drivers
     *
     * @var array
     */
    protected $validDrivers = array (
        'apc',
        'dummy',
        'file',
        'memcached',
        'redis',
        'wincache' 
    );
    
    /**
     * Path of cache files (if file-based cache)
     *
     * @var string
     */
    protected $_cachePath = NULL;
    
    /**
     * Reference to the driver
     *
     * @var mixed
     */
    protected $_adapter = 'dummy';
    
    /**
     * Fallback driver
     *
     * @var string
     */
    protected $_backupDriver = 'dummy';
    
    /**
     * Cache key prefix
     *
     * @var string
     */
    public $keyPrefix = '';
    
    /**
     * Name of the current class - usually the driver class
     *
     * @var string
     */
    protected $libName;
    
    /**
     * Constructor
     */
    private function __construct() {
        $defaultConfig = array (
            'adapter' => 'memcached' 
        );
        
        $config = Config::load ( 'cache' );
        $this->_adapter = $config ['cache.defaultDriver'];
        isset ( $config ['cache.keyPrefix'] ) && $this->keyPrefix = $config ['cache.keyPrefix'];
        
        if (isset ( $config ['backup'] ) && in_array ( $config ['backup'], $this->validDrivers )) {
            $this->_backupDriver = $config ['backup'];
        }
        
        // If the specified adapter isn't available, check the backup.
        if (! $this->isSupported ( $this->_adapter )) {
            if (! $this->isSupported ( $this->_backupDriver )) {
                // Backup isn't supported either. Default to 'Dummy' driver.
                $this->_adapter = 'dummy';
            } else {
                // Backup is supported. Set it to primary.
                $this->_adapter = $this->_backupDriver;
            }
        }
        
        $this->{$this->_adapter}->init ();
    }
    
    /**
     *
     *
     * Initialize class properties based on the configuration array.
     *
     * @param array $config
     *            array()
     * @return Cache
     */
    public static function getInstance($config = array()) {
        if (! self::$instance instanceof Cache) {
            self::$instance = new self ();
        }
        return self::$instance;
    }
    
    /**
     * Get magic method
     *
     * The first time a driver is used it won't exist, so we instantiate it
     * subsequents calls will go straight to the proper driver.
     *
     * @param
     *            string	Driver class name
     * @return object class
     */
    public function __get($driver) {
        // Try to load the driver
        return $this->loadDriver ( $driver );
    }
    
    /**
     * Load driver
     *
     * Separate load_driver call to support explicit driver load by library or user
     *
     * @param
     *            string	Driver name (w/o parent prefix)
     * @return object class
     */
    
    // ------------------------------------------------------------------------
    
    /**
     * Get
     *
     * Look for a value in the cache. If it exists, return the data
     * if not, return FALSE
     *
     * @param string $key            
     * @return mixed matching $id or FALSE on failure
     */
    public function get($key) {
        try {
            return $this->{$this->_adapter}->get ( $this->keyPrefix . md5($key) );
        }
        catch (\Exception $e) {
            Debug::log($e->getFile() . ":" . $e->getMessage());
            Debug::log($e->getTraceAsString());
            return false;
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Cache Save
     *
     * @param string $key            
     * @param mixed $data
     *            store
     * @param int $ttl
     *            60	Cache TTL (in seconds)
     * @return bool on success, FALSE on failure
     */
    public function set($key, $data, $ttl = 60) {
        return $this->{$this->_adapter}->save ( $this->keyPrefix . md5($key), $data, $ttl );
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete from Cache
     *
     * @param string $key            
     * @return bool on success, FALSE on failure
     */
    public function delete($key) {
        return $this->{$this->_adapter}->delete ( $this->keyPrefix . md5($key) );
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Clean the cache
     *
     * @return bool on success, FALSE on failure
     */
    public function clean() {
        return $this->{$this->_adapter}->clean ();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Cache Info
     *
     * @param string $type
     *            'user'	user/filehits
     * @return mixed containing cache info on success OR FALSE on failure
     */
    public function cacheInfo($type = 'user') {
        return $this->{$this->_adapter}->cacheInfo ( $type );
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get Cache Metadata
     *
     * @param string $key
     *            get cache metadata on
     * @return mixed item metadata
     */
    public function getMetaData($key) {
        return $this->{$this->_adapter}->getMetaData ( $this->keyPrefix . md5($key) );
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Is the requested driver supported in this environment?
     *
     * @param string $driver
     *            to test
     * @return array
     */
    public function isSupported($driver) {
        static $support = array ();
        
        if (! isset ( $support [$driver] )) {
            $support [$driver] = $this->{$driver}->isSupported ();
        }
        
        return $support [$driver];
    }
}

/* End of file Cache.php */
/* Location: ./system/libraries/Cache/Cache.php */