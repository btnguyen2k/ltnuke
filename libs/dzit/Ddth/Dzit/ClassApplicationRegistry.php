<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * OO way to retrieve the currently running application instance.
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
 * @id			$Id: ClassApplicationRegistry.php 11 2008-03-06 09:14:18Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * OO way to retrieve the currently running application instance.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_ApplicationRegistry {
    public static $CURRENT_APP = NULL;

    /**
     * Registers the currently running application.
     *
     * @param Ddth_Dzit_IApplication
     */
    public static function registerApplication($app) {
        self::$CURRENT_APP = $app;
    }

    /**
     * Gets the currently running application.
     *
     * @return Ddth_Dzit_IApplication
     */
    public static function getCurrentApplication() {
        return self::$CURRENT_APP;
    }
}
?>