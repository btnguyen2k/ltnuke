<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Factory interface to create and dispose ADOdb connections.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Adodb
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassIAdodbFactory.php 144 2008-02-29 15:34:04Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Factory interface to create and dispose ADOdb connections.
 *
 * This factory interface provides APIs create and dispose
 * {@link http://adodb.sourceforge.net/ ADOdb} connections.
 *
 * @package    	Adodb
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Adodb_IAdodbFactory {
    const DEFAULT_CONFIG_FILE = "dphp-adodb.properties";

    /**
     * Gets an ADOdb connection.
     *
     * @param bool indicates that if a transaction is automatically started
     * @return ADOConnection an instance of ADOConnection, NULL is returned if
     * the connection can not be created
     */
    public function getConnection($startTransaction=false);

    /**
     * Closes an ADOConnection
     *
     * @param ADOConnection
     * @param bool
     */
    public function closeConnection($conn, $hasError=false);
}
?>