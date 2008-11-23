<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * "Key" to to differentiate a configuration from another.
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
 * "Key" to to differentiate a configuration from another.
 *
 * An AppConfigKey is a composition of two elements:
 * <ul>
 * <li><code>domain</code> - used as a "namespace"
 * <li><code>key</code> - used as a "key" to differentiate a configuration
 * from another within a same domain
 * </ul>
 *
 * @package    	EhConfig
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_EhConfig_ConfigKey {

    private $domain = NULL;

    private $name = NULL;

    /**
     * Constructs a new Ddth_EhConfig_ConfigKey object.
     *
     * @param string
     * @param string
     */
    public function __construct($domain="", $name="") {
        $this->setDomain($domain);
        $this->setName($name);
    }

    /**
     * Gets domain value.
     *
     * @return string
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * Sets domain value.
     *
     * @param string
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * Gets name value.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets name value.
     *
     * @param string
     */
    public function setName($name) {
        $this->name = $name;
    }
}
?>
