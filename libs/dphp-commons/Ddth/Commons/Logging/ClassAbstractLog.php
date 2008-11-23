<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * An abstract named logger.
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
 * @id			$Id: ClassAbstractLog.php 147 2008-03-09 06:00:32Z nbthanh@vninformatics.com $
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
 * An abstract named logger.
 *
 * This class is the top level abstract class of all other concrete named
 * logger inplementations.
 *
 * @package    	Commons
 * @subpackage	Logging
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @since      	Class available since v0.1
 */
abstract class Ddth_Commons_Logging_AbstractLog
implements Ddth_Commons_Logging_ILog {
    private $className;

    private $settings;

    private $isTrace = false;
    private $isDebug = false;
    private $isInfo = false;
    private $isWarn = false;
    private $isError = false;
    private $isFatal = false;

    /**
     * Constructs an new Ddth_Commons_Logging_AbstractLog object.
     *
     * @param logical name of the logger
     */
    public function __construct($className) {
        $this->className = $className;
    }

    /**
     * Initializes this logger.
     *
     * @param Ddth_Commons_Properties initializing properties
     * @throws {@link Ddth_Commons_Logging_LogConfigurationException LogConfigurationException}
     */
    public function init($prop) {
        //normalize class name
        if ( !is_string($this->className) ) {
            $this->className = NULL;
        }
        if ( $this->className !== NULL ) {
            $this->className = trim(str_replace('::', '_', $this->className));
        }

        if ( $prop === NULL ) {
            $prop = new Ddth_Commons_Properties();
        }
        if ( !($prop instanceof Ddth_Commons_Properties) ) {
            $msg = 'Invalid argument!';
            throw new Ddth_Commons_Logging_LogConfigurationException($msg);
        }
        $this->settings = $prop;

        //set up logging level
        $loggerClazzs = Array();
        $needle = Ddth_Commons_Logging_ILog::SETTING_PREFIX_LOGGER_CLASS;
        foreach ( $prop->keys() as $key ) {
            $pos = strpos($key, $needle);
            if ( $pos !== false ) {
                $loggerClazzs[] = substr($key, $pos+strlen($needle));
            }
        }
        sort($loggerClazzs);
        $loggerClazzs = array_reverse($loggerClazzs);
        $found = false;
        $level = NULL;
        foreach ( $loggerClazzs as $clazz ) {
            if ( $this->className === $clazz ||
            strpos($this->className, $clazz.'_')!==false ) {                
                $key = Ddth_Commons_Logging_ILog::SETTING_PREFIX_LOGGER_CLASS.$clazz;
                $level = trim(strtoupper($prop->getProperty($key)));
                $found = true;                
                break;
            }
        }

        if ( !$found ) {
            $key = Ddth_Commons_Logging_ILog::SETTING_DEFAULT_LOG_LEVEL;
            $level = trim(strtoupper($prop->getProperty($key)));
        }        

        switch ($level) {
            case 'TRACE':
                $this->isTrace = true;
            case 'DEBUG':
                $this->isDebug = true;
            case 'INFO':
                $this->isInfo = true;
            case 'WARN':
                $this->isWarn = true;
            case 'ERROR':
                $this->isError = true;
            case 'FATAL':
                $this->isFatal = true;
            default:
                //default level = ERROR
                $this->isError = true;
                $this->isFatal = true;
        }
    }

    /**
     * Is debug logging currently enabled?
     *
     * @return bool
     */
    public function isDebugEnabled() {
        return $this->isDebug;
    }

    /**
     * Is error logging currently enabled?
     *
     * @return bool
     */
    public function isErrorEnabled() {
        return $this->isError;
    }

    /**
     * Is fatal logging currently enabled?
     *
     * @return bool
     */
    public function isFatalEnabled() {
        return $this->isFatal;
    }

    /**
     * Is info logging currently enabled?
     *
     * @return bool
     */
    public function isInfoEnabled() {
        return $this->isInfo;
    }

    /**
     * Is trace logging currently enabled?
     *
     * @return bool
     */
    public function isTraceEnabled() {
        return $this->isTrace;
    }

    /**
     * Is warn logging currently enabled?
     *
     * @return bool
     */
    public function isWarnEnabled() {
        return $this->isWarn;
    }
}
?>