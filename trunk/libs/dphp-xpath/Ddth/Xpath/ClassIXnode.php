<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * An XML node.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Xpath
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassIXnode.php 127 2008-02-25 05:21:13Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * An XML node.
 *
 * This interface represents an XML node.
 *
 * @package    	Xpath
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Interface available since v0.1
 */
interface Ddth_Xpath_IXnode {
    
    /**
     * Gets all attributes of the node.
     * 
     * @return Array() all attributes of the node as an array of {name=>value},
     * an empty array is returned if node has no attribute, NULL is returned if
     * node can not have attribute (e.g. attribute node).
     */
    public function getAllAttributes();
    
    /**
     * Gets a node's attribute.
     * 
     * @param string name of the attribute
     * @return string attribute value, or NULL if attribute does not exist
     */
    public function getAttribute($name);
    
    /**
     * Gets all children nodes.
     * 
     * @return Array() an array of {@link Ddth_Xpath_IXnode IXnode}, an empty
     * array is returned if node has no children, NULL is returned if node can
     * not have children (e.g. attribute node)  
     */
    public function getChildren();
    
    /**
     * Gets node's name
     *
     * @return string the node's name
     */
    public function getName();
    
    /**
     * Gets node's text value.
     * 
     * @return string if node is an attribute node, the attribute's value is
     * returned; if node is an element node that contains *only one* text child
     * the child's text is returned; undetermined value is returned in any other cases 
     */
    public function getValue();
    
    /**
     * Returns the node as an XML document.
     * 
     * @return string
     */
    public function toXml();

    /**
     * Runs an XPath query and returns the result.
     * 
     * @param string an XPath path
     * @return Array() an array of {@link Ddth_Xpath_IXnode IXnode}s.
     * @throws {@link Ddth_Xpath_XpathException XpathException}
     */
    public function xpath($path);
}
?>