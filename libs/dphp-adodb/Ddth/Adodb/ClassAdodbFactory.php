<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * ADOdb connection factory.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Adodb
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassAdodbFactory.php 185 2008-09-12 08:24:22Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/** */
require_once 'adodb-exceptions.inc.php';
require_once 'adodb.inc.php';

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
 * ADOdb connection factory.
 *
 * @package    	Adodb
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1.4
 * @since      	Class available since v0.1
 */
class Ddth_Adodb_AdodbFactory implements Ddth_Adodb_IAdodbFactory {
    private static $cacheInstances = Array();
    
    private $LOGGER;

    /**
     * Gets an instance of Ddth_Adodb_AdodbFactory.
     *
     * See: {@link Ddth_Adodb_AdodbConfig configuration file format}.
     *
     * @param string name of the configuration file (located in
     * {@link http://www.php.net/manual/en/ini.core.php#ini.include-path include-path})
     * @return Ddth_Adodb_AdodbFactory
     * @throws {@link Ddth_Adodb_AdodbException AdodbException}
     */
    public static function getInstance($configFile=NULL) {
        if ( $configFile === NULL ) {
            return self::getInstance(self::DEFAULT_CONFIG_FILE);
        }
        if ( !isset(self::$cacheInstances[$configFile]) ) {
            $config = Ddth_Adodb_AdodbConfig::loadConfig($configFile);
            $instance = new Ddth_Adodb_AdodbFactory($config);
            self::$cacheInstances[$configFile] = $instance;
        }
        return self::$cacheInstances[$configFile];
    }

    /**
     * Holds an instance of Ddth_Adodb_AdodbConfig.
     *
     * @var Ddth_Adodb_AdodbConfig
     */
    private $config = NULL;

    /**
     * Constructs a new Ddth_Adodb_AdodbFactory object.
     *
     * @param Ddth_Adodb_AdodbConfig
     */
    public function __construct($config=NULL) {
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog('Ddth_Adodb_AdodbFactory');
        if ( $config === NULL || $config instanceof Ddth_Adodb_AdodbConfig ) {
            $msg = 'NULL configuration object or not an instance of Ddth_Adodb_AdodbConfig, load from default configuration file.';
            $this->LOGGER->info($msg);
            $config = Ddth_Adodb_AdodbConfig::loadConfig(self::DEFAULT_CONFIG_FILE);
        }
        $this->config = $config;
    }

    /**
     * Gets configuration object.
     *
     * @return Ddth_Adodb_AdodbConfig
     */
    protected function getConfig() {
        return $this->config;
    }

    /**
     * Gets an ADOdb connection.
     *
     * @param bool indicates that if a transaction is automatically started
     * @return ADOConnection an instance of ADOConnection, NULL is returned if
     * the connection can not be created
     */
    public function getConnection($startTransaction=false) {
        $dsn = $this->config->getUrl();
        $conn = NULL;
        if ( $dsn !== NULL && $dsn !== '' ) {
            $conn = NewADOConnection($dsn);
        }
        if ( $conn === NULL || $conn === false ) {
            $driver = $this->config->getDriver();
            $host = $this->config->getHost();
            $user = $this->config->getUser();
            $password = $this->config->getPassword();
            $database = $this->config->getDatabase();
            
            $conn = NewADOConnection($driver);
            if ( $conn !== NULL || $conn !== false ) {
                $conn->connect($host, $user, $password, $database);
            }
        }

        if ( $conn === NULL || $conn === false ) {
            return NULL;
        }

        foreach ( $this->config->getSetupSqls() as $sql ) {
            //run setup sqls
            $conn->Execute($sql);
        }

        if ( $startTransaction ) {
            $conn->StartTrans();
        }
        return $conn;
    }

    /**
     * Closes an ADOConnection
     *
     * @param ADOConnection
     * @param bool
     */
    public function closeConnection($conn, $hasError=false) {
        if ( $conn !== NULL ) {
            if ( $conn->hasTransactions ) {
                if ( $hasError ) {
                    $conn->FailTrans();
                }
                $conn->CompleteTrans();
            }
            $conn->close();
        }
    }
}
?>