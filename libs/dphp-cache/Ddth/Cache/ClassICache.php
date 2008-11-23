<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Representation of a cache.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Cache
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassICache.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
 * @since      	File available since v0.1
 */

/**
 * Representation of a cache.
 *
 * @package    	Cache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Cache_ICache {
    /**
     * Removes all entries from this cache.
     *
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function clear();

    /**
     * Clean-up method.
     *
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function destroy();
    
    /**
     * Initializing method.
     *
     * @param Ddth_Cache_CacheConfig
     * @param Ddth_Cache_CacheManager
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function init($config, $manager);

    /**
     * Checks if an entry exists in this cache.
     *
     * @param mixed
     * @return bool
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function exists($key);

    /**
     * Retrieves a cache entry from this cache.
     *
     * @param mixed
     * @return mixed
     * @throws {@link Ddth_Cache_CacheException CacheException} 
     */
    public function get($key);
    
    /**
     * Gets cache's capacity.
     * 
     * @return integer
     */
    public function getCapacity();
    
    /**
     * Gets this cache's associated cache manager.
     *
     * @return Ddth_Cache_ICacheManager
     */
    public function getCacheManager();

    /**
     * Gets this cache's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Counts number of entries within this cache currently contained in the
     * in-memory store.
     *
     * @return integer
     */
    public function countElementsInMemory();

    /**
     * Puts an entry into this cache.
     *
     * @param mixed
     * @param mixed
     * @return mixed old entry with the same key (if exists)
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function put($key, $value);

    /**
     * Removes an entry from this cache.
     *
     * @param mixed
     * @return mixed existing entry associated with the key (if exists)
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function remove($key);
}
?>