<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Dzit's bootstrap script.
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
 * @id			$Id: index.php 16 2008-04-28 14:55:53Z btnguyen2k@gmail.com $
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
        if ( !Ddth_Commons_Loader::loadClass($className, $translator) ) {
            trigger_error("Can not load class [$className]!");
        }
    }
}

/*
 * This is the directory where configuration files are stored.
 * It should not be reachable from the web.
 */
define('CONFIG_DIR', '../config');
if ( !is_dir(CONFIG_DIR) ) {
    exit('Invalid CONFIG_DIR setting!');
}

/*
 * This is the directory where 3rd party libraries are located.
 * All 1st level sub-directories of this directory will be included
 * in the include_path
 */
define('LIBS_DIR', '../libs');
if ( !is_dir(LIBS_DIR) ) {
    exit('Invalid LIBS_DIR setting!');
}

/*
 * Dzit's configuration file (located in include_path)
 */
define('DZIT_CONFIG_FILE', CONFIG_DIR.'/dzit.properties');

/* set up include path */
$includePath = '.'.PATH_SEPARATOR.CONFIG_DIR;
if ( $dh = @opendir(LIBS_DIR) ) {
    while ( ($file = readdir($dh)) !== false ) {
        if ( is_dir(LIBS_DIR."/$file") && $file!="." && $file!=".." ) {
            $includePath .= PATH_SEPARATOR.LIBS_DIR."/$file";
        }
    }
} else {
    exit('Can not open LIBS_DIR!');
}
ini_set('include_path', $includePath);

/**
 * @var Ddth_Commons_Logging_ILog
 */
$logger = Ddth_Commons_Logging_LogFactory::getLog('Dzit');

/* load configurations */
$config = new Ddth_Dzit_Configurations(DZIT_CONFIG_FILE);
$appClass = $config->getApplicationClass();
$app = new $appClass();
if ( !($app instanceof Ddth_Dzit_IApplication) ) {
    exit("[$appClass] does not implement interface Ddth_Dzit_IApplication!"); 
}
//register the application instance
Ddth_Dzit_ApplicationRegistry::$CURRENT_APP = $app;
$hasError = false;
try {
    $app->init($config);
    $app->execute();
} catch ( Exception $e ) {
    $hasError = true;
    $logger->error($e->getMessage(), $e);
}
$app->destroy($hasError);
?>