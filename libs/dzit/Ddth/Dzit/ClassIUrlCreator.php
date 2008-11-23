<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * APIs to generate URLs for application.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Dzit
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassIUrlCreator.php 22 2008-05-19 04:22:44Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * APIs to generate URLs for application.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Dzit_IUrlCreator {
    /**
     * Constructs an URL.
     *
     * @param string
     * @param Array() index array
     * @param Array() associative array
     * @param string
     * @param bool include domain (and schema, e.g. http://) in the constructed URL
     * @param bool
     */
    public function createUrl($action, $pathInfoParams=Array(), $urlParams=Array(),
        $script="", $includeDomain=false, $forceHttps=false);
        
    /**
     * Gets "home" URL.
     *
     * @return string
     */
    public function getHomeUrl($includeDomain=false);
}
?>