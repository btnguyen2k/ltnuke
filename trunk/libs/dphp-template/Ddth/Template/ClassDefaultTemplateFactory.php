<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Default template factory.
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
 * @id			$Id: ClassDefaultTemplateFactory.php 161 2008-04-17 04:48:57Z btnguyen2k@gmail.com $
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
 * Default template factory.
 *
 * This TemplateFactory loads template packs from files on disk.
 *
 * Configuration properties (stored as .properties format):
 * <code>
 * # Points to the root directory where all template packs are located.
 * baseDirectory=/path/to/templates/directory
 *
 * # Names of registered template packs, separated by (,) or (;) or spaces.
 * # Template name should contain only lower-cased letters (a-z), digits (0-9)
 * # and underscores only!
 * templates=default, fancy
 *
 * # template.<name>.display is the display name of the registered
 * # template <name>.
 * # This property is optional.
 * template.default.display=Default
 *
 * # template.<name>.type is the type of the registered template <name>.
 * # Supported tempalte types: smarty, php
 * template.default.type=smarty
 *
 * # template.<name>.description is the short description of the registered
 * # template <name>.
 * # This property is optional.
 * template.default.description=This is the default template pack
 *
 * # template.<name>.location points to a sub-directory, of the template factory's
 * # root directory, where files of this template pack are located.
 * template.default.location=default
 * 
 * # template.<name>.configFile is the name of the template pack configuration file.
 * # This file is located under template.<name>.location
 * template.default.configFile=config.properties
 *
 * # Other template.<name>.xxx properties are custom properties and will also be passed
 * # to the template pack's Ddth_Template_ITemplate::init() method.
 * # Also a property template.<name>.name which holds the template pack's name
 * # and a property template.<name>.baseDirectory which is a copy of baseDirectory
 * # will be created and passed to Ddth_Template_ITemplate::init() method.
 *
 * # template.<name>.xxx properties will be wrapped inside a Ddth_Commons_Properties
 * # object (the "template.<name>." part will be removed) and passed to the
 * # template pack's Ddth_Template_ITemplate::init() method.
 * 
 * # The following properties are for Smartyt-type template:
 * # - name of the directory to store Smarty's cache files (located inside template.<name>.location)
 * template.<name>.smarty.cache=cache
 * # - name of the directory to store Smarty's compiled template files (located inside template.<name>.location)
 * template.<name>.smarty.compile=templates_c
 * # - name of the directory to store Smarty's configuration files (located inside template.<name>.location)
 * template.<name>.smarty.configs=configs
 * </code>
 * See {@link Ddth_Template_TemplateFactory configuration file format}.
 *
 * @package    	Template
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Template_DefaultTemplateFactory implements Ddth_Template_ITemplateFactory {
    const PROPERTY_PREFIX = 'template.';

    const PROPERTY_BASE_DIRECTORY = 'baseDirectory';

    const PROPERTY_REGISTERED_TEMPLATES = 'templates';

    /*
    const PROPERTY_TEMPLATE_DISPLAY_NAME = 'template.{0}.display';

    const PROPERTY_TEMPLATE_TYPE = 'template.{0}.type';

    const PROPERTY_TEMPLATE_DESCRIPTION = 'template.{0}.description';

    const PROPERTY_TEMPLATE_LOCATION = 'template.{0}.location';
	*/

    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;

    /**
     * @var Ddth_Commons_Properties
     */
    private $settings = NULL;

    /**
     * @var Array()
     */
    private $registeredTemplates = Array();

    /**
     * @var string
     */
    private $baseDir = NULL;

    /**
     * Constructs a new Ddth_Template_DefaultTemplateFactory object.
     */
    public function __construct() {
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
    }

    /**
     * Gets this factory's configuration settings.
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
     * Gets a configuration setting.
     *
     * @param string
     * @return string
     */
    protected function getSetting($key) {
        return $this->getSettings()->getProperty($key);
    }

    /**
     * Sets this factory's configuration settings.
     *
     * @param Ddth_Commons_Properties
     */
    protected function setSettings($settings) {
        $this->settings = $settings;
    }

    /**
     * {@see Ddth_Template_ITemplateFactory::getTemplate()}
     */
    public function getTemplate($name) {
        if ( isset($this->registeredTemplates[$name]) ) {
            return $this->registeredTemplates[$name];
        }
        return NULL;
    }

    /**
     * {@see Ddth_Template_ITemplateFactory::getTemplateNames()}
     */
    public function getTemplateNames() {
        return array_keys($this->registeredTemplates);
    }

    /*
     * {@see Ddth_Template_ITemplateFactory::init()}
     */
    public function init($settings) {
        $this->setSettings($settings);

        //set up base directory
        $baseDirectory = $this->getSetting(self::PROPERTY_BASE_DIRECTORY);
        if ( $baseDirectory===NULL || trim($baseDirectory)==="" ) {
            $msg = 'Can not find base directory setting!';
            $this->LOGGER->fatal($msg);
            throw new Ddth_Template_TemplateException($msg);
        }
        if ( !is_dir(trim($baseDirectory)) ) {
            $msg = "[$baseDirectory] is not found or not a directory!";
            $this->LOGGER->fatal($msg);
            throw new Ddth_Template_TemplateException($msg);
        }
        $this->baseDir = trim($baseDirectory);

        //load registered template packs
        $templatePacks = $this->getSetting(self::PROPERTY_REGISTERED_TEMPLATES);
        $templatePacks = trim(preg_replace('/[\s,;]+/', ' ', $templatePacks));
        $tokens = preg_split('/[\s,;]+/', trim($templatePacks));
        foreach ( $tokens as $templateName ) {
            if ( $templateName==="" ) {
                continue;
            }
            $msg = "Loading template pack [$templateName]...";
            $this->LOGGER->info($msg);
            if ( isset($this->registeredTemplates[$templateName]) ) {
                $msg = "Template pack [$templateName] has already been registered.";
                $this->LOGGER->warn($msg);
                continue;
            }
            try {
                //set up configuration properties
                $prefix = self::PROPERTY_PREFIX.$templateName.".";
                $len = strlen($prefix);
                $props = new Ddth_Commons_Properties();
                foreach ( $this->getSettings()->keys() as $key ) {
                    if ( $prefix === substr($key, 0, $len) ) {
                        $value = $this->getSetting($key);
                        $key = substr($key, $len);
                        if ( $key !== '' && $value !== NULL ) {
                            $props->setProperty($key, $value);
                        }
                    }
                }
                $key = Ddth_Template_AbstractTemplate::PROPERTY_NAME;
                $props->setProperty($key, $templateName);
                $key = Ddth_Template_AbstractTemplate::PROPERTY_BASE_DIRECTORY;
                $props->setProperty($key, $baseDirectory);
                
                //create the template pack instance
                $key = Ddth_Template_AbstractTemplate::PROPERTY_TYPE;
                $templateType = $props->getProperty($key);
                $template = NULL;
                if ( strtolower($templateType) === 'smarty' ) {
                    $template = new Ddth_Template_Smarty_SmartyTemplate();
                } elseif ( strtolower($templateType) === 'php' ) {
                    $template = new Ddth_Template_Php_PhpTemplate();
                } else {
                    $msg = "Template type [$templateType] is not supported!";
                    $this->LOGGER->warn($msg);
                    continue;
                }
                $template->init($props);
                $this->registeredTemplates[$templateName] = $template;
            } catch ( Exception $e ) {
                $msg = $e->getMessage();
                $this->LOGGER->error($msg, $e);
            }
        }
        if ( count($this->registeredTemplates) === 0 ) {
            $msg = "There is no registered template packs!";
            $this->LOGGER->warn($msg);
        }
    }
}
?>