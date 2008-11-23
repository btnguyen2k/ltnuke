<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * File-based language pack.
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
 * @id			$Id: ClassFileLanguage.php 161 2008-04-17 04:48:57Z btnguyen2k@gmail.com $
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
 * File-based language pack.
 *
 * @package    	Mls
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_Mls_FileLanguage extends Ddth_Mls_AbstractLanguage {
    const PROPERTY_LOCATION = "location";

    const PROPERTY_BASE_DIRECTORY = "base.directory";

    /**
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;

    /**
     * @var string
     */
    private $location = NULL;

    /**
     * @var string
     */
    private $baseDirectory = NULL;

    /**
     * Constructs a new Ddth_Mls_FileLanguage object.
     */
    public function __construct() {
        parent::__construct();
        $clazz = __CLASS__;
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog($clazz);
    }

    /**
     * {@see Ddth_Mls_AbstractLanguage::buildLanguageData()}
     */
    protected function buildLanguageData() {
        $this->baseDirectory = trim($this->getSetting(self::PROPERTY_BASE_DIRECTORY));
        $this->baseDirectory = preg_replace('/[\\/]+/', '/', $this->baseDirectory);
        $this->location = trim($this->getSetting(self::PROPERTY_LOCATION));
        $this->location = preg_replace('/[\\/]+/', '/', $this->location);
        $dir = $this->baseDirectory . '/' . $this->location;
        if ( !is_dir($dir) ) {
            $msg = "[$dir] is not a directory!";
            throw new Ddth_Mls_MlsException($msg);
        }
        $languageData = new Ddth_Commons_Properties();
        if ( $dh = @opendir($dir) ) {
            while ( $file = @readdir($dh) ) {
                if ( is_readable($dir.'/'.$file) && preg_match('/^.+\.properties$/i', $file) ) {
                    try {
                        $msg = "Load language file [$dir/$file]...";
                        $this->LOGGER->info($msg);
                        $languageData->load($dir.'/'.$file);
                    } catch ( Exception $e ) {
                        $msg = $e->getMessage();
                        $this->LOGGER->warn($msg, $e);
                    }
                }
            }
            @closedir($dh);
        } else {
            $msg = "[$dir] is not accessible!";
            throw new Ddth_Mls_MlsException($msg);
        }
        $this->setLanguageData($languageData);
    }
}
?>