<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * PHP template pack.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Template
 * @subpackage  Php
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassPhpTemplate.php 159 2008-04-07 19:54:58Z btnguyen2k@gmail.com $
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
 * PHP template pack.
 *
 * @package    	Template
 * @subpackage  Php
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Template_Php_PhpTemplate extends Ddth_Template_AbstractTemplate {
    /**
     * Constructs a new Ddth_Template_Php_PhpTemplate object.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * {@see Ddth_Template_ITemplate::init()}
     */
    public function getPage($id) {
        $templateFile = $this->getPageTemplateFile($id);
        if ( $templateFile !== NULL ) {
            //$f = new Ddth_Commons_File($templateFile, $this->getLocation());
            //return new Ddth_Template_Php_PhpPage($id, $f->getPathname(), $this);
            return new Ddth_Template_Php_PhpPage($id, $templateFile, $this);
        } else {
            return NULL;
        }
    }
}
?>