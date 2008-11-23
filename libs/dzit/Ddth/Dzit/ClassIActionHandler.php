<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Action handler declaration.
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
 * @id			$Id: ClassIActionHandler.php 18 2008-05-01 13:09:54Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Action handler declaration.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Dzit_IActionHandler {
    /**
     * Handles the action and returns a IControlForward object.
     *
     * @param string
     * @return IControlForward
     * @throws Ddth_Dzit_DzitException
     */
    public function execute($action);

    /**
     * Gets the currently executing action.
     *
     * @return string
     */
    public function getAction();
}
?>