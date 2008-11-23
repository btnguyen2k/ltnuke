<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Cache management interface.
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
 * @id			$Id: ClassICacheManager.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
 * @since      	File available since v0.1
 */

/**
 * Cache management interface.
 *
 * @package    	Cache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Cache_ICacheManager {
    /**
     * Perform clean-up work before shutting down this cache manager.
     *
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function destroy();

    /**
     * Performs initializing work before using this cache manager.
     *
     * @param Ddth_Cache_CacheManagerConfig
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function init($config);
    
    /**
     * Returns a short information/description about the cache manager.
     * 
     * @return string
     */
    public function getCacheManagerInfo();
    
    /**
     * Gets a cache by its name.
     * 
     * @param string
     * @return Ddth_Cache_ICache
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function getCache($name);
    
    /**
     * Shortcut for {@link getCache()}.{@link Ddth_Cache_ICache::clear() clear()}.
     * 
     * @param string
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function clearCache($name);
    
    /**
     * Shortcut for {@link getCache()}.{@link Ddth_Cache_ICache::get() get()}.
     * 
     * @param string
     * @param mixed
     * @return mixed
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function getFromCache($name, $key);
    
    /**
     * Shortcut for {@link getCache()}.{@link Ddth_Cache_ICache::put() put()}.
     * 
     * @param string
     * @param mixed
     * @param mixed
     * @return mixed old entry with the same key (if exists)
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function putToCache($name, $key, $value);
    
    /**
     * Shortcut for {@link getCache()}.{@link Ddth_Cache_ICache::remove() remove()}.
     * 
     * @param string
     * @param mixed
     * @return mixed existing entry associated with the key (if exists)
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function removeFromCache($name, $key);    
}
?>