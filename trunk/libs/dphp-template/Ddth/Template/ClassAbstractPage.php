<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Abstract template page.
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
 * @id			$Id: ClassAbstractPage.php 160 2008-04-17 02:02:42Z btnguyen2k@gmail.com $
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
 * Abstract template page.
 *
 * @package    	Template
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Template_AbstractPage implements Ddth_Template_IPage {
    /**
     * @var string
     */
    private $id = NULL;

    /**
     * Name of the associated physical template file.
     *
     * @var string
     */
    private $templateFile = NULL;

    /**
     * @var Ddth_Template_DataModel_INode
     */
    private $dataModel = NULL;

    /**
     * @var Ddth_Template_ITemplate
     */
    private $template = NULL;

    /**
     * Constructs a new Ddth_Template_AbstractPage object.
     *
     * @param string
     * @param string
     * @param Ddth_Template_ITemplate
     */
    public function __construct($id, $templateFile, $template) {
        $this->setId($id);
        $this->setTemplateFile($templateFile);
        $this->setTemplate($template);
    }

    /**
     * Gets page's id.
     *
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets page's id.
     *
     * @param string
     */
    protected function setId($id) {
        $this->id = $id;
    }

    /**
     * Gets associated template instance.
     *
     * @return Ddth_Template_ITemplate
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * Sets associated template instance.
     *
     * @param Ddth_Template_ITemplate
     */
    protected function setTemplate($template) {
        $this->template = $template;
    }

    /**
     * {@see Ddth_Template_IPage::getTemplateFile()}
     */
    public function getTemplateFile() {
        return $this->templateFile;
    }

    /**
     * Sets page's associated template file.
     *
     * @param string
     */
    protected function setTemplateFile($templateFile) {
        $this->templateFile = $templateFile;
    }

    /**
     * {@see Ddth_Template_IPage::setDataModel()}
     */
    public function setDataModel($root) {
        $this->dataModel = $root;
    }

    /**
     * Gets page's data model.
     *
     * @return Ddth_Template_DataModel_INode
     */
    protected function getDataModel() {
        return $this->dataModel;
    }

    /**
     * Gets a template's setting property.
     *
     * @param string
     * @return string
     */
    protected function getTemplateProperty($key) {
        return $this->template->getSetting($key);
    }
}
?>