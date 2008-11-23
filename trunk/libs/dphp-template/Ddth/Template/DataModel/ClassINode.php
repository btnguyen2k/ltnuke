<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Represents a data model node.
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
 * @id			$Id: ClassINode.php 159 2008-04-07 19:54:58Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Represents a data model node.
 *
 * This interface represents a single node in the template's data model.
 *
 * @package    	Template
 * @subpackage  DataModel
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Template_DataModel_INode {
    /**
	 * Converts the data model node to PHP data type.
	 * 
	 * @return mixed
	 */
	public function asPhpType();

	/**
	 * Retrieves the node's name.
	 * 
	 * @return string
	 */
	public function getName();
	
	/**
	 * Gets the node's value.
	 * 
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Sets the node's value.
	 * 
	 * @param mixed
	 */
	public function setValue($value);
}
?>