<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * List-value data model node.
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
 * @id			$Id: ClassList.php 161 2008-04-17 04:48:57Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

if ( !function_exists('__autoload') ) {
    /**
     * Automatically loads class source file when used.
     *
     * @param string
     * @ignore
     */
    function __autoload($className) {
        require_once 'Ddth/Commons/ClassDefaultClassNameTranslator.php';
        require_once 'Ddth/Commons/ClassLoader.php';
        $translator = Ddth_Commons_DefaultClassNameTranslator::getInstance();
        Ddth_Commons_Loader::loadClass($className, $translator);
    }
}

/**
 * List-value data model node.
 *
 * @package    	Template
 * @subpackage  DataModel
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Template_DataModel_List extends Ddth_Template_DataModel_AbstractNode {

    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER = NULL;

    /**
     * Constructs a new Ddth_Template_DataModel_List object.
     *
     * @param string
     * @name Array
     */
    public function __construct($name, $value=Array()) {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
        parent::__construct($name, $value);
    }

    /**
     * {@see Ddth_Template_DataModel_INode::asPhpType()}
     *
     * @return Array
     */
    public function asPhpType() {
        $myValue = $this->getValue();
        $result = Array();
        foreach ( $myValue as $value ) {
            $result[] = $value->asPhpType();
        }
        return $result;
    }

    /**
     * {@see Ddth_Template_DataModel_INode::setValue()}
     *
     * @param scalar
     */
    public function setValue($value) {
        $myValue = Array();
        parent::setValue($myValue);
        if ( is_array($value) ) {
            foreach ( array_values($value) as $v ) {
                $this->addChild($v);
            }
        } else {
            $this->addChild($value);
        }
    }

    /**
     * Counts number of child nodes
     *
     * @return integer
     */
    public function countChildren() {
        return count($this->getValue());
    }

    /**
     * Gets a child node by index.
     *
     * @return Ddth_Template_DataModel_INode
     */
    public function getChild($index) {
        $myValue = $this->getValue();
        if ( $index >= 0 && $index < count($myValue) ) {
            return $myValue[$index];
        }
        return NULL;
    }

    /**
     * Adds a child node.
     *
     * @param mixed|Ddth_Template_DataModel_INode
     */
    public function addChild($child) {
        $myValue = $this->getValue();
        if ( $child instanceof Ddth_Template_DataModel_INode ) {
            $myValue[] = $child;
            parent::setValue($myValue);
        } elseif ( $child !== NULL ) {
            if ( is_object($child) ) {
                $this->addChild(new Ddth_Template_DataModel_Bean('', $child));
            } elseif ( is_array($child) ) {
                $this->addChild(new Ddth_Template_DataModel_Map('', $child));
            } else {
                $this->addChild(new Ddth_Template_DataModel_Scalar('', $child));
            }
        } else {
            $msg = "Null argument!";
            $this->LOGGER->error($msg);
        }
    }
}
?>