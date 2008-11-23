<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Forward execution flow to a view.
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
 * @id			$Id: ClassViewControlForward.php 16 2008-04-28 14:55:53Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Forward execution flow to to a view.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_ControlForward_ViewControlForward implements Ddth_Dzit_IControlForward {

    /**
     * @var string
     */
    private $action = NULL;

    /**
     * Constructs a new Ddth_Dzit_ControlForward_ViewControlForward object with supplied action name.
     *
     * @param string
     */
    public function __construct($action) {
        $this->action = $action;
    }

    /**
     * Gets action.
     *
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Sets action.
     *
     * @param string
     */
    public function setAction($action) {
        $this->action = $action;
    }
}
?>
