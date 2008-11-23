<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * An abstract implementation of cache manager.
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
 * @id			$Id: ClassAbstractCacheManager.php 161 2008-04-17 04:48:57Z btnguyen2k@gmail.com $
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
 * An abstract implementation of cache manager.
 *
 * @package    	Cache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Cache_AbstractCacheManager implements Ddth_Cache_ICacheManager {
    /**
     * @var Ddth_Commons_Logging_ILog;
     */
    private $LOGGER;

    /**
     * @var Ddth_Cache_CacheManagerConfig
     */
    private $config;

    /**
     * Constructs a new Ddth_Cache_AbstractCacheManager object.
     */
    public function __construct() {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
    }

    /**
     * {@see Ddth_Cache_ICacheManager::destroy()}.
     */
    public function destroy() {
    }

    /**
     * {@see Ddth_Cache_ICacheManager::init()}.
     */
    public function init($config) {
        $this->config = $config;
        $this->LOGGER->info($this->getCacheManagerInfo());
    }
    
    /**
     * {@see Ddth_Cache_ICacheManager::getCacheManagerInfo()}.
     */
    public function getCacheManagerInfo() {
        return (string)__CLASS__;
    }

    /**
     * Gets cache manager configuration object.
     *
     * @return Ddth_Cache_CacheManagerConfig
     */
    protected function getConfig() {
        return $this->config;
    }

    /**
     * Shortcut for {@link getCache()}.{@link Ddth_Cache_ICache::clear() clear()}.
     *
     * @param string
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function clearCache($name) {
        $cache = $this->getCache($name);
        if ( $cache === NULL ) {
            $msg = "Cache [$name] not found!";
            $this->LOGGER->warn($msg);
        } else {
            $cache->clear();
        }
    }

    /**
     * Shortcut for {@link getCache()}.{@link Ddth_Cache_ICache::get() get()}.
     *
     * @param string
     * @param mixed
     * @return mixed
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function getFromCache($name, $key) {
        $cache = $this->getCache($name);
        if ( $cache === NULL ) {
            $msg = "Cache [$name] not found!";
            $this->LOGGER->warn($msg);
        } else {
            return $cache->get($key);
        }
    }

    /**
     * Shortcut for {@link getCache()}.{@link Ddth_Cache_ICache::put() put()}.
     *
     * @param string
     * @param mixed
     * @param mixed
     * @return mixed old entry with the same key (if exists)
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function putToCache($name, $key, $value) {
        $cache = $this->getCache($name);
        if ( $cache === NULL ) {
            $msg = "Cache [$name] not found!";
            $this->LOGGER->warn($msg);
        } else {
            return $cache->put($key, $value);
        }
    }

    /**
     * Shortcut for {@link getCache()}.{@link Ddth_Cache_ICache::remove() remove()}.
     *
     * @param string
     * @param mixed
     * @return mixed existing entry associated with the key (if exists)
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public function removeFromCache($name, $key) {
        $cache = $this->getCache($name);
        if ( $cache === NULL ) {
            $msg = "Cache [$name] not found!";
            $this->LOGGER->warn($msg);
        } else {
            return $cache->remove($key);
        }
    }
}
?>