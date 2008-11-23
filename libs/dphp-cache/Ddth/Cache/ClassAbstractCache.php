<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * An abstract implementation of cache.
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
 * @id			$Id: ClassAbstractCache.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
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
 * An abstract implementation of cache.
 *
 * @package    	Cache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Cache_AbstractCache implements Ddth_Cache_ICache {
    /**
     * @var Ddth_Cache_CacheConfig
     */
    private $config;

    /**
     * @var Ddth_Cache_ICacheManager
     */
    private $manager;

    /**
     * @var string
     */
    private $cacheName = NULL;

    /**
     * @var integer
     */
    private $cacheCapacity = NULL;

    /**
     * @var integer
     */
    private $cacheTimeout = NULL;

    /**
     * Constructs a new Ddth_Cache_AbstractCache object.
     */
    public function __construct() {
    }

    /**
     * {@see Ddth_Cache_ICache::destroy()}
     */
    public function destroy() {
        $this->clear();
    }

    /**
     * {@see Ddth_Cache_ICache::init()}
     */
    public function init($config, $manager) {
        $this->config = $config;
        $this->manager = $manager;
    }

    /**
     * Gets cache configuration object.
     *
     * @return Ddth_Cache_CacheConfig
     */
    protected function getConfig() {
        return $this->config;
    }

    /**
     * {@see Ddth_Cache_ICache::getCapacity()}
     */
    public function getCapacity() {
        if ( $this->cacheCapacity == NULL ) {
            $this->cacheCapacity = $this->config->getCacheCapacity();
        }
        return $this->cacheCapacity;
    }

    /**
     * {@see Ddth_Cache_ICache::getCacheManager()}
     */
    public function getCacheManager() {
        return $this->manager;
    }

    /**
     * {@see Ddth_Cache_ICache::getName()}
     */
    public function getName() {
        if ( $this->cacheName == NULL ) {
            $this->cacheName = $this->config->getCacheName();
        }
        return $this->cacheName;
    }

    /**
     * Gets cache's timeout setting.
     *
     * @return integer
     */
    public function getTimeout() {
        if ( $this->cacheTimeout == NULL ) {
            $this->cacheTimeout = $this->config->getCacheTimeout();
        }
        return $this->cacheTimeout;
    }
}
?>