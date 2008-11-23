<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Enhanced version of Ddth::Commons::Properties.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		EhProperties
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassProperties.php 147 2008-03-09 06:00:32Z nbthanh@vninformatics.com $
 * @since      	File available since v0.1
 */

/**
 * This class enhanced Ddth::Commons::Properties with extra functionalities.
 *
 * @package    	EhProperties
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @since      	Class available since v0.1
 */
class Ddth_EhProperties_EhProperties extends Ddth_Commons_Properties {
    /**
     * Constructs a new Ddth_Commons_Properties object.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * {@see Ddth_Commons_Properties::getProperties()}
     */
    public function getProperty($key, $defaultValue=NULL) {
        return $this->myGetProperty(Array(), $key, $defaultValue);
    }

    private function myGetProperty($cached, $key, $defaultValue=NULL) {
        if ( in_array($key, $cached) ) {
            //loop detected
            return '';
        }

        $cached[] = $key;
        $value = parent::getProperty($key, $defaultValue);
        
        if ( $value === NULL ) {
            return $value;
        }
        $result = '';
        while ( strlen($value) > 0 ) {
            if ( preg_match('/^([^\{]+)/', $value, $matches) ) {
                $result .= $matches[1];
                $value = substr($value, strlen($matches[1]));
            } elseif ( preg_match('/^\{\%([^}]+)\}/', $value, $matches) ) {
                //an environment-name placeholder
                $name = $matches[1];
                if ( isset($_ENV[$name]) ) {
                    $result .= $_ENV[$name];
                } elseif ( isset($_SERVER[$name]) ) {
                    $result .= $_SERVER[$name];
                }
                $value = substr($value, strlen($matches[0]));
            } elseif ( preg_match('/^\{\$([^}]+)\}/', $value, $matches) ) {
                //an property-name placeholder
                $name = $matches[1];
                $result .= $this->myGetProperty($cached, $name);
                $value = substr($value, strlen($matches[0]));
            } else {
                $result .= substr($value, 0, 1);
                $value = substr($value, 1);
            }
        }
        return $result;
    }
}
?>
