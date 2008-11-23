<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Represents a template page.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Template
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassIPage.php 160 2008-04-17 02:02:42Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Represents a template page.
 *
 * This interface represents a single template page.
 *
 * @package    	Template
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Template_IPage {
    /**
     * Gets the template file associated with this page.
     *
     * @return string
     */
    public function getTemplateFile();

    /**
     * Sets the data model for this page.
     *
     * @param Ddth_Template_DataModel_INode
     */
    public function setDataModel($root);

    /**
     * Renders the page.
     *
     * @param Ddth_Template_DataModel_INode
     * @throws Ddth_Template_TemplateException
     */
    public function render($dataModel=NULL);
}
?>