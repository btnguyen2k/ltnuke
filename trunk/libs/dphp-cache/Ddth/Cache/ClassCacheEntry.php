<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Represents an entry in the cache.
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
 * @id			$Id: ClassCacheEntry.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
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
 * Represents an entry in the cache.
 *
 * @package    	Cache
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Cache_CacheEntry {
    /**
     * @var mixed
     */
    private $value;
    
    /**
     * @var integer
     */
    private $timeout;
    
    /**
     * @var integer
     */
    private $lastAccessTimestamp;
    
    /**
     * Constructs a new Ddth_Cache_CacheEntry object
     * 
     * @param mixed
     * @param integer
     */
    public function __construct($value, $timeout=0) {
        $this->value = $value;
        $this->timeout = $timeout+0;
        $this->lastAccessTimestamp = time();
    }
    
    /**
     * Gets entry's timeout value.
     * 
     * @return integer
     */
    public function getTimeout() {
        return $this->timeout;
    }
    
    /**
     * Gets entry's value.
     * 
     * @return mixed
     */
    public function getValue() {
        $this->lastAccessTimestamp = time();
        return $this->value;
    }
    
    /**
     * Gets entry's last access timestamp
     * 
     * @return integer UNIX timestamp
     */
    public function getLastAccessTimestamp() {
        return $this->lastAccessTimestamp;
    }
    
    /**
     * Checks if this entry is expired.
     * 
     * @return bool
     */
    public function isExpired() {
        $timeout = $this->timeout;
        return $timeout > 0 && $this->getLastAccessTimestamp()+$timeout < time();
    }
}
?>