<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * An abstract data model node.
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
 * @id			$Id: ClassAbstractNode.php 159 2008-04-07 19:54:58Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * An abstract data model node.
 *
 * @package    	Template
 * @subpackage  DataModel
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Template_DataModel_AbstractNode implements Ddth_Template_DataModel_INode {
    /**
     * @var string
     */
    private $name = NULL;

    /**
     * @var mixed
     */
    private $value = NULL;

    /**
     * Constructs a new Ddth_Template_DataModel_AbstractNode object.
     *
     * @param string
     * @name string|number
     */
    public function __construct($name, $value=NULL) {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * {@see Ddth_Template_DataModel_INode::getName()}
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets node's name.
     *
     * @param string
     */
    protected function setName($name) {
        $this->name = $name;
    }

    /**
     * {@see Ddth_Template_DataModel_INode::getValue()}
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * {@see Ddth_Template_DataModel_INode::setValue()}
     */
    public function setValue($value) {
        $this->value = $value;
    }
}
?>