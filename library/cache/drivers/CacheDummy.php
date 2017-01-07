<?php
namespace Library\Cache\Drivers;

use Library\Cache\Driver;
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
class CacheDummy extends Driver {

    public function init() {
        return true;
    }
	/**
	 * Get
	 *
	 * Since this is the dummy class, it's always going to return FALSE.
	 *
	 * @param	string
	 * @return	bool	FALSE
	 */
	public function get($id)
	{
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Save
	 *
	 * @param	string	Unique Key
	 * @param	mixed	Data to store
	 * @param	int	Length of time (in seconds) to cache the data
	 * @return	bool	TRUE, Simulating success
	 */
	public function save($id, $data, $ttl = 60)
	{
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete from Cache
	 *
	 * @param	mixed	unique identifier of the item in the cache
	 * @return	bool	TRUE, simulating success
	 */
	public function delete($id)
	{
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Clean the cache
	 *
	 * @return	bool	TRUE, simulating success
	 */
	public function clean()
	{
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * @param	string	user/filehits
	 * @return	bool	FALSE
	 */
	 public function cacheInfo($type = NULL)
	 {
		 return FALSE;
	 }

	// ------------------------------------------------------------------------

	/**
	 * Get Cache Metadata
	 *
	 * @param	mixed	key to get cache metadata on
	 * @return	bool	FALSE
	 */
	public function getMetaData($id)
	{
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Is this caching driver supported on the system?
	 * Of course this one is.
	 *
	 * @return	bool	TRUE
	 */
	public function isSupported()
	{
		return TRUE;
	}

}

/* End of file Cache_dummy.php */
/* Location: ./system/libraries/Cache/drivers/Cache_dummy.php */