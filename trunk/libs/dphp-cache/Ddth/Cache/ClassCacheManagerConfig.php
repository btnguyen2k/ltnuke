<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Cache manager configurations.
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
 * @id			$Id: ClassCacheManagerConfig.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
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
 * Cache manager configurations.
 *
 * This class encapsulates cache manager's configuration settings.
 *
 * Configuration file format: configurations are stored in
 * .properties file; supported properties are:
 * <code>
 * # Type of cache, currently only memcache is supported
 * # Default value is memcache
 * cache.type=memcache
 *
 * # Cache's prefix string. It is recommended to set this property with something
 * # specific to your site/application/setup to avoid clashing with other cache managers
 * # This setting is optional
 * cache.prefix=
 *
 * # All memcache.xxx properties are memcache-specific settings
 * </code>
 *
 * @package    	Cache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Cache_CacheManagerConfig {

    const PROPERTY_CACHE_TYPE = "cache.type";

    const PROPERTY_CACHE_PREFIX = "cache.prefix";

    const DEFAULT_CACHE_TYPE = "memcache";

    /**
     * Loads configurations from file and encapsulates them inside a
     * Ddth_Cache_CacheManagerConfig instance.
     *
     * See: {@link Ddth_Cache_CacheManagerConfig configuration file format}.
     *
     * @param string name of the configuration file (located in
     * {@link http://www.php.net/manual/en/ini.core.php#ini.include-path include-path})
     * @return Ddth_Cache_CacheManagerConfig
     * @throws {@link Ddth_Cache_CacheException CacheException}
     */
    public static function loadConfig($configFile) {
        $fileContent = Ddth_Commons_Loader::loadFileContent($configFile);
        if ( $fileContent === NULL ) {
            $msg = "Can not read file [$configFile]!";
            throw new Ddth_Cache_CacheException($msg);
        }
        $prop = new Ddth_Commons_Properties();
        try {
            $prop->import($fileContent);
        } catch ( Exception $e ) {
            throw new Ddth_Cache_CacheException($e->getMessage(), $e->getCode());
        }
        return new Ddth_Cache_CacheManagerConfig($prop);
    }

    /**
     * @var Ddth_Commons_Properties
     */
    private $properties;

    /**
     * Constructs a new Ddth_Cache_CacheManagerConfig object.
     *
     * @param Ddth_Commons_Properties
     */
    public function __construct($prop) {
        $this->properties = $prop;
    }
    
    /**
     * Gets a cache's configurations.
     * 
     * Sub-classes should override this method to return the correct cache
     * configuration instance.
     * 
     * @param string
     * @return Ddth_Cache_CacheConfig
     */
    public function getCacheConfig($name) {
        $props = new Ddth_Commons_Properties();
        $props->setProperty(Ddth_Cache_CacheConfig::PROPERTY_CACHE_NAME, $name);
        $prefix = $this->getCachePrefix();
        $props->setProperty(Ddth_Cache_CacheConfig::PROPERTY_CACHE_PREFIX, $prefix);
        $config = new Ddth_Cache_CacheConfig($props);
        return $config;
    }

    /**
     * Gets cache's prefix string.
     *
     * @return string
     */
    public function getCachePrefix() {
        $key = self::PROPERTY_CACHE_PREFIX;
        return $this->getProperty($key);
    }

    /**
     * Gets type of cache.
     *
     * @return string
     */
    public function getCacheType() {
        $key = self::PROPERTY_CACHE_TYPE;
        return $this->getProperty($key, self::DEFAULT_CACHE_TYPE);
    }

    /**
     * Gets the internal properties.
     *
     * @return Ddth_Commons_Properties
     */
    protected function getProperties() {
        return $this->properties;
    }

    /**
     * Gets a property setting.
     *
     * @param string
     * @param string
     * @return string
     */
    protected function getProperty($key, $defaultValue=NULL) {
        return $this->properties->getProperty($key, $defaultValue=NULL);
    }
}
?>