<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Cache configurations.
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
 * @id			$Id: ClassCacheConfig.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
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
 * Cache configurations.
 *
 * This class encapsulates cache's configuration settings.
 *
 * @package    	Cache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Cache_CacheConfig {

    const PROPERTY_CACHE_CAPACITY = "cache.capacity";

    const PROPERTY_CACHE_NAME = "cache.name";

    const PROPERTY_CACHE_TIMEOUT = "cache.timeout";
    
    const PROPERTY_CACHE_PREFIX = "cache.prefix";

    /**
     * @var Ddth_Commons_Properties
     */
    private $properties;

    /**
     * Constructs a new Ddth_Cache_CacheManagerConfig object.
     *
     * @param Ddth_Commons_Properties
     */
    public function __construct($props) {
        $this->properties = $props;
    }

    /**
     * Gets cache's capacity setting.
     *
     * @return integer
     */
    public function getCacheCapacity() {
        $value = $this->getProperty(self::PROPERTY_CACHE_CAPACITY);
        return $value !== NULL ? $value+0 : 0;
    }

    /**
     * Gets cache's name.
     *
     * @return string
     */
    public function getCacheName() {
        return $this->getProperty(self::PROPERTY_CACHE_NAME);
    }

    /**
     * Gets cache's timeout setting.
     *
     * @return integer
     */
    public function getCacheTimeout() {
        $value = $this->getProperty(self::PROPERTY_CACHE_TIMEOUT);
        return $value !== NULL ? $value+0 : 0;
    }
    
    /**
     * Gets cache's prefix string.
     * 
     * @return string
     */
    public function getPrefix() {
        return $this->getProperty(self::PROPERTY_CACHE_PREFIX);
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