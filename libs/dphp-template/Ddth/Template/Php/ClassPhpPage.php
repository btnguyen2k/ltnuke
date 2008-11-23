<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Php template page.
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
 * @id			$Id: ClassPhpPage.php 160 2008-04-17 02:02:42Z btnguyen2k@gmail.com $
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
 * Php template page.
 *
 * @package    	Template
 * @subpackage  Php
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Template_Php_PhpPage extends Ddth_Template_AbstractPage {
    /**
     * Constructs a new Ddth_Template_Php_PhpPage object.
     *
     * @param string
     * @param string
     * @param Ddth_Template_ITempalte
     */
    public function __construct($id, $templateFile, $template) {
        parent::__construct($id, $templateFile, $template);
    }

    /**
     * {@see Ddth_Template_IPage::render()}
     */
    public function render($dataModel=NULL) {
        if ( $dataModel !== NULL ) {
            $this->setDataModel($dataModel);
        }
        $key = Ddth_Template_Php_PhpTemplate::PROPERTY_BASE_DIRECTORY;
        $baseDir = new Ddth_Commons_File($this->getTemplateProperty($key));
        $key = Ddth_Template_Php_PhpTemplate::PROPERTY_LOCATION;
        $location = new Ddth_Commons_File($this->getTemplateProperty($key), $baseDir);
        $templateFile = new Ddth_Commons_File($this->getTemplateFile(), $location);

        global $DATAMODEL;
        $DATAMODEL = $this->getDataModel();
        $DATAMODEL = $DATAMODEL->asPhpType();
        include $templateFile->getPathname();
    }
}
?>