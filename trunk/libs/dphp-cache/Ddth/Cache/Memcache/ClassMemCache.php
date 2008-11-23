<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Memcache implementation of cache.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Cache
 * @subpackage  Memcache
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassMemCache.php 151 2008-03-13 03:42:20Z nbthanh@vninformatics.com $
 * @since      	File available since v0.1
 */

if ( !function_exists('__autoload') ) {
    /**
     * Automatically loads class source file when used.
     *
     * @param string
     */
    function __autoload($className) {
        require_once 'Ddth/Commons/ClassDefaultClassNameTranslator.php';
        require_once 'Ddth/Commons/ClassLoader.php';
        $translator = Ddth_Commons_DefaultClassNameTranslator::getInstance();
        Ddth_Commons_Loader::loadClass($className, $translator);
    }
}

/**
 * Memcache implementation of cache.
 *
 * @package    	Cache
 * @subpackage  Memcache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Cache_Memcache_MemCache extends Ddth_Cache_AbstractCache {

    const SYNC_OUTGOING = true;

    const SYNC_INCOMING = false;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var Array()
     */
    private $storage;

    public function getStorage() {
        return $this->storage;
    }

    /**
     * Constructs a new Ddth_Cache_Memcache_MemCache object.
     */
    public function __construct() {
    }

    /**
     * {@see Ddth_Cache_ICache::init()}
     */
    public function init($config, $manager) {
        $this->prefix = $config->getPrefix();
        if ( $this->prefix === NULL ) {
            $this->prefix = "";
        }
        $this->prefix .= '.'.$config->getCacheName();
        if ( strlen($this->prefix) > 31 ) {
            $this->prefix = md5($this->prefix);
        }
        parent::init($config, $manager);
        $this->syncStorage(self::SYNC_INCOMING);
    }

    /**
     * Syncs local storage instance with the global cached one.
     */
    protected function syncStorage($outgoing=true) {
        if ( $outgoing ) {
            $this->mcSet($this->prefix, $this->storage);
        } else {
            $this->storage = $this->mcGet($this->prefix);
            if ( $this->storage === NULL || !is_array($this->storage) ) {
                $this->storage = Array();
                $this->syncStorage(self::SYNC_OUTGOING);
            }
        }
    }

    /**
     * Deletes an item from memcache server.
     *
     * @param string
     */
    protected function mcDelete($key) {
        $memcache = $this->getCacheManager()->getMemcacheInstance();
        $memcache->delete("$key");
    }

    /**
     * Retrieves an item from memcache server.
     *
     * @param string
     * @return mixed
     */
    protected function mcGet($key) {
        $memcache = $this->getCacheManager()->getMemcacheInstance();
        $result = $memcache->get("$key");
        return $result !== false ? $result : NULL;
    }

    /**
     * Store an item to memcache server.
     *
     * @param string
     * @param mixed
     */
    protected function mcSet($key, $value, $flag=MEMCACHE_COMPRESSED, $timeout=0) {
        $memcache = $this->getCacheManager()->getMemcacheInstance();
        $memcache->set("$key", $value, $flag, $timeout);
    }

    /**
     * Generates a key string for a cache entry's key.
     *
     * @param string
     * @return string
     */
    protected function createKeyString($key) {
        $result = $this->getPrefix() . '.' . $key;
        if ( strlen($result) > 31 ) {
            $result = md5($result);
        }
        return $result;
    }

    /**
     * Gets prefix string.
     *
     * @return string
     */
    protected function getPrefix() {
        return $this->prefix;
    }

    /**
     * {@see Ddth_Cache_ICache::getCacheManager()}
     * @return Ddth_Cache_Memcache_MemCacheManager
     */
    public function getCacheManager() {
        return parent::getCacheManager();
    }

    /**
     * {@see Ddth_Cache_ICache::clear()}
     */
    public function clear() {
        $this->syncStorage(self::SYNC_INCOMING);
        foreach ( $this->storage as $key=>$value ) {
            $this->mcDelete($key);
        }
        $this->storage = Array();
        $this->syncStorage(self::SYNC_OUTGOING);
    }

    /**
     * {@see Ddth_Cache_ICache::countElementsInMemory()}
     */
    public function countElementsInMemory() {
        $this->syncStorage(self::SYNC_INCOMING);
        return count($this->storage);
    }

    /**
     * {@see Ddth_Cache_ICache::exists()}
     */
    public function exists($key) {
        $this->syncStorage(self::SYNC_INCOMING);
        $key = $this->createKeyString($key);
        return isset($this->storage[$key]);
    }

    /**
     * {@see Ddth_Cache_ICache::get()}
     */
    public function get($key) {
        $this->syncStorage(self::SYNC_INCOMING);
        $key = $this->createKeyString($key);
        $entry = $this->mcGet($key);
        if ( $entry !== NULL && $entry instanceof Ddth_Cache_CacheEntry ) {
            if ( !$entry->isExpired() ) {
                $value = $entry->getValue();
                $timeout = $this->getTimeout();
                $this->mcSet($key, $entry, MEMCACHE_COMPRESSED, $timeout);
                $this->storage[$key] = $entry->getLastAccessTimestamp();
                asort($this->storage, SORT_NUMERIC);
                $this->syncStorage(self::SYNC_OUTGOING);
                return $value;
            }
        }
        if ( isset($this->storage[$key]) ) {
            //removed expired entry
            unset($this->storage[$key]);
            $this->syncStorage(self::SYNC_OUTGOING);
        }
        return NULL;
    }

    /**
     * {@see Ddth_Cache_ICache::put()}
     */
    public function put($key, $value) {
        $this->syncStorage(self::SYNC_INCOMING);
        $key = $this->createKeyString($key);
        $timeout = $this->getTimeout();
        $oldEntry = $this->mcGet($key);
        $newEntry = new Ddth_Cache_CacheEntry($value, $timeout);
        $this->mcSet($key, $newEntry, MEMCACHE_COMPRESSED, $timeout);
        $this->storage[$key] = $newEntry->getLastAccessTimestamp();
        asort($this->storage, SORT_NUMERIC);
        $this->checkCapacity();
        $this->syncStorage(self::SYNC_OUTGOING);
        if ( $oldEntry !== NULL && $oldEntry instanceof Ddth_Cache_CacheEntry ) {
            if ( !$oldEntry->isExpired() ) {
                return $oldEntry->getValue();
            }
        }
        return NULL;
    }

    /**
     * Re-validates this cache's capacity.
     */
    protected function checkCapacity() {
        $capacity = $this->getCapacity();
        while ( $capacity > 0 && $capacity < count($this->storage) ) {
            foreach ( $this->storage as $key=>$value ) {
                $this->mcDelete($key);
                unset($this->storage[$key]);
                break;
            }
        }
    }

    /**
     * {@see Ddth_Cache_ICache::remove()}
     */
    public function remove($key) {
        $this->syncStorage(self::SYNC_INCOMING);
        $key = $this->createKeyString($key);
        $oldEntry = $this->mcGet($key);
        $this->mcDelete($key);
        if ( isset($this->storage[$key]) ) {
            unset($this->storage[$key]);
        }
        $this->syncStorage(self::SYNC_OUTGOING);
        if ( $oldEntry !== NULL && $oldEntry instanceof Ddth_Cache_CacheEntry ) {
            if ( !$oldEntry->isExpired() ) {
                return $oldEntry->getValue();
            }
        }
        return NULL;
    }
}
?>