<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Class name to physical file name translator.  
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 * 
 * Provides a mechanism to translate class name to physical file name
 * on disk, available for use with {@link http://www.php.net/include/ include()},
 * {@link http://www.php.net/include_once/ include_once()},
 * {@link http://www.php.net/require/ require()}, and
 * {@link http://www.php.net/require_once/ require_once()} methods.
 * 
 * @package		Commons
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassIClassNameTranslator.php 116 2008-02-16 16:39:38Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1 
 */

/**
 * Class name to physical file name translator.
 * 
 * This interface provides a mechanism to translate class name to physical file name
 * on disk, available for use with {@link http://www.php.net/include/ include()},
 * {@link http://www.php.net/include_once/ include_once()},
 * {@link http://www.php.net/require/ require()}, and
 * {@link http://www.php.net/require_once/ require_once()} methods.
 *
 * @package    	Commons
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @since      	Class available since v0.1
 */
interface Ddth_Commons_IClassNameTranslator {
    /**
     * Translates a class name to physical file name on disk.
     * 
     * @param string $className 
     * @return string file name on disk available for including.  
     */
    public function translateClassNameToFileName($className);
}
?>