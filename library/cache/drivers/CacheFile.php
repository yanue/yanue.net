<?php
namespace Library\Cache\Drivers;

use Library\Cache\Driver;
use Library\Core\Config;
/**
 * Caching Driver Class
 *
 * @package		Library
 * @subpackage	Cache
 * @category    Driver
 * @author		wgwang
 * @copyright   ainana
 * @since       Version 1.0
 * @date        2013年10月15日
 * @link
 */
class CacheFile extends Driver {

	/**
	 * Directory in which to save cache files
	 *
	 * @var string
	 */
	protected $_cache_path;

	/**
	 * Initialize file-based cache
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$path = Config::getItem('cache.filePath');
		$this->_cache_path = ($path === '') ? APPPATH.'cache/' : $path;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Library\Cache\Driver::init()
	 */
	public function init(){
	    
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch from cache
	 *
	 * @param	mixed	unique key id
	 * @return	mixed	data on success/false on failure
	 */
	public function get($id)
	{
		if ( ! file_exists($this->_cache_path.$id))
		{
			return FALSE;
		}

		$data = unserialize(file_get_contents($this->_cache_path.$id));

		if ($data['ttl'] > 0 && time() > $data['time'] + $data['ttl'])
		{
			unlink($this->_cache_path.$id);
			return FALSE;
		}

		return $data['data'];
	}

	// ------------------------------------------------------------------------

	/**
	 * Save into cache
	 *
	 * @param	string	unique key
	 * @param	mixed	data to store
	 * @param	int	length of time (in seconds) the cache is valid
	 *				- Default is 60 seconds
	 * @return	bool	true on success/false on failure
	 */
	public function save($id, $data, $ttl = 60)
	{
		$contents = array(
			'time'		=> time(),
			'ttl'		=> $ttl,
			'data'		=> $data
		);

		if (file_put_contents($this->_cache_path.$id, serialize($contents)))
		{
			@chmod($this->_cache_path.$id, 0660);
			return TRUE;
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete from Cache
	 *
	 * @param	mixed	unique identifier of item in cache
	 * @return	bool	true on success/false on failure
	 */
	public function delete($id)
	{
		return file_exists($this->_cache_path.$id) ? unlink($this->_cache_path.$id) : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Clean the Cache
	 *
	 * @return	bool	false on failure/true on success
	 */
	public function clean()
	{
		return unlink($this->_cache_path, true);
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * Not supported by file-based caching
	 *
	 * @param	string	user/filehits
	 * @return	mixed	FALSE
	 */
	public function cacheInfo($type = NULL)
	{
		return array(
		    fileatime($this->_cache_path),
		    filesize($this->_cache_path),
		    filectime($this->_cache_path),
		    fileowner($this->_cache_path),
		    filetype($this->_cache_path)
		);
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Cache Metadata
	 *
	 * @param	mixed	key to get cache metadata on
	 * @return	mixed	FALSE on failure, array on success.
	 */
	public function getMetaData($id)
	{
		if ( ! file_exists($this->_cache_path.$id))
		{
			return FALSE;
		}

		$data = unserialize(file_get_contents($this->_cache_path.$id));

		if (is_array($data))
		{
			$mtime = filemtime($this->_cache_path.$id);

			if ( ! isset($data['ttl']))
			{
				return FALSE;
			}

			return array(
				'expire' => $mtime + $data['ttl'],
				'mtime'	 => $mtime
			);
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Is supported
	 *
	 * In the file driver, check to see that the cache directory is indeed writable
	 *
	 * @return	bool
	 */
	public function isSupported()
	{
		return is_writable($this->_cache_path);
	}

}

/* End of file Cache_file.php */
/* Location: ./system/libraries/Cache/drivers/Cache_file.php */