<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Capturing a HTTP request.
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
 * @id			$Id: ClassIHttpRequest.php 15 2008-04-18 10:30:42Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * An interface to access HTTP request's parameters.
 * 
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1 
 */
interface Ddth_Dzit_IHttpRequest {

    /**
     * Gets action associated with the request.
     *
     * @return string
     */
    public function getAction();
    
    /**
     * Gets a parameter sent via form (POST request)
     *
     * @param string
     * @return string
     */
    public function getFormParam($name);
    
    /**
     * Gets an upload file entry.
     *
     * @param string
     * @return Ddth_Dzit_UploadFile
     */
    public function getUploadFile($name);
    
    /**
     * Gets a parameter sent via URL (GET request)
     *
     * @param string
     * @return string
     */
    public function getUrlParam($name);
    
    /**
     * Checks if there is any upload file.
     * 
     * @return bool
     */
    public function hasUploadFile();
}
?>