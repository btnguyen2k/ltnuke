<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Default capturing of HTTP request.
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
 * @id			$Id: ClassDefaultHttpRequest.php 15 2008-04-18 10:30:42Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Default implementation of IHttpRequest interface.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_DefaultHttpRequest implements Ddth_Dzit_IHttpRequest {
    /**
     * Constructs a new Ddth_Dzit_DefaultHttpRequest object.
     */
    public function __construct() {
        //empty
    }

    /**
     * {@see Ddth_Dzit_IHttpRequst::getAction()}
     *
     */
    public function getAction() {
        return $this->getUrlParam(Ddth_Dzit_DefaultUrlCreator::GET_PARAM_ACTION);
    }

    /**
     * {@see Ddth_Dzit_IHttpRequest::getFormParam()}
     */
    public function getFormParam($name) {
        return isset($_POST[$name]) ? $_POST[$name] : NULL;
    }

    /**
     * {@see Ddth_Dzit_IHttpRequest::getUploadFile()}
     */
    public function getUploadFile($name) {
        //TODO
        return NULL;
    }

    /**
     * {@see Ddth_Dzit_IHttpRequest::getUrlParam()}
     */
    public function getUrlParam($name) {
        return isset($_GET[$name]) ? $_GET[$name] : NULL;
    }

    /**
     * {@see Ddth_Dzit_IHttpRequest::hasUploadFile()}
     */
    public function hasUploadFile() {
        return isset($_FILES) && count($_FILES) > 0 && is_array($_FILES);
    }
}
?>