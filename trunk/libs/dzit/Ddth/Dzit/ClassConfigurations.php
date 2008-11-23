<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Encapsulates Dzit's start up configurations.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Dzit
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassConfigurations.php 16 2008-04-28 14:55:53Z btnguyen2k@gmail.com $
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
 * Encapsulates Dzit's start up configurations.
 *
 * Dzit's start-up configurations are stored in a .properties file with the
 * following format:
 * <code>
 * # Name of the application class. It must implement interface
 * # Ddth_Dzit_IApplication, and is recommended to extends class
 * # Ddth_Dzit_App_GenericApplication
 * # Default value is Ddth_Dzit_App_GenericApplication
 * dzit.application.class=Ddth_Dzit_App_GenericApplication
 *
 * # Action mapping configuration settings file
 * dzit.action_mapping.file=dzit-action_mapping.xml
 *
 * # Name of Ddth_Adodb's configuration file. If this property is set,
 * # application is adodb-enabled.
 * # This configuration setting is supported by Ddth_Dzit_App_GenericApplication
 * dzit.adodb.config.file=dphp-adodb.properties
 *
 * # Name of Ddth_Mls's configuration file. If this property is set,
 * # application is Mls-enabled.
 * dzit.mls.config.file=dphp-mls.properties
 *
 * # Name of Ddth_Template's configuration file. If this property is set,
 * # application is Template-enabled.
 * dzit.template.config.file=dphp-template.properties
 * </code>
 *
 * @package    	Dzit
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Dzit_Configurations {

    const DEFAULT_APPLICATION_CLASS = 'Ddth_Dzit_App_GenericApplication';

    const PROPERTY_APPLICATION_CLASS = 'dzit.application.class';

    const PROPERTY_ACTION_MAPPING_FILE = 'dzit.action_mapping.file';

    const PROPERTY_ADODB_CONFIG_FILE = 'dzit.adodb.config.file';

    const PROPERTY_MLS_CONFIG_FILE = 'dzit.mls.config.file';

    const PROPERTY_TEMPLATE_CONFIG_FILE = 'dzit.template.config.file';

    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;

    /**
     * @var Ddth_Commons_Properties
     */
    private $settings;

    /**
     * Constructs a new Ddth_Dzit_Configurations object
     *
     * @param string
     */
    public function __construct($configFile) {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
        $this->settings = new Ddth_Commons_Properties();
        try {
            $this->settings->load($configFile);
        } catch ( Exception $e ) {
            $msg = $e->getMessage();
            $this->LOGGER->error($msg, $e);
        }
    }

    /**
     * Gets name of action mapping configurations file.
     *
     * @return string
     */
    public function getActionMappingFile() {
        $key = self::PROPERTY_ACTION_MAPPING_FILE;
        return $this->getSetting($key);
    }

    /**
     * Returns ADOdb support status.
     *
     * @return bool
     */
    public function supportAdodb() {
        $adodbConfigFile = $this->getAdodbConfigFile();
        return $adodbConfigFile !== NULL && trim($adodbConfigFile) !== "";
    }

    /**
     * Gets name of configuration file for ADOdb factory.
     *
     * @return string
     */
    public function getAdodbConfigFile() {
        $key = self::PROPERTY_ADODB_CONFIG_FILE;
        return $this->getSetting($key);
    }

    /**
     * Returns Mls support status.
     *
     * @return bool
     */
    public function supportMls() {
        $mlsConfigFile = $this->getMlsConfigFile();
        return $mlsConfigFile !== NULL && trim($mlsConfigFile) !== "";
    }

    /**
     * Gets name of configuration file for Mls factory.
     *
     * @return string
     */
    public function getMlsConfigFile() {
        $key = self::PROPERTY_MLS_CONFIG_FILE;
        return $this->getSetting($key);
    }

    /**
     * Returns Template support status.
     *
     * @return bool
     */
    public function supportTemplate() {
        $templateConfigFile = $this->getTemplateConfigFile();
        return $templateConfigFile !== NULL && trim($templateConfigFile) !== "";
    }

    /**
     * Gets name of configuration file for Template factory.
     *
     * @return string
     */
    public function getTemplateConfigFile() {
        $key = self::PROPERTY_TEMPLATE_CONFIG_FILE;
        return $this->getSetting($key);
    }

    /**
     * Gets application class name setting.
     *
     * @return string
     */
    public function getApplicationClass() {
        $key = self::PROPERTY_APPLICATION_CLASS;
        return $this->getSetting($key, self::DEFAULT_APPLICATION_CLASS);
    }

    /**
     * Gets the internal settings object.
     *
     * @return Ddth_Commons_Properties
     */
    protected function getSettings() {
        return $this->settings;
    }

    /**
     * Gets a single setting.
     *
     * @param string
     * @return string
     */
    protected function getSetting($key, $defaultValue=NULL) {
        return $this->settings->getProperty($key, $defaultValue);
    }
}
?>