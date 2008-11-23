<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Thrown to indicate that an error has occurred.
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
 * @id			$Id: ClassDzitException.php 10 2008-03-01 07:23:02Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/** */
require_once 'Ddth/Commons/Exceptions/ClassAbstractException.php';

/**
 * Thrown to indicate that an error has occurred.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_DzitException
extends Ddth_Commons_Exceptions_AbstractException {

    /**
     * Constructs a new Ddth_Dzit_DzitException object.
     *
     * @param string exception message
     * @param int user defined exception code
     */
    public function __construct($message = NULL, $code = 0) {
        parent::__construct($message, $code);
    }
}
?>