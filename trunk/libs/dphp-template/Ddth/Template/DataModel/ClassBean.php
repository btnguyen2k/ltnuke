<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Bean data model node.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Template
 * @subpackage 	DataModel
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassBean.php 161 2008-04-17 04:48:57Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Bean data model node.
 *
 * @package    	Template
 * @subpackage  DataModel
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Template_DataModel_Bean extends Ddth_Template_DataModel_AbstractNode {
    
    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER = NULL;
    
    /**
     * Constructs a new Ddth_Template_DataModel_Bean object.
     *
     * @param string
     * @name object
     */
    public function __construct($name, $value=NULL) {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
        parent::__construct($name, $value);
    }

    /**
     * {@see Ddth_Template_DataModel_INode::asPhpType()}
     *
     * @return object
     */
    public function asPhpType() {
        return $this->getValue();
    }

    /**
     * {@see Ddth_Template_DataModel_INode::setValue()}
     *
     * @param object
     */
    public function setValue($value) {
        if ( is_object($value) ) {
            parent::setValue($value);
        } else {
            $msg = "[$value] is not an object!";
            $this->LOGGER->error($msg);
            parent::setValue(NULL);
        }
    }
}
?>