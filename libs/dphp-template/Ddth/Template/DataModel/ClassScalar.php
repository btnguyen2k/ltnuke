<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Scalar-value data model node.
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
 * @id			$Id: ClassScalar.php 160 2008-04-17 02:02:42Z btnguyen2k@gmail.com $
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
 * Scalar-value data model node.
 *
 * @package    	Template
 * @subpackage  DataModel
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Template_DataModel_Scalar extends Ddth_Template_DataModel_AbstractNode {

    /**
     * Constructs a new Ddth_Template_DataModel_Scalar object.
     *
     * @param string
     * @name scalar
     */
    public function __construct($name, $value=NULL) {
        parent::__construct($name, $value);
    }

    /**
     * {@see Ddth_Template_DataModel_INode::asPhpType()}
     *
     * @return scalar
     */
    public function asPhpType() {
        return $this->getValue();
    }

    /**
     * {@see Ddth_Template_DataModel_INode::setValue()}
     *
     * @param scalar
     */
    public function setValue($value) {
        if ( is_scalar($value) ) {
            parent::setValue($value);
        } else {
            parent::setValue("$value");
        }
    }
}
?>