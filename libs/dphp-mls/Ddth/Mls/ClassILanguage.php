<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Represents a language pack.
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
 * @id			$Id: ClassILanguage.php 141 2008-02-29 11:52:45Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Represents a language pack.
 *
 * This interface represents a single language pack.
 *
 * @package    	Mls
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Mls_ILanguage {
    /**
     * Gets a text message from this language.
     * 
     * Note: the official type of the argument $replacements is an array.
     * Implementations of this interface, however, can take advantage of PHP's
     * variable arguments support to take in any number of single replacement.  
     *
     * @param string key of the text message to get
     * @param Array() replacements for place-holders within the text message
     * @return string
     */
    public function getMessage($key, $replacements=NULL);

    /**
     * Gets description of the language pack.
     *
     * @return string
     */
    public function getDescription();
    
    /**
     * Gets display name of the language pack.
     *
     * @return string
     */
    public function getDisplayName();
    
    /**
     * Gets name of the language pack.
     *
     * @return string
     */
    public function getName();

    /**
     * Initializes the language pack.
     *
     * @param Dddth_Commons_Properties
     * @throws Ddth_Mls_MlsException
     */
    public function init($settings);
}
?>