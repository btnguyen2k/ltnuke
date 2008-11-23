<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Factory interface to create language pack objects.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Mls
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassILanguageFactory.php 143 2008-02-29 15:13:14Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Factory interface to create language pack objects.
 *
 * @package    	Mls
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Mls_ILanguageFactory {            
    
    /**
     * Gets a language pack.
     * 
     * @param string
     * @return Ddth_Mls_ILanguage
     * @throws Ddth_Mls_MlsException
     */
    public function getLanguage($name);
    
    /**
     * Gets list of names of available languages.
     * 
     * @return Array()
     */
    public function getLanguageNames();
    
    /**
     * Initializes the factory.
     * 
     * @param Dddth_Commons_Properties
     * @throws Ddth_Mls_MlsException
     */
    public function init($settings);
}
?>