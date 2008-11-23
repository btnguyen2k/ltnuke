<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Factory to create cache manager instances.
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
 * @id			$Id: ClassCacheFactory.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
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
 * Factory to create cache manager instances.
 *
 * @package    	Cache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Cache_CacheFactory {

    const DEFAULT_CONFIG_FILE = "dphp-cache.properties";

    const CACHE_TYPE_MEMCACHE = "memcache";

    const CACHE_TYPE_MEMCACHED = "memcached";

    private static $cacheInstances = Array();

    /**
     * Gets a cache manager instance.
     * 
     * See: {@link Ddth_Adodb_AdodbConfig configuration file format}.
     *
     * @param string name of the configuration file (located in
     * {@link http://www.php.net/manual/en/ini.core.php#ini.include-path include-path})
     * @return Ddth_Cache_ICacheManager
     * @throws {@link Ddth_Cache_CacheException CacheException} 
     */
    public static function getCacheManager($configFile=NULL) {
        if ( $configFile === NULL ) {
            return self::getCacheManager(self::DEFAULT_CONFIG_FILE);
        }
        if ( !isset(self::$cacheInstances[$configFile]) ) {            
            $config = Ddth_Cache_CacheManagerConfig::loadConfig($configFile);
            $type = $config->getCacheType();            
            try {
                $instance = NULL;
                if ( $type !== NULL ) {
                    $type = strtolower($type);                    
                    if ( $type === self::CACHE_TYPE_MEMCACHE 
                            || $type === self::CACHE_TYPE_MEMCACHED ) {
                        $instance = new Ddth_Cache_Memcache_MemCacheManager();                        
                        $instance->init($config);                        
                    }                   
                }                
                self::$cacheInstances[$configFile] = $instance;
            } catch ( Exception $e ) {
                $msg = $e->getMessage();
                throw new Ddth_Cache_CacheException($e);
            }
        }
        return self::$cacheInstances[$configFile];
    }
}
?>