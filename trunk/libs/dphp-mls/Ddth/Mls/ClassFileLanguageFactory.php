<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * File-based LanguageFactory.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Mls
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassFileLanguageFactory.php 161 2008-04-17 04:48:57Z btnguyen2k@gmail.com $
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
 * File-based LanguageFactory.
 *
 * This LanguageFactory loads language packs from files on disk.
 *
 * Configuration properties:
 * <code>
 * # Points to the root directory where all language packs are located.
 * file.base.directory=/path/to/languages/directory
 *
 * # Names of registered language packs, separated by (,) or (;) or spaces.
 * # Language name should contain only lower-cased letters (a-z), digits (0-9)
 * # and underscores only!
 * file.languages=default, en, vn
 *
 * # Class name of the concrete language pack, it *must* implement interface
 * # Ddth_Mls_ILanguage and _should_ extends class Ddth_Mls_FileLanguage.
 * # This property is optional, the default value is Ddth_Mls_FileLanguage.
 * file.language.class=Ddth_Mls_FileLanguage
 *
 * # file.<name>.display is the display name of the registered
 * # language <name>.
 * # This property is optional.
 * file.default.display=Default
 *
 * # file.<name>.description is the short description of the registered
 * # language <name>.
 * # This property is optional.
 * file.default.description=This is the default language pack
 *
 * # file.<name>.location points to a sub-directory, of the language factory's
 * # root directory, where files of this language pack are located
 * file.default.location=default
 *
 * # Other file.<name>.xxx properties are custom properties and will also be passed
 * # to the language pack's Ddth_Mls_ILanguage::init() method.
 * # Also a property file.<name>.name which holds the language pack's name
 * # and a property file.<name>.base.directory which is a copy of file.base.directory 
 * # will be created.
 *
 * # file.<name>.xxx properties will be wrapped inside a Ddth_Commons_Properties
 * # object (the "file.<name>." part will be removed) and passed to the
 * # language pack's Ddth_Mls_ILanguage::init() method.
 * </code>
 * See {@link Ddth_Mls_LanguageFactory configuration file format}.
 *
 * @package    	Mls
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Mls_FileLanguageFactory implements Ddth_Mls_ILanguageFactory {
    const DEFAULT_LANGUAGE_CLASS = 'Ddth_Mls_FileLanguage';

    const PROPERTY_PREFIX = 'file.';
    
    const PROPERTY_BASE_DIRECTORY = 'file.base.directory';

    const PROPERTY_LANGUAGE_CLASS = 'file.language.class';

    const PROPERTY_REGISTERED_LANGUAGES = 'file.languages';

    const PROPERTY_LANGUAGE_DISPLAY_NAME = 'file.{0}.display';

    const PROPERTY_LANGUAGE_DESCRIPTION = 'file.{0}.description';

    const PROPERTY_LANGUAGE_LOCATION = 'file.{0}.location';

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
    private $registeredLanguages = Array();

    /**
     * @var string
     */
    private $baseDir = NULL;

    /**
     * Constructs a new Ddth_Mls_FileLanguageFactory object.
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
     * {@see Ddth_Mls_ILanguageFactory::getLanguage()}
     */
    public function getLanguage($name) {
        if ( isset($this->registeredLanguages[$name]) ) {
            return $this->registeredLanguages[$name];
        }
        return NULL;
    }

    /**
     * {@see Ddth_Mls_ILanguageFactory::getLanguageNames()}
     */
    public function getLanguageNames() {
        return array_keys($this->registeredLanguages);
    }

    /*
     * {@see Ddth_Mls_ILanguageFactory::init()}
     */
    public function init($settings) {
        $this->setSettings($settings);

        //set up base directory
        $baseDirectory = $this->getSetting(self::PROPERTY_BASE_DIRECTORY);
        if ( $baseDirectory===NULL || trim($baseDirectory)==="" ) {
            $msg = 'Can not find base directory setting!';
            $this->LOGGER->fatal($msg);
            throw new Ddth_Mls_MlsException($msg);
        }
        if ( !is_dir(trim($baseDirectory)) ) {
            $msg = "[$baseDirectory] is not found or not a directory!";
            $this->LOGGER->fatal($msg);
            throw new Ddth_Mls_MlsException($msg);
        }
        $this->baseDir = trim($baseDirectory);

        //set up language pack class
        $languageClass = $this->getSetting(self::PROPERTY_LANGUAGE_CLASS);
        if ( $languageClass===NULL || trim($languageClass)==="" ) {
            $languageClass = self::DEFAULT_LANGUAGE_CLASS;
        } else {
            $languageClass = trim($languageClass);
        }

        //load registered language packs
        $languagePacks = $this->getSetting(self::PROPERTY_REGISTERED_LANGUAGES);
        $languagePacks = trim(preg_replace('/[\s,;]+/', ' ', $languagePacks));
        $tokens = preg_split('/[\s,;]+/', trim($languagePacks));
        foreach ( $tokens as $langName ) {
            if ( $langName==="" ) {
                continue;
            }
            $msg = "Loading language pack [$langName]...";
            $this->LOGGER->info($msg);
            if ( isset($this->registeredLanguages[$langName]) ) {
                $msg = "Language pack [$langName] has already been registered.";
                $this->LOGGER->warn($msg);
                continue;
            }
            try {
                //create the language pack instance
                @$language = new $languageClass();
                if ( !($language instanceof Ddth_Mls_ILanguage) ) {
                    $msg = "[$languageClass] is not an instance of Ddth_Mls_ILanguage!";
                    throw new Ddth_Mls_MlsException($msg);
                }
                //set up configuration properties
                $prefix = self::PROPERTY_PREFIX.$langName.".";
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
                $key = Ddth_Mls_AbstractLanguage::PROPERTY_NAME;
                $props->setProperty($key, $langName);
                $key = Ddth_Mls_FileLanguage::PROPERTY_BASE_DIRECTORY;
                $props->setProperty($key, $baseDirectory);
                $language->init($props);
                $this->registeredLanguages[$langName] = $language;
            } catch ( Exception $e ) {
                $msg = $e->getMessage();
                $this->LOGGER->error($msg, $e);
            }
        }
        if ( count($this->registeredLanguages) === 0 ) {
            $msg = "There is no registered language packs!";
            $this->LOGGER->warn($msg);
        }
    }
}
?>