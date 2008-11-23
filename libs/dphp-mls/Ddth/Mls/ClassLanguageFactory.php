<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Factory to create instances of ILanguageFactory.
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
 * @id			$Id: ClassLanguageFactory.php 166 2008-04-28 09:25:04Z btnguyen2k@gmail.com $
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
 * Factory to create instances of ILanguageFactory.
 *
 * Configuration file format: the configurations are stored in
 * .properties file; supported properties are:
 * <code>
 * # Class name of the concrete factory.
 * # Default value is Ddth_Mls_FileLanguageFactory.
 * factory.class=Ddth_Mls_FileLanguageFactory
 *
 * # Each concrete factory will have its own configuration properties,
 * # eee its phpDocs for details.
 * </code>
 * The default configuration file is dphp-mls.properties located in
 * {@link http://www.php.net/manual/en/ini.core.php#ini.include-path include-path}.
 *
 * @package    	Mls
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Mls_LanguageFactory {
    private static $cacheInstances = Array();

    const DEFAULT_CONFIG_FILE = "dphp-mls.properties";

    const DEFAULT_FACTORY_CLASS = 'Ddth_Mls_FileLanguageFactory';

    const PROPERTY_FACTORY_CLASS = "factory.class";

    /**
     * Gets an instance of Ddth_Mls_ILanguageFactory.
     *
     * Note: {@link Ddth_Mls_LanguageFactory configuration file format}.
     *
     * @param string name of the configuration file (located in
     * {@link http://www.php.net/manual/en/ini.core.php#ini.include-path include-path})
     * @return Ddth_Mls_ILanguageFactory
     * @throws {@link Ddth_Mls_MlsException MlsException}
     */
    public static function getInstance($configFile=NULL) {
        if ( $configFile === NULL ) {
            return self::getInstance(self::DEFAULT_CONFIG_FILE);
        }
        if ( !isset(self::$cacheInstances[$configFile]) ) {
            $fileContent = Ddth_Commons_Loader::loadFileContent($configFile);
            if ( $fileContent === NULL ) {
                $msg = "Can not read file [$configFile]!";
                throw new Ddth_Mls_MlsException($msg);
            }
            $prop = new Ddth_Commons_Properties();
            try {
                $prop->import($fileContent);
            } catch ( Exception $e ) {
                $msg = $e->getMessage();
                throw new Ddth_Mls_MlsException($msg, $e->getCode());
            }
            $factoryClass = $prop->getProperty(self::PROPERTY_FACTORY_CLASS);
            if ( $factoryClass===NULL || trim($factoryClass)==="" ) {
                $factoryClass = self::DEFAULT_FACTORY_CLASS;
            } else {
                $factoryClass = trim($factoryClass);
            }
            try {
                @$instance = new $factoryClass();
                if ( $instance instanceof Ddth_Mls_ILanguageFactory ) {
                    $instance->init($prop);
                    self::$cacheInstances[$configFile] = $instance;
                } else {
                    $msg = "[$factoryClass] does not implement Ddth_Mls_ILanguageFactory";
                    throw new Ddth_Mls_MlsException($msg);
                }
            } catch ( Ddth_Mls_MlsException $me ) {
                throw $me;
            } catch ( Exception $e ) {
                $msg = $e->getMessage();
                throw new Ddth_Mls_MlsException($msg);
            }
        }
        return self::$cacheInstances[$configFile];
    }
}
?>