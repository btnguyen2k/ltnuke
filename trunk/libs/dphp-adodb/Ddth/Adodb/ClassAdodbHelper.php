<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * ADOdb connection factory.
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
 * @id			$Id: ClassAdodbFactory.php 148 2008-03-12 05:38:09Z nbthanh@vninformatics.com $
 * @since      	File available since v0.1.2
 */

/**
 * ADOdb helper class.
 *
 * @package    	Adodb
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1.2
 * @since      	Class available since v0.1.2
 */
class Ddth_Adodb_AdodbHelper {
    /**
     * A shortcut to to build string with n question marks separated by commas (e.g. "?,?,?,?").
     * 
     * @param int
     * @return string
     */
    public static function buildArrayParams($count=1) {
        $count += 0;
        if ( $count < 1 ) return '';
        $result = '?';
        for ( $i = 1; $i < $count; $i++ ) {
            $result .= ',?';
        }
        return $result;
    }
}
?>