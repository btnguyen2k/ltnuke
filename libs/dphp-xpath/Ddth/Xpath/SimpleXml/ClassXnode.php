<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Generic node.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Xpath
 * @subpackage	SimpleXml
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassXnode.php 147 2008-03-09 06:00:32Z nbthanh@vninformatics.com $
 * @since      	File available since v0.1
 */

if ( !function_exists('__autoload') ) {
    /**
     * Automatically loads class source file when used.
     *
     * @param string
     */
    function __autoload($className) {
        require_once 'Ddth/Commons/ClassDefaultClassNameTranslator.php';
        require_once 'Ddth/Commons/ClassLoader.php';
        $translator = Ddth_Commons_DefaultClassNameTranslator::getInstance();
        Ddth_Commons_Loader::loadClass($className, $translator);
    }
}

/**
 * Generic Xnode.
 *
 * This abstract implementation of {@link Ddth_Xpath_IXnode IXnode} interface
 * is a "generic" XML node.
 *
 * @package    	Xpath
 * @subpackage	SimpleXml
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Xpath_SimpleXml_Xnode implements Ddth_Xpath_IXnode {

    private $name;

    private $simpleXml;

    /**
     * Creates a new node from a SimpleXMLElement object.
     * 
     * @param $simpleXml
     * @return Ddth_Xpath_SimpleXml_Xnode
     * @throws {@link Ddth_Xpath_XpathException XpathException}
     */
    public static function createNode($simpleXml) {        
        if ( $simpleXml===NULL || !($simpleXml instanceof SimpleXMLElement ) ) {
            $msg = "[$simpleXml] is not an instance of SimpleXMLElement!";
            throw new Ddth_Xpath_XpathException($msg);
        }                
        if ( $simpleXml->attributes() === NULL ) {
            return new Ddth_Xpath_SimpleXml_AttributeXnode($simpleXml);
        } else {
            return new Ddth_Xpath_SimpleXml_ElementXnode($simpleXml);            
        }        
    }

    /**
     * Constructs a new Ddth_Xpath_SimpleXml_Xnode object.
     *
     * @param SimpleXML the SimpleXML object holding data for this node
     * @throws {@link Ddth_Xpath_XpathException XpathException}
     */
    protected function __construct($simpleXml) {
        if ( $simpleXml===NULL || !($simpleXml instanceof SimpleXMLElement ) ) {
            $msg = "[$simpleXml] is not an instance of SimpleXMLElement!";
            throw new Ddth_Xpath_XpathException($msg);
        }

        $this->simpleXml = $simpleXml;
        $this->name = $simpleXml->getName();
    }

    /**
     * Gets the associated SimpleXMLElement object.
     * 
     * @return SimpleXMLElement
     */
    protected function getSimpleXmlObj() {
        return $this->simpleXml;
    }

    public function getAllAttributes() {
        return NULL;
    }

    public function getAttribute($name) {
        return NULL;
    }

    public function getChildren() {
        return NULL;
    }

    public function getName() {
        return $this->name;
    }
    
    public function getValue() {
        return NULL;
    }
    
    public function toXml() {
        return "";
    }

    public function xpath($path) {
        return NULL;        
    }
}
?>