<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * An abstract implementation of IApplication.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Dzit
 * @subpackage  App
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassAbstractApplication.php 27 2008-07-14 03:43:23Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Abstract implementation of IApplication.
 *
 * @package    	Dzit
 * @subpackage  App
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Dzit_App_AbstractApplication implements Ddth_Dzit_IApplication {

    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;

    /**
     * @var Ddth_Dzit_Configurations
     */
    private $dzitConfig;

    /**
     * @var Ddth_Adodb_IAdodbFactory
     */
    private $adodbFactory = NULL;

    /**
     * @var ADOConnection
     */
    private $adodbConn = NULL;

    /**
     * @var Ddth_Dzit_IHttpRequest
     */
    private $httpRequest = NULL;

    /**
     * @var Ddth_Dzit_IUrlCreator
     */
    private $urlCreator = NULL;

    /**
     * @var Ddth_Xpath_Xpath
     */
    private $actionMappingConfig = NULL;

    /**
     * Application-level attributes.
     *
     * @var Array()
     */
    private $appAttributes = Array();

    /**
     * Currently executing action handler.
     *
     * @var Ddth_Dzit_IActionHandler
     */
    private $currentActionHandler = NULL;

    /**
     * @var Ddth_Mls_ILanguageFactory
     */
    private $languageFactory = NULL;

    /**
     * @var Ddth_Template_ITemplateFactory
     */
    private $templateFactory = NULL;

    /**
     * Constructs a new Ddth_Dzit_App_AbstractApplication object.
     */
    public function __construct() {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
        Ddth_Dzit_ApplicationRegistry::registerApplication($this);
    }

    /**
     * Clean-up method.
     *
     * This method is called just before the application object is abandoned.
     *
     * @throws Ddth_Dzit_DzitException
     */
    public function destroy($hasError=false) {
        $this->closeAdodbConnection($hasError);
    }

    /**
     * {@see Ddth_Dzit_IApplication::createTransmission()}
     */
    public function createTransmission($message, $url=NULL, $timeout=2) {
        $transmission = Ddth_Dzit_Transmission::createTransmission($message, $url, $timeout);
        $id = $transmission->getId();
        $_SESSION[Ddth_Dzit_DzitConstants::SESSION_TRANSMISSION][$id] = $transmission;
        return $transmission;
    }

    /**
     * {@see Ddth_Dzit_IApplication::deleteTransmission()}
     */
    public function deleteTransmission($id=NULL) {
        $transmission = $this->getTransmission($id);
        if ( $transmission !== NULL ) {
            unset($_SESSION[Ddth_Dzit_DzitConstants::SESSION_TRANSMISSION][$transmission->getId()]);
        }
    }

    /**
     * {@see Ddth_Dzit_IApplication::getTransmission()}
     */
    public function getTransmission($id=NULL) {
        if ( $id === NULL ) {
        $key = Ddth_Dzit_DzitConstants::URL_PARAM_TRANSMISSION;
            $id = isset($_GET[$key]) ? $_GET[$key] : NULL;
        }
        if ( $id === NULL ) {
            return NULL;
        }
        $key = Ddth_Dzit_DzitConstants::SESSION_TRANSMISSION;
        return isset($_SESSION[$key][$id]) ? $_SESSION[$key][$id] : NULL;
    }

    /**
     * {@see Ddth_Dzit_IApplication::getAttribute()}
     */
    public function getAttribute($name) {
        return isset($this->appAttributes[$name]) ? $this->appAttributes[$name] : NULL;
    }

    /**
     * {@see Ddth_Dzit_IApplication::setAttribute()}
     */
    public function setAttribute($name, $value) {
        $this->appAttributes[$name] = $value;
    }

    /**
     * {@see Ddth_Dzit_IApplication::getCurrentActionHandler()}
     */
    public function getCurrentActionHandler() {
        return $this->currentActionHandler;
    }

    /**
     * Sets the currently executing action handler.
     *
     * @param Ddth_Dzit_IActionHandler
     */
    protected function setCurrentActionHandler($actionHandler) {
        $this->currentActionHandler = $actionHandler;
    }

    /**
     * Creates and returns an action handler.
     *
     * @param string
     * @return Ddth_Dzit_IActionHandler
     * @throws Ddth_Dzit_DzitException
     */
    protected function createActionHandler($className) {
        try {
            $handler = new $className();
            if ( !($handler instanceof Ddth_Dzit_IActionHandler) ) {
                $msg = "[$className] is not an instance of Ddth_Dzit_IActionHandler!";
                throw new Ddth_Dzit_DzitException($msg);
            }
            //$handler->init();
        } catch ( Exception $e ) {
            if ( $e instanceof Ddth_Dzit_DzitException ) {
                throw $e;
            } else {
                throw new Ddth_Dzit_DzitException($e->getMessage());
            }
        }
        return $handler;
    }

    /**
     * Gets an action handler by xpath.
     *
     * @param string
     * @return Ddth_Dzit_IActionHandler
     * @throws Ddth_Dzit_DzitException
     */
    protected function getActionHandlerByXpath($xpath) {
        $nodes = $this->actionMappingConfig->xpath($xpath);
        if ( $nodes === NULL || !is_array($nodes) || count($nodes)===0 ) {
            return NULL;
        }
        $class = $nodes[0]->getAttribute('class');
        if ( $class === NULL || trim($class)==="" ) {
            $msg = "Invalid class for action handler located at [$xpath]!";
            throw new Ddth_Dzit_DzitException($msg);
        }
        return $this->createActionHandler($class);
    }

    /**
     * Gets the default action handler.
     *
     * @return Ddth_Dzit_IActionHandler
     * @throws Ddth_Dzit_DzitException
     */
    protected function getDefaultActionHandler() {
        $path = "/action-mapping/default-handler";
        return $this->getActionHandlerByXpath($path);
    }

    /**
     * Gets handler for an action.
     *
     * @param string
     * @return Ddth_Dzit_IActionHandler
     */
    protected function getActionHandler($action) {
        if ( $action === NULL || trim($action)==="" ) {
            return $this->getDefaultActionHandler();
        }
        $path = "/action-mapping/handler[@action=\"$action\"]";
        return $this->getActionHandlerByXpath($path);
    }

    /**
     * Creates and returns a view renderer.
     *
     * @param string
     * @return Ddth_Dzit_IViewRenderer
     * @throws Ddth_Dzit_DzitException
     */
    protected function createViewRenderer($className) {
        try {
            $renderer = new $className();
            if ( !($renderer instanceof Ddth_Dzit_IViewRenderer) ) {
                $msg = "[$className] is not an instance of Ddth_Dzit_IViewRenderer!";
                throw new Ddth_Dzit_DzitException($msg);
            }
            //renderer->init();
        } catch ( Exception $e ) {
            if ( $e instanceof Ddth_Dzit_DzitException ) {
                throw $e;
            } else {
                throw new Ddth_Dzit_DzitException($e->getMessage());
            }
        }
        return $renderer;
    }

    /**
     * Gets a view renderer by xpath.
     *
     * @param string
     * @return Ddth_Dzit_IViewRenderer
     * @throws Ddth_Dzit_DzitException
     */
    protected function getViewRendererByXpath($xpath) {
        $nodes = $this->actionMappingConfig->xpath($xpath);
        if ( $nodes === NULL || !is_array($nodes) || count($nodes)===0 ) {
            return NULL;
        }
        $class = $nodes[0]->getAttribute('class');
        if ( $class === NULL || trim($class)==="" ) {
            $msg = "Invalid class for view renderer located at [$xpath]!";
            throw new Ddth_Dzit_DzitException($msg);
        }
        return $this->createViewRenderer($class);
    }

    /**
     * Gets the default view renderer.
     *
     * @return Ddth_Dzit_IViewRenderer
     * @throws Ddth_Dzit_DzitException
     */
    protected function getDefaultViewRenderer() {
        $path = "/action-mapping/default-view";
        return $this->getViewRendererByXpath($path);
    }

    /**
     * Gets view renderer for an action.
     *
     * @param string
     * @return Ddth_Dzit_IViewRenderer
     */
    protected function getViewRenderer($action) {
        if ( $action === NULL || trim($action)==="" ) {
            return $this->getDefaultViewRenderer();
        }
        $path = "/action-mapping/view[@action=\"$action\"]";
        return $this->getViewRendererByXpath($path);
    }

    /**
     * Gets Dzit's configuration instance.
     *
     * @return Ddth_Dzit_Configurations
     */
    protected function getDzitConfig() {
        return $this->dzitConfig;
    }

    /**
     * {@see Ddth_Dzit_IApplication::getHttpRequest()}
     */
    public function getHttpRequest() {
        if ( $this->httpRequest === NULL ) {
            $this->httpRequest = new Ddth_Dzit_DefaultHttpRequest();
        }
        return new $this->httpRequest;
    }

    /**
     * {@see Ddth_Dzit_IApplication::getLanguage()}
     */
    public function getLanguage($name=NULL) {
        //TODO
        return $this->languageFactory->getLanguage(Ddth_Dzit_DzitConstants::LANGUAGE_DEFAULT);
    }

    /**
     * {@see Ddth_Dzit_IApplication::getTemplate()}
     */
    public function getTemplate($name=NULL) {
        //TODO
        return $this->templateFactory->getTemplate(Ddth_Dzit_DzitConstants::TEMPLATE_DEFAULT);
    }

    /**
     * {@see Ddth_Dzit_IApplication::getUrlCreator()}
     */
    public function getUrlCreator() {
        if ( $this->urlCreator === NULL ) {
            $this->urlCreator = new Ddth_Dzit_DefaultUrlCreator();
        }
        return new $this->urlCreator;
    }

    /**
     * {@see Ddth_Dzit_IApplication::init()}
     */
    public function init($config) {
        $this->dzitConfig = $config;
        $this->initSession();
        $this->initAdodbFactory();
        $this->initActionMapping();
        $this->initLanguageFactory();
        $this->initTemplateFactory();
    }

    /**
     * Initializes language factory.
     *
     * @throws Ddth_Dzit_DzitException
     */
    protected function initLanguageFactory() {
        try {
            if ( $this->getDzitConfig()->supportMls() ) {
                $mlsConfigFile = $this->getDzitConfig()->getMlsConfigFile();
                if ( trim($mlsConfigFile) === "" ) {
                    $mlsConfigFile = NULL;
                }
                $this->languageFactory = Ddth_Mls_LanguageFactory::getInstance($mlsConfigFile);
            } else {
                $this->languageFactory = NULL;
            }
        } catch ( Exception $e ) {
            $msg = $e->getMessage();
            throw new Ddth_Dzit_DzitException($msg);
        }
    }

    /**
     * Initializes template factory.
     *
     * @throws Ddth_Dzit_DzitException
     */
    protected function initTemplateFactory() {
        try {
            if ( $this->getDzitConfig()->supportTemplate() ) {
                $templateConfigFile = $this->getDzitConfig()->getTemplateConfigFile();
                if ( trim($templateConfigFile) === "" ) {
                    $templateConfigFile = NULL;
                }
                $this->templateFactory = Ddth_Template_TemplateFactory::getInstance($templateConfigFile);
            } else {
                $this->templateFactory = NULL;
            }
        } catch ( Exception $e ) {
            $msg = $e->getMessage();
            throw new Ddth_Dzit_DzitException($msg);
        }
    }

    /**
     * Initializes action mapping table.
     *
     * @throws Ddth_Dzit_DzitException
     */
    protected function initActionMapping() {
        $actionMappingFile = $this->getDzitConfig()->getActionMappingFile();
        $fileContent = Ddth_Commons_Loader::loadFileContent($actionMappingFile);
        if ( $fileContent === NULL ) {
            $msg = "Can not read action mapping file [$actionMappingFile]!";
            throw new Ddth_Dzit_DzitException($msg);
        }
        $this->actionMappingConfig = Ddth_Xpath_XmlParser::getInstance()->parseXml($fileContent);
    }

    /**
     * Initializes ADOdb factory.
     *
     * @throws Ddth_Dzit_DzitException
     */
    protected function initAdodbFactory() {
        try {
            if ( $this->getDzitConfig()->supportAdodb() ) {
                $adodbConfigFile = $this->getDzitConfig()->getAdodbConfigFile();
                if ( trim($adodbConfigFile) === "" ) {
                    $adodbConfigFile = NULL;
                }
                $this->adodbFactory = Ddth_Adodb_AdodbFactory::getInstance($adodbConfigFile);
            } else {
                $this->adodbFactory = NULL;
            }
        } catch ( Exception $e ) {
            $msg = $e->getMessage();
            throw new Ddth_Dzit_DzitException($msg);
        }
    }

    /**
     * Initializes session.
     *
     * @throws Ddth_Dzit_DzitException
     */
    protected function initSession() {
        session_start();
    }

    /**
     * {@see Ddth_Dzit_IApplication::getAdodbConnection()}
     */
    public function getAdodbConnection() {
        if ( $this->adodbFactory == NULL ) {
            return NULL;
        }
        if ( $this->adodbConn == NULL ) {
            $this->adodbConn = $this->adodbFactory->getConnection(true);
            $this->countAdodbConn = 0;
        }
        $this->countAdodbConn++;
        return $this->adodbConn;
    }

    /**
     * Closes any open ADOdb connection.
     *
     * @param bool indicate if an error has occurred
     */
    protected function closeAdodbConnection($hasError=false) {
        if ( $this->adodbConn !== NULL ) {
            if ( $hasError ) {
                $this->adodbConn->FailTrans();
            }
            $this->countAdodbConn--;
            if ( $this->countAdodbConn == 0 ) {
                $this->adodbFactory->closeConnection($this->adodbConn);
                $this->adodbConn = NULL;
            }
        }
    }
}
?>