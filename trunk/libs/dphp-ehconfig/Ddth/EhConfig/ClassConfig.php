<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * A configuration setting.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		EhConfig
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassIAdodbFactory.php 144 2008-02-29 15:34:04Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * This class represents a configuration setting.
 *
 * @package    	EhConfig
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_EhConfig_Config {

    private $key = NULL;

    private $value = NULL;

    /**
     * Constructs a new Ddth_EhConfig_Config object.
     *
     * @param Ddth_EhConfig_ConfigKey
     * @param string
     */
    public function __construct($key=NULL, $value="") {
        $this->setKey($key);
        $this->setValue($value);
    }

    /**
     * Gets configuration key.
     *
     * @return Ddth_EhConfig_ConfigKey
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Sets configuration key.
     *
     * @param Ddth_EhConfig_ConfigKey
     */
    public function setKey($key) {
        if ( $key === NULL ) {
            throw new Exception('Null argument!');
        }
        if ( !($key instanceof Ddth_EhConfig_ConfigKey) ) {
            throw new Exception('Argument is not an instance of Ddth_EhConfig_ConfigKey!');
        }
        $this->key = $key;
    }
    
    /**
     * Sets configuration value.
     * 
     * @param mixed
     */
    public function setValue($value) {
        $this->value = $value;
    }
    
    /**
     * Gets configuration value.
     * 
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
}
?>
