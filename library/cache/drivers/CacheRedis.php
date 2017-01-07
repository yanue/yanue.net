<?php

namespace Library\Cache\Drivers;

use Library\Cache\Driver;
use Library\Cache\Exception\CacheServerConnectFailedException;
use Library\Core\Config;

/**
 * Caching Driver Class
 *
 * @package Library
 * @subpackage Cache
 * @category Driver
 * @author wgwang
 * @copyright ainana
 * @since Version 1.0
 *        @date 2013年10月15日
 * @link
 *
 */
class CacheRedis extends Driver {
    /**
     * Default config
     *
     * @static
     *
     * @var array
     */
    protected static $_default_config = array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => 6379,
        'timeout' => 0 
    );
    
    /**
     * Redis connection
     *
     * @var Redis
     */
    protected $_redis;
    
    // ------------------------------------------------------------------------
    
    /**
     * Get cache
     *
     * @param
     *            string	Cache key identifier
     * @return mixed
     */
    public function get($key) {
        return $this->_redis->get ( $key );
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Save cache
     *
     * @param
     *            string	Cache key identifier
     * @param
     *            mixed	Data to save
     * @param
     *            int	Time to live
     * @return bool
     */
    public function save($key, $value, $ttl = NULL) {
        return ($ttl) ? $this->_redis->setex ( $key, $ttl, $value ) : $this->_redis->set ( $key, $value );
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete from cache
     *
     * @param
     *            string	Cache key
     * @return bool
     */
    public function delete($key) {
        return ($this->_redis->delete ( $key ) === 1);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Clean cache
     *
     * @return bool
     * @see Redis::flushDB()
     */
    public function clean() {
        return $this->_redis->flushDB ();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get cache driver info
     *
     * @param
     *            string	Not supported in Redis.
     *            Only included in order to offer a
     *            consistent cache API.
     * @return array
     * @see Redis::info()
     */
    public function cacheInfo($type = NULL) {
        return $this->_redis->info ();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get cache metadata
     *
     * @param
     *            string	Cache key
     * @return array
     */
    public function getMetaData($key) {
        $value = $this->get ( $key );
        
        if ($value) {
            return array (
                'expire' => time () + $this->_redis->ttl ( $key ),
                'data' => $value 
            );
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Check if Redis driver is supported
     *
     * @return bool
     */
    public function isSupported() {
        if (extension_loaded ( 'redis' )) {
            return TRUE;
        } else {
            // log_message('debug', 'The Redis extension must be loaded to use Redis cache.');
            return FALSE;
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Setup Redis config and connection
     *
     * Loads Redis config file if present. Will halt execution
     * if a Redis connection can't be established.
     *
     * @return bool
     * @see Redis::connect()
     */
    public function init() {
        $config = Config::getItem ( 'cache.drivers.redis' );
        if (is_null ( $config )) {
            $config = array ();
        }
        
        if ($CI->config->load ( 'redis', TRUE, TRUE )) {
            $config += $CI->config->item ( 'redis' );
        }
        
        $config = array_merge ( self::$_default_config, $config );
        
        $this->_redis = new \Redis ();
        
        try {
            $this->_redis->connect ( $config ['host'], $config ['port'], $config ['timeout'] );
        } catch ( \RedisException $e ) {
            throw new CacheServerConnectFailedException ();
            // show_error('Redis connection refused. ' . $e->getMessage());
        }
        
        if (isset ( $config ['password'] )) {
            $this->_redis->auth ( $config ['password'] );
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
     *
     *
     * Class destructor
     *
     * Closes the connection to Redis if present.
     *
     * @return void
     */
    public function __destruct() {
        if ($this->_redis) {
            $this->_redis->close ();
        }
    }
}

/* End of file Cache_redis.php */
/* Location: ./system/libraries/Cache/drivers/Cache_redis.php */