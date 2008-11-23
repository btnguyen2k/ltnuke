<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Element node.
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
 * @id			$Id: ClassElementXnode.php 127 2008-02-25 05:21:13Z btnguyen2k@gmail.com $
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
 * Element node.
 *
 * This class represent an XML element node.
 *
 * @package    	Xpath
 * @subpackage	SimpleXml
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Xpath_SimpleXml_ElementXnode extends Ddth_Xpath_SimpleXml_Xnode {

    private $attributes = Array();
    
    private $value = NULL;
    
    /**
     * Constructs a new Ddth_Xpath_SimpleXml_ElementXnode object.
     *
     * @param SimpleXML the SimpleXML object holding data for this node
     * @throws {@link Ddth_Xpath_XpathException XpathException}
     */
    protected function __construct($simpleXml) {
        parent::__construct($simpleXml);
        foreach ( $simpleXml->attributes() as $name => $value ) {
            $value = sprintf("%s", $value);
            $this->attributes[$name] = trim($value);        
        }
        $this->value = trim(sprintf("%s", $simpleXml));
    }
    
    public function getAllAttributes() {
        return $this->attributes;
    }
    
    public function getAttribute($name) {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : NULL;  
    }
    
    public function getChildren() {
        return $this->xpath("/*");
    }
    
    public function getValue() {
        return $this->value;
    }

    public function toXml() {
        return $this->getSimpleXmlObj()->asXML();
    }
    
    public function xpath($path) {        
        $simpleXmlNodes = $this->getSimpleXmlObj()->xpath($path);
        if ( $simpleXmlNodes === false ) {           
            return NULL;
        }        
        $result = Array();        
        foreach ( $simpleXmlNodes as $node ) {
            $result[] = Ddth_Xpath_SimpleXml_Xnode::createNode($node);
        }                
        return $result;
    }
}
?>