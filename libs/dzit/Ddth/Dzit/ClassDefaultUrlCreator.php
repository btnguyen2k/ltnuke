<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Default URL creator.
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
 * @id			$Id: ClassDefaultUrlCreator.php 26 2008-07-09 08:31:07Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Default implementation of IUrlCreator interface.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_DefaultUrlCreator implements Ddth_Dzit_IUrlCreator {
    const GET_PARAM_ACTION = 'act';

    /**
     * {@see Ddth_Dzit_IUrlCreator::createUrl()}
     */
    public function createUrl($action, $pathInfoParams=Array(), $urlParams=Array(), $script="", $includeDomain=false, $forceHttps=false) {
        if ( $script === NULL || trim($script) === "" ) {
            $url = $_SERVER['PHP_SELF'];
        } else {
            $url = trim($script);
        }
        $url .= '?'.self::GET_PARAM_ACTION.'='.$action;
        $ok1 = $pathInfoParams!==NULL && is_array($pathInfoParams) && count($pathInfoParams) > 0;
        $ok2 = $urlParams!==NULL && is_array($urlParams) && count($urlParams) > 0;
        if ( $ok1 || $ok2 ) {
            $i = 1;
            if ( $pathInfoParams !== NULL ) {
                foreach ( $pathInfoParams as $param ) {
                    $url .= "&$i=$param";
                    $i++;
                }
            }
            if ( $urlParams !== NULL ) {
                foreach ( $urlParams as $key=>$value ) {
                    $url .= "&$key=$value";
                }
            }
        }
        if ( $includeDomain || $forceHttps ) {
            if ( $url[0] !== '/' ) {
                $url = "/$url";
            }
            $url = $_SERVER["HTTP_HOST"].$url;
            if ( $forceHttps ) {
                $url = "https://$url";
            } else {
                $url = isset($_SERVER['HTTPS']) ? "https://$url" : "http://$url";
            }
        }
        return $url;
    }

    /**
     * {@see Ddth_Dzit_IUrlCreator::getHomeUrl()}
     */
    public function getHomeUrl($includeDomain=false) {
        $url = pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
        if ( $url[strlen($url)-1] !== '/' ) {
            $url .= '/';
        }
        if ( $includeDomain ) {
            if ( $url[0] !== '/' ) {
                $url = "/$url";
            }
            $url = $_SERVER["HTTP_HOST"].$url;
            $url = isset($_SERVER['HTTPS']) ? "https://$url" : "http://$url";
        }
        return $url;
    }
}
?>