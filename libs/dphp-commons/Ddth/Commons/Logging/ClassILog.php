<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * A simple logging interface abstracting other logging libraries.
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
 * @id			$Id: ClassILog.php 116 2008-02-16 16:39:38Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * A simple logging interface abstracting other logging libraries.
 *
 * A simple logging interface abstracting other logging libraries. In order to be
 * instantiated successfully by LogFactory, classes that implement this interface
 * must have a constructor that takes a single string parameter representing the
 * "name" of this ILog.
 *
 * The six logging levels used by ILog are (in order):
 * 1. trace (the least serious)
 * 2. debug
 * 3. info
 * 4. warn
 * 5. error
 * 6. fatal (the most serious)
 *
 * The mapping of these log levels to the concepts used by the underlying logging
 * system is implementation dependent. The implemention should ensure, though,
 * that this ordering behaves as expected.
 *
 * Configuration of the underlying logging system will generally be done external
 * to the Logging APIs, through whatever mechanism is supported by that system.
 * 
 * Note: The APIs of this package mimics Apache's commons-logging library.
 *
 * @package    	Commons
 * @subpackage	Logging
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html LGPL 3.0
 * @since      	Class available since v0.1
 */
interface Ddth_Commons_Logging_ILog {
    const SETTING_DEFAULT_LOG_LEVEL = 'default';
    
    const SETTING_PREFIX_LOGGER_CLASS = 'loggerClass.';
    
    /**
     * Initializes this ILog.
     * 
     * @param Ddth_Commons_Properties initializing properties
     * @throws {@link Ddth_Commons_Logging_LogConfigurationException LogConfigurationException}
     */
    public function init($prop);
    
    /**
     * Logs a message with debug log level.
     *
     * @param string
     * @param Exception
     */
    public function debug($message, $e = NULL);

    /**
     * Logs a message with error log level.
     *
     * @param string
     * @param Exception
     */
    public function error($message, $e = NULL);

    /**
     * Logs a message with fatal log level.
     *
     * @param string
     * @param Exception
     */
    public function fatal($message, $e = NULL);

    /**
     * Logs a message with info log level.
     *
     * @param string
     * @param Exception
     */
    public function info($message, $e = NULL);

    /**
     * Logs a message with trace log level.
     *
     * @param string
     * @param Exception
     */
    public function trace($message, $e = NULL);

    /**
     * Logs a message with warn log level.
     *
     * @param string
     * @param Exception
     */
    public function warn($message, $e = NULL);

    /**
     * Is debug logging currently enabled?
     *
     * @return bool
     */
    public function isDebugEnabled();

    /**
     * Is error logging currently enabled?
     *
     * @return bool
     */
    public function isErrorEnabled();

    /**
     * Is fatal logging currently enabled?
     *
     * @return bool
     */
    public function isFatalEnabled();

    /**
     * Is info logging currently enabled?
     *
     * @return bool
     */
    public function isInfoEnabled();

    /**
     * Is trace logging currently enabled?
     *
     * @return bool
     */
    public function isTraceEnabled();

    /**
     * Is warn logging currently enabled?
     *
     * @return bool
     */
    public function isWarnEnabled();
}
?>