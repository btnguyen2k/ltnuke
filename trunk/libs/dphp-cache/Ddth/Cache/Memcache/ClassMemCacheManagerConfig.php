<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Memcache-specific cache manager configurations.
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
 * @id			$Id: ClassMemCacheManagerConfig.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
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
 * Memcache-specific cache manager configurations.
 *
 * Configuration file format: configurations are store in the
 * {@link Ddth_Cache_CacheManagerConfig global configuration file}. Memcache-specific
 * configuration settings are:
 * <code>
 * # All memcache.xxx properties are memcache-specific settings
 *
 * # Minimum data size (in bytes) before attempting to compress data automatically
 * # This setting is optional
 * memcache.compressThreshold=1024
 *
 * # List of memcache servers, each server is identified by a name (e.g. localhost, server2)
 * memcache.servers=localhost, server2
 *
 * # Configurations for server 'localhost'
 * memcache.server.localhost.host=localhost
 * memcache.server.localhost.port=11211
 *
 * # Configurations for server 'server2'
 * memcache.server.server2.host=192.168.0.254
 * memcache.server.server2.port=11211
 *
 * # Settings for default cache:
 * #
 * # Maximum number of items hold by the cache
 * # capacity=0 means 'unlimitted'
 * memcache.cache.default.capacity=1000
 *
 * # Maximum number of seconds an item can stay idle in memory before being expired
 * # timeout=0 means 'never expired'
 * memcache.cache.default.timeout=3600
 * 
 * # Settings for cache named 'test':
 * memcache.cache.test.capacity=100
 * memcache.cache.test.timeout=3600
 * </code>
 *
 * @package    	Cache
 * @subpackage  Memcache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Cache_Memcache_MemCacheManagerConfig extends Ddth_Cache_CacheManagerConfig {

    const PROPERTY_MC_COMPRESS_THRESHOLD = 'memcache.compressThreshold';

    const PROPERTY_MC_SERVERS = 'memcache.servers';

    const PROPERTY_PREFIX_MC_SERVER = 'memcache.server.';
    
    const PROPERTY_PREFIX_MC_CACHE = 'memcache.cache.';

    const DEFAULT_SERVER_PORT = 11211;

    /**
     * @var Array
     */
    private $cacheCacheConfig = Array();

    /**
     * Constructs a new Ddth_Cache_Memcache_MemCacheManagerConfig object.
     *
     * @param mixed an instance of Ddth_Commons_Properties or Ddth_Cache_CacheManagerConfig
     */
    public function __construct($params) {
        if ( $params instanceof Ddth_Commons_Properties ) {
            parent::__construct($params);
        } else {
            //must be an instance of Ddth_Cache_CacheManagerConfig
            parent::__construct($params->getProperties());
        }
    }

    /**
     * {@see Ddth_Cache_CacheManagerConfig::getCacheConfig()}.
     *
     * @return Ddth_Cache_Memcache_MemCacheConfig
     */
    public function getCacheConfig($name) {
        if ( !isset($this->cacheCacheConfig[$name]) ) {
            $props = new Ddth_Commons_Properties();
            $props->setProperty(Ddth_Cache_CacheConfig::PROPERTY_CACHE_NAME, $name);
            $prefix = $this->getCachePrefix();
            $props->setProperty(Ddth_Cache_CacheConfig::PROPERTY_CACHE_PREFIX, $prefix);

            $keyDefault = self::PROPERTY_PREFIX_MC_CACHE.'default.';
            $key = self::PROPERTY_PREFIX_MC_CACHE.$name.'.';

            $capacity = $this->getProperty($key.'capacity');
            if ( $capacity === NULL ) {
                $capacity = $this->getProperty($keyDefault.'capacity');
            }
            $capacity = $capacity !== NULL ? $capacity+0 : 0;
            $props->setProperty(Ddth_Cache_CacheConfig::PROPERTY_CACHE_CAPACITY, $capacity);

            $timeout = $this->getProperty($key.'timeout');
            if ( $timeout === NULL ) {
                $timeout = $this->getProperty($keyDefault.'timeout');
            }
            $timeout = $timeout !== NULL ? $timeout+0 : 0;
            $props->setProperty(Ddth_Cache_CacheConfig::PROPERTY_CACHE_TIMEOUT, $timeout);
            
            $config = new Ddth_Cache_Memcache_MemCacheConfig($props);
            $this->cacheCacheConfig[$name] = $config;
        }
        return $this->cacheCacheConfig[$name];
    }

    /**
     * Gets compress threshold value
     *
     * @return integer
     */
    public function getCompressThreshold() {
        $key = self::PROPERTY_MC_COMPRESS_THRESHOLD;
        $value = $this->getProperty($key);
        return $value !== NULL ? (int)$value : 0;
    }

    /**
     * @var Array()
     */
    private $serverNames = NULL;

    /**
     * Gets a memcache server settings
     *
     * @return Array() an associative array with the following fields:
     * host - host address of memcache server, port - port number
     */
    public function getServerSettings($serverName) {
        $key = self::PROPERTY_PREFIX_MC_SERVER.$serverName.'.';
        $host = $this->getProperty($key.'host');
        if ( $host === NULL || $host === "" ) {
            return NULL;
        }
        $port = $this->getProperty($key.'port');
        if ( $port === NULL || $port === "" ) {
            $port = self::DEFAULT_SERVER_PORT;
        } else {
            $port = $port+0;
        }
        return Array('host' => $host, 'port' => $port);
    }

    /**
     * Gets name list of defined servers
     *
     * @return Array();
     */
    public function getServerNames() {
        if ( $this->serverNames === NULL ) {
            $this->serverNames = Array();
            $value = $this->getProperty(self::PROPERTY_MC_SERVERS);
            if ( $value !== NULL ) {
                $tokens = preg_split('/[\s,;]+/', $value);
                foreach ( $tokens as $serverName ) {
                    if ( trim($serverName) !== "" ) {
                        $this->serverNames[] = $serverName;
                    }
                }
            }
        }
        return $this->serverNames;
    }
}
?>