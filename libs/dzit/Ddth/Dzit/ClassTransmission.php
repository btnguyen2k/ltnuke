<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Represents a transmission item.
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
 * @id			$Id: ClassUploadFile.php 15 2008-04-18 10:30:42Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * This class represents a transmission item which carries a pack of information
 * between urls and is identified by an session-scope unique id.
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_Transmission {

    const DEFAULT_TIMEOUT = 2;

    private $id = NULL;
    private $message = NULL;
    private $url = NULL;
    private $timeout = self::DEFAULT_TIMEOUT;

    public function __construct($id, $message, $url=NULL, $timeout=2) {
        $this->setId($id);
        $this->setMessage($message);
        $this->setUrl($url);
        $this->getRedirectTimeout($timeout);
    }

    /**
     * Gets transmission's id.
     *
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets transmission's id.
     *
     * @param string $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Gets transmission message.
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Sets transmission message.
     *
     * @param string $msg
     */
    public function setMessage($msg) {
        $this->message = $msg;
    }

    /**
     * Gets transmission url.
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Sets transmission url.
     *
     * @param string $url
     */
    public function setUrl($url=NULL) {
        $this->url = $url;
    }

    /**
     * Gets transmission auto-redirecting timeout.
     *
     * @return int auto-redirecting timeout in seconds
     */
    public function getRedirectTimeout() {
        return $this->timeout;
    }

    /**
     * Sets transmission auto-redirecting timeout.
     *
     * @param $timeout auto-redirecting timeout in seconds
     */
    public function setRedirectTimeout($timeout=2) {
        $this->timeout = $timeout+0;
    }

    /**
     * Creates a new transmission object.
     *
     * @param string $message
     * @param string $url
     * @param int $timeout
     * @return Ddth_Dzit_Transmission
     */
    public static function createTransmission($message, $url=NULL, $timeout=2) {
        $id = md5(time().rand(0, time()));
        return new Ddth_Dzit_Transmission($id, $message, $url, $timeout);
    }
}
?>