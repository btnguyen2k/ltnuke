<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Factory for creating {@link Ddth_Commons_Logging_ILog ILog} instances.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Commons
 * @subpackage	Logging
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @id			$Id: ClassLogFactory.php 149 2008-03-12 06:02:50Z nbthanh@vninformatics.com $
 * @since      	File available since v0.1
 */

if ( !function_exists('__autoload') ) {
    /**
     * Automatically loads class source file when used.
     *
     * @param string
     */
    function __autoload($className) {
        require_once 'Ddth/Commons/ClassDefaultClassNameTranslator.php';
        require_once 'Ddth/Commons/ClassLoader.php';
        $translator = Ddth_Commons_DefaultClassNameTranslator::getInstance();
        Ddth_Commons_Loader::loadClass($className, $translator);
    }
}

/**
 * Factory for creating {@link Ddth_Commons_Logging_ILog ILog} instances.
 *
 * By default, this factory will look for its configuration file called
 * dphp-logging.properties in the
 * {@link http://www.php.net/manual/en/ini.core.php#ini.include-path include directory},
 * or user can specify his own configuration file.
 * 
 * The factory configuration file has the following format:
 * <code>
 * # Class name of the logger (an implementation of ILog)
 * # Default value is Ddth_Commons_Logging_SimpleLog
 * ddth.commons.logging.Logger=Ddth_Commons_Logging_SimpleLog
 * 
 * # logger.setting.xxx=setting xxx for the underlying logger
 * logger.setting.default=default log level (TRACE, DEBUG, INFO, WARN, ERROR, or FATAL) 
 * 
 * # The following settings are used by class AbstractLog:
 * # Set DEBUG log level for package Ddth
 * logger.setting.loggerClass.Ddth=DEBUG
 * # Set INFO log level for package Ddth_Commons
 * logger.setting.loggerClass.Ddth_Commons=INFO
 * # Set WARN log level for class Ddth_Commons_LogFactory
 * logger.setting.loggerClass.Ddth_Commons_LogFactory=WARN
 * </code>
 * 
 * Note: {@link ClassLoader.php Classes and files naming conventions}.
 * Note: The APIs of this package mimics Apache's commons-logging library.
 *
 * @package    	Commons
 * @subpackage	Logging
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @since      	Class available since v0.1
 */
final class Ddth_Commons_Logging_LogFactory {
    /**
     * The default configuration file.
     */
    const FACTORY_SETTINGS_FILE = "dphp-logging.properties";

    const SETTING_LOGGER = "ddth.commons.logging.Logger";

    const SETTING_PREFIX_LOGGER_SETTING = "logger.setting.";
    
    const DEFAULT_LOGGER = "Ddth_Commons_Logging_SimpleLog";

    private static $factorySettings = NULL;

    private static $logSettings = NULL;

    private static $reloadConfig = true;

    /**
     * Gets a named logger.
     *
     * @param string name of the logger
     * @param string name of the configuration file
     * @return Ddth_Commons_Logging_ILog
     * @throws {@link Ddth_Commons_Logging_LogConfigurationException LogConfigurationException}
     * @throws {@link Ddth_Commons_Exceptions_IllegalArgumentException IllegalArgumentException}
     * @throws {@link Ddth_Commons_Exceptions_IllegalStateException IllegalStateException}
     */
    public static function getLog($className, $configFile=NULL) {
        if ( self::$reloadConfig ) {
            if ( $configFile === NULL ) {
                self::loadFactorySettings(self::FACTORY_SETTINGS_FILE);                 
            } else {
                self::loadFactorySettings($configFile);
                self::$reloadConfig = true;
            }

        }        
        $prop = self::$factorySettings;        
        $loggerClass = $prop->getProperty(self::SETTING_LOGGER);        
        if ( $loggerClass === NULL || trim($loggerClass)==="" ) {
            $loggerClass = self::DEFAULT_LOGGER;
            //$msg = 'Invalid setting for "'.self::SETTING_LOGGER.'"';
            //throw new Ddth_Commons_Logging_LogConfigurationException($msg);
        }
        try {
            $log = new $loggerClass($className);
            $log->init(self::$logSettings);
            return $log;
        } catch (Ddth_Commons_Logging_LogConfigurationException $lce) {
            throw $lce;
        } catch (Exception $e) {
            $msg = '['.$e->getMessage().']\n'.$e->getTraceAsString();
            throw new Ddth_Commons_Logging_LogConfigurationException($msg);
        }
    }

    /**
     * Loads factory settings from configuration file.
     *
     * @param string name of the configuration file
     * @throws {@link Ddth_Commons_Logging_LogConfigurationException LogConfigurationException}
     * @throws {@link Ddth_Commons_Exceptions_IllegalArgumentException IllegalArgumentException}
     * @throws {@link Ddth_Commons_Exceptions_IllegalStateException IllegalStateException}
     */
    private static function loadFactorySettings($configFile=NULL) {
        if ( $configFile === NULL ) {
            $configFile = self::FACTORY_SETTINGS_FILE;
        }
        $config = Ddth_Commons_Loader::loadFileContent($configFile);
        if ( $config === NULL ) {           
            $msg = 'Can not load log factory configuration file "'.$configFile.'"';
            throw new Ddth_Commons_Logging_LogConfigurationException($msg);
        }        
        $prop = new Ddth_Commons_Properties();
        $prop->import($config);
        self::$factorySettings = $prop;

        self::$logSettings = self::buildLogSettings();
        
        self::$reloadConfig = false;
        
        return self::$factorySettings;
    }

    /**
     * Builds logger configuration settings from factory configuration settings.
     */
    private static function buildLogSettings() {
        $prop = new Ddth_Commons_Properties();
        foreach ( self::$factorySettings->keys() as $key ) {
            $found = strpos($key, self::SETTING_PREFIX_LOGGER_SETTING);
            if ( $found !== false ) {
                $k = substr($key, $found+strlen(self::SETTING_PREFIX_LOGGER_SETTING));
                $v = self::$factorySettings->getProperty($key);
                $prop->setProperty($k, $v);
            }
        }
        self::$logSettings = $prop;
        return self::$logSettings;
    }
}
?>