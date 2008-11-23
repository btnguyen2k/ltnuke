<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Memcache implementation of cache manager.
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
 * @id			$Id: ClassMemCacheManager.php 161 2008-04-17 04:48:57Z btnguyen2k@gmail.com $
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
 * Memcache implementation of cache manager.
 *
 * @package    	Cache
 * @subpackage  Memcache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Cache_Memcache_MemCacheManager extends Ddth_Cache_AbstractCacheManager {
    /**
     * @var Ddth_Commons_Logging_ILog;
     */
    private $LOGGER;

    /**
     * @var Memcache
     */
    private $memcache = NULL;

    /**
     * Constructs a new Ddth_Cache_Memcache_MemCacheManager object.
     */
    public function __construct() {
        parent::__construct();
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
    }

    /**
     * {@see Ddth_Cache_ICacheManager::init()}
     */
    public function init($config) {
        $memcacheConfig = new Ddth_Cache_Memcache_MemCacheManagerConfig($config);
        $servers = $memcacheConfig->getServerNames();
        if ( $servers === NULL || count($servers)===0 ) {
            $this->LOGGER->error("No memcache server defined!");
        } else {
            $this->memcache = new Memcache();
            foreach ( $servers as $serverName ) {
                $serverSettings = $memcacheConfig->getServerSettings($serverName);
                $host = $serverSettings['host'];
                $port = $serverSettings['port'];
                $this->memcache->addServer($host, $port);
                $this->LOGGER->info("Added memcache server [$host:$port]");
            }
            $compressThreshold = $memcacheConfig->getCompressThreshold();
            if ( $compressThreshold > 0 ) {
                $this->memcache->setCompressThreshold($compressThreshold);
            }
        }
        parent::init($memcacheConfig);
    }
    
    /**
     * Gets the Memcache instance.
     * 
     * @return Memcache
     */
    public function getMemcacheInstance() {
        return $this->memcache;
    }

    /**
     * {@see Ddth_Cache_ICacheManager::getCacheManagerInfo()}.
     */
    public function getCacheManagerInfo() {
        if ( $this->memcache === NULL ) {
            return "[Memcache-CacheManager] No memcache server found!";
        } else {
            return "[Memcache-CacheManager] Memcache server version ".$this->memcache->getVersion();
        }        
    }

    /**
     * {@see Ddth_Cache_ICacheManager::getCache()}
     * @return Ddth_Cache_Memcache_MemCache
     */
    public function getCache($name) {
        $cacheConfig = $this->getConfig()->getCacheConfig($name);
        $cache = new Ddth_Cache_Memcache_MemCache();
        $cache->init($cacheConfig, $this);
        return $cache;
    }
}
?>