<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * An abstract implementation of IActionHandler.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Dzit
 * @subpackage  ActionHandler
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassAbstractActionHandler.php 24 2008-06-05 07:03:43Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Abstract implementation of IActionHandler.
 *
 * @package    	Dzit
 * @subpackage  ActionHandler
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Dzit_ActionHandler_AbstractActionHandler implements Ddth_Dzit_IActionHandler {
    /**
     * @var Array()
     */
    private $dataModel = Array();

    /**
     * {@see Ddth_Dzit_IActionHandler::getAction()}
     */
    public function getAction() {
        return $this->getAppAttribute(Ddth_Dzit_DzitConstants::APP_ATTR_CURRENT_ACTION);
    }

    /**
     * Sets the currently executing action.
     *
     * @param string
     */
    protected function setAction($action) {
        $this->setAppAttribute(Ddth_Dzit_DzitConstants::APP_ATTR_CURRENT_ACTION, $action);
    }

    /**
     * {@see Ddth_Dzit_IActionHandler::execute()}
     */
    public function execute($action) {
        $this->setAction($action);
        return $this->performAction();
    }

    /**
     * Convenience method for sub-classes to override.
     *
     * @return Ddth_Dzit_IControlForward
     * @throws Ddth_Dzit_DzitException
     */
    abstract protected function performAction();

    /**
     * Gets the currently running application.
     *
     * @return Ddth_Dzit_IApplication
     */
    protected function getApplication() {
        return Ddth_Dzit_ApplicationRegistry::getCurrentApplication();
    }

    /**
     * Gets an application-level attribute.
     *
     * @param string
     * @return mixed
     */
    protected function getAppAttribute($name) {
        return $this->getApplication()->getAttribute($name);
    }

    /**
     * Sets an application-level attribute.
     *
     * @param string
     * @param mixed
     */
    protected function setAppAttribute($name, $value) {
        $this->getApplication()->setAttribute($name, $value);
    }

    /**
     * Gets language pack.
     *
     * @return Ddth_Mls_ILanguage
     */
    protected function getLanguage() {
        return $this->getApplication()->getLanguage();
    }

    /**
     * Gets a root data model by name.
     *
     * @param Ddth_Template_DataModel_INode
     */
    protected function getRootDataModel($name) {
        $dataModels = $this->getRootDataModels();
        return isset($dataModels[$name]) ? $dataModels[$name] : NULL;
    }

    /**
     * Gets collection of root data models
     *
     * @return Array()
     */
    protected function getRootDataModels() {
        $dataModels = $this->getAppAttribute(Ddth_Dzit_DzitConstants::APP_ATTR_ROOT_DATA_MODELS);
        if ( !is_array($dataModels) ) {
            $dataModels = Array();
            $this->setAppAttribute(Ddth_Dzit_DzitConstants::APP_ATTR_ROOT_DATA_MODELS, $dataModels);
        }
        return $dataModels;
    }

    /**
     * Sets root data models
     *
     * @param Array()
     */
    protected function setRootDataModels($rootDataModels) {
        if ( !is_array($rootDataModels) ) {
            $rootDataModels = Array();
        }
        $this->setAppAttribute(Ddth_Dzit_DzitConstants::APP_ATTR_ROOT_DATA_MODELS, $rootDataModels);
    }

    /**
     * Populates a node to the root data models.
     *
     * @param string
     * @param Ddth_Template_DataModel_INode
     */
    protected function populateRootDataModel($name, $node) {
        if ( $name!==NULL && $node instanceof Ddth_Template_DataModel_INode ) {
            $root = $this->getRootDataModels();
            $root[$name] = $node;
            $this->setRootDataModels($root);
        }
    }

    /**
     * Populates page's data models.
     *
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateDataModels() {
        $this->populateModelLanguage();
        $this->populateModelPage();
    }

    /**
     * Populates the 'page' data model. 'page' is the root data model representing the
     * output HTML page.
     *
     * Structure of the 'page' data model is as the following:
     *
     * <pre>
     * page (the root, type: Map)
     * +-- header (stuffs between &lt;head&gt; and &lt;/head&gt;, type: Map)
     * |   +-- title (usage: &lt;title&gt;{page.header.title}&lt;/title&gt;, type String)
     * |   +-- charset (usage: &lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;text/html;charset={page.header.charset}&quot;&gt;, type: String)
     * |   +-- description (usage: &lt;meta name=&quot;Description&quot; content=&quot;{page.header.description}&quot;&gt;, type: String)
     * |   +-- keywords (usage: &lt;meta name=&quot;Keywords&quot; content=&quot;{page.header.keywords}&quot;&gt;, type: String)
     * |   +-- redirectUrl (usage: &lt;meta http-equiv=&quot;refresh&quot; content=&quot;3;url={page.header.redirectUrl}&quot;/&gt;, type: String)
     * +-- content (stuffs between &lt;body&gt; and &lt;/body&gt;, type: Map)
     *     +-- ...
     * </pre>
     *
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPage() {
        $name = Ddth_Dzit_DzitConstants::DATAMODEL_PAGE;
        $node = $this->getRootDataModel($name);
        if ( $node === NULL ) {
            $node = new Ddth_Template_DataModel_Map($name);
            $this->populateRootDataModel($name, $node);
        }
        $this->populateModelPageForm($node);
        $this->populateModelPageHeader($node);
        $this->populateModelPageContent($node);
    }

    /**
     * Populates page's content (stuff between &lt;body> and
     * &lt;/body>). By default, this method just does nothing.
     *
     * @param Ddth_Template_DataModel_Map
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPageContent($page) {
        //empty
    }

    /**
     * Populates page's header (stuff between &lt;head> and
     * &lt;/head>).
     *
     * @param Ddth_Template_DataModel_Map
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPageHeader($page) {
        $name = Ddth_Dzit_DzitConstants::DATAMODEL_PAGE_HEADER;
        $pageHeader = new Ddth_Template_DataModel_Map($name);
        $page->addChild($name, $pageHeader);
        $this->populateModelPageHeaderTitle($pageHeader);
        $this->populateModelPageHeaderCharset($pageHeader);
        $this->populateModelPageHeaderDescription($pageHeader);
        $this->populateModelPageHeaderKeywords($pageHeader);
        $this->populateModelPageHeaderRedirectUrl($pageHeader);
    }

    /**
     * Populates page's header (string between &lt;title> and
     * &lt;/title>)
     *
     * @param Ddth_Template_DataModel_Map
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPageHeaderTitle($pageHeader) {
        $pageHeader->addChild(Ddth_Dzit_DzitConstants::DATAMODEL_PAGE_HEADER_TITLE, 'Dzit Page Title');
    }

    /**
     * Populates page's charset.
     *
     * @param Ddth_Template_DataModel_Map
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPageHeaderCharset($pageHeader) {
        $pageHeader->addChild(Ddth_Dzit_DzitConstants::DATAMODEL_PAGE_HEADER_CHARSET, 'utf-8');
    }

    /**
     * Populates page's description.
     *
     * @param Ddth_Template_DataModel_Map
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPageHeaderDescription($pageHeader) {
        $pageHeader->addChild(Ddth_Dzit_DzitConstants::DATAMODEL_PAGE_HEADER_DESCRIPTION, 'Dzit Description');
    }

    /**
     * Populates page's keywords.
     *
     * @param Ddth_Template_DataModel_Map
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPageHeaderKeywords($pageHeader) {
        $pageHeader->addChild(Ddth_Dzit_DzitConstants::DATAMODEL_PAGE_HEADER_KEYWORDS, 'Dzit Keywords');
    }

    /**
     * Populates redirection url. By default, this method just does nothing.
     * Sub-classes may override this method if needed.
     *
     * @param Ddth_Template_DataModel_Map
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPageHeaderRedirectUrl($pageHeader) {
        //empty
    }

    /**
     * Populates page's main form ('page.form' data model). By default, this
     * method just does nothing. Sub-classes may override this method if needed.
     *
     * @param Ddth_Template_DataModel_Map
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelPageForm($page) {
        //empty
    }

    /**
     * Populates the language pack ('language' data model).
     *
     * @throws Ddth_Dzit_DzitException
     */
    protected function populateModelLanguage() {
        $name = Ddth_Dzit_DzitConstants::DATAMODEL_LANGUAGE;
        $node = $this->getRootDataModel($name);
        if ( $node === NULL ) {
            $language = $this->getLanguage();
            $node = new Ddth_Template_DataModel_Bean($name, $language);
            $this->populateRootDataModel($name, $node);
        }
    }
}
?>