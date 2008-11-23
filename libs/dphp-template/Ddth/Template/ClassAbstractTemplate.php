<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Abstract template pack.
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
 * @id			$Id: ClassAbstractTemplate.php 171 2008-05-19 08:11:21Z btnguyen2k@gmail.com $
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
 * Abstract template pack.
 *
 * @package    	Template
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Template_AbstractTemplate implements Ddth_Template_ITemplate {
    const PROPERTY_NAME = "name";

    const PROPERTY_DISPLAY_NAME = "display";

    const PROPERTY_TYPE = "type";

    const PROPERTY_DESCRIPTION = "description";

    const PROPERTY_LOCATION = "location";

    const PROPERTY_BASE_DIRECTORY = "baseDirectory";

    const PROPERTY_CONFIG_FILE = "configFile";

    /**
     * @var Ddth_Commons_Properties
     */
    private $settings = NULL;

    /**
     * @var string
     */
    private $templateName = NULL;

    /**
     * @var string
     */
    private $displayName = NULL;

    /**
     * @var string
     */
    private $description = NULL;

    /**
     * @var Ddth_Commons_Properties
     */
    private $templateConfig = NULL;

    /**
     * @var Ddth_Commons_File
     */
    private $baseDir = NULL;

    /**
     * @var Ddth_Commons_File
     */
    private $location = NULL;

    /**
     * @var Ddth_Commons_File
     */
    private $configFile = NULL;

    /**
     * Constructs a new Ddth_Template_AbstractTemplate object.
     */
    public function __construct() {
    }

    /**
     * Gets this template's configuration settings.
     *
     * @return Ddth_Commons_Properties
     */
    protected function getSettings() {
        if ( !($this->settings instanceof Ddth_Commons_Properties) ) {
            $this->settings = new Ddth_Commons_Properties();
        }
        return $this->settings;
    }

    /**
     * {@see Ddth_Template_ITemplate::getSetting()}
     */
    public function getSetting($key) {
        return $this->getSettings()->getProperty($key);
    }

    /**
     * Sets this template's configuration settings.
     *
     * @param Ddth_Commons_Properties
     */
    protected function setSettings($settings) {
        $this->settings = $settings;
    }

    /**
     * Gets character encoding setting.
     *
     * @return string
     */
    protected function getCharset() {
        return $this->getTemplateConfigProperty(self::PROPERTY_CHARSET);
    }
    
//    /**
//     * {@see Ddth_Template_ITemplate::getAbsoluteDir()}.
//     */
//    public function getAbsoluteDir() {
//        return $this->getLocation();
//    }

    /**
     * {@see Ddth_Template_ITemplate::getDescription()}
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * {@see Ddth_Template_ITemplate::getDir()}.
     */
    public function getDir() {
        return $this->getSetting(self::PROPERTY_LOCATION);
    }

    /**
     * {@see Ddth_Template_ITemplate::getDisplayName()}
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * {@see Ddth_Template_ITemplate::getName()}
     */
    public function getName() {
        return $this->templateName;
    }

    /**
     * Gets physical template filename for a page.
     *
     * @param string
     * @return string
     */
    protected function getPageTemplateFile($pageId) {
        $key = str_replace('{0}', $pageId, self::PROPERTY_PAGE);
        return $this->getTemplateConfigProperty($key);
    }

    /**
     * {@see Ddth_Template_ITemplate::init()}
     */
    public function init($settings) {
        $this->setSettings($settings);
        $this->templateName = $this->getSetting(self::PROPERTY_NAME);
        $this->displayName = $this->getSetting(self::PROPERTY_DISPLAY_NAME);
        $this->description = $this->getSetting(self::PROPERTY_DESCRIPTION);

        $this->baseDir = new Ddth_Commons_File($this->getSetting(self::PROPERTY_BASE_DIRECTORY));
        $this->location = new Ddth_Commons_File($this->getSetting(self::PROPERTY_LOCATION), $this->baseDir);
        $this->configFile = new Ddth_Commons_File($this->getSetting(self::PROPERTY_CONFIG_FILE), $this->location);

        $this->buildTemplateConfig();
    }

    /**
     * Gets base directory.
     *
     * @return Ddth_Commons_File
     */
    protected function getBaseDir() {
        return $this->baseDir;
    }

    /**
     * Gets configuration file.
     *
     * @return Ddth_Commons_File
     */
    protected function getConfigFile() {
        return $this->configFile;
    }

    /**
     * Gets location directory.
     *
     * @return Ddth_Commons_File
     */
    protected function getLocation() {
        return $this->location;
    }

    /**
     * Loads and builds template configuration data. Called by
     * {@link Ddth_Template_AbstractTemplate::init()} method.
     *
     * @throws Ddth_Template_TemplateException
     */
    protected function buildTemplateConfig() {
        $props = new Ddth_Commons_Properties();
        try {
            $props->load($this->configFile->getPathname());
        } catch ( Exception $e ) {
            $msg = $e->getMessage();
            throw new Ddth_Template_TemplateException($msg);
        }
        $this->setTemplateConfig($props);
    }

    /**
     * Sets template configurations.
     *
     * @param Ddth_Commons_Properties
     */
    protected function setTemplateConfig($templateConfig) {
        if ( $templateConfig===NULL || !($templateConfig instanceof Ddth_Commons_Properties) ) {
            $this->templateConfig = new Ddth_Commons_Properties();
        } else {
            $this->templateConfig = $templateConfig;
        }
    }

    /**
     * Gets template configurations.
     *
     * @return Ddth_Commons_Properties
     */
    protected function getTemplateConfig() {
        return $this->templateConfig;
    }

    /**
     * Gets a template configuration property.
     *
     * @param string
     * @return string
     */
    protected function getTemplateConfigProperty($key) {
        return $this->getTemplateConfig()->getProperty($key);
    }
}
?>