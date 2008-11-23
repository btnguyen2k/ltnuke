<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Adodb implementation of Configuration Manager.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		EhConfig
 * @subpackage  Adodb
 * @author		NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @id			$Id: ClassIAdodbFactory.php 144 2008-02-29 15:34:04Z btnguyen2k@gmail.com $
 * @since      	File available since v0.1
 */

/**
 * Adodb implementation of Configuration Manager.
 *
 * Configuration file format: configurations are stored in
 * .properties file; supported configuration properties as of v0.1:
 * <code>
 * # Name of the Adodb factory class where the configuration manager retrieves ADOdb connections
 * # Default value is Ddth_Adodb_AdodbFactory
 * ehconfig.adodb.factoryClass=Ddth_Adodb_AdodbFactory
 *
 * # ADOdb-SQL to query a configuration by key (domain & name)
 * # Binding order: domain, name
 * ehconfig.adodb.sql.getConfig=SELECT conf_value AS conf_value FROM tableName WHERE conf_domain=:domain AND conf_name=:name
 *
 * # ADOdb-SQL to update a configuration by key (domain & name)
 * # Binding order: domain, name
 * ehconfig.adodb.sql.updateConfig=UPDATE tableName SET conf_value=:value WHERE conf_domain=:domain AND conf_name=:name
 *
 * # ADOdb-SQL to create a new configuration
 * # Binding order: domain, name, value
 * ehconfig.adodb.sql.createConfig=INSERT INTO tableName (conf_domain, conf_name, conf_value) VALUES (:domain, :name, :value)
 *
 * # ADOdb-SQL to delete a configuration by key (domain & name)
 * # Binding order: domain, name
 * ehconfig.adodb.sql.deleteConfig=DELETE FROM tableName WHERE conf_domain=:domain AND conf_name=:name
 *
 * # ADOdb-SQL to delete all configurations within a domain
 * # Binding order: domain
 * ehconfig.adodb.sql.deleteAllConfigsInDomain=DELETE FROM tableName WHERE conf_domain=:domain
 * </code>
 *
 * @package    	EhConfig
 * @subpackage  Adodb
 * @author     	NGUYEN, Ba Thanh <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
class Ddth_EhConfig_Adodb_ConfigManager extends Ddth_EhConfig_ConfigManager {

    const DEFAULT_ADODB_FACTORY_CLASS = 'Ddth_Adodb_AdodbFactory';

    const PROPERTY_ADODB_FACTORY_CLASS = 'ehconfig.adodb.factoryClass';

    const PROPERTY_SQL_GET_CONFIG = 'ehconfig.adodb.sql.getConfig';

    const PROPERTY_SQL_CREATE_CONFIG = 'ehconfig.adodb.sql.createConfig';

    const PROPERTY_SQL_DELETE_CONFIG = 'ehconfig.adodb.sql.deleteConfig';

    const PROPERTY_SQL_DELETE_ALL_CONFIGS_IN_DOMAIN = 'ehconfig.adodb.sql.deleteAllConfigsInDomain';

    private $adodbFactory = NULL;

    private $adodbConn = NULL;

    /**
     * Constructs a new Ddth_EhConfig_Adodb_ConfigManager object.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * {@see Ddth_EhConfig_ConfigManager::init()}
     */
    public function init($props) {
        parent::init($props);
    }

    /**
     * {@see Ddth_EhConfig_ConfigManager::createConfig()}
     */
    public function createConfig($config) {
        if ( $config === NULL || !($config instanceof Ddth_EhConfig_Config) ) {
            return;
        }
        $conn = $this->getAdodbConnection();
        $sql = $this->getProperty(self::PROPERTY_SQL_CREATE_CONFIG);
        $params = Array($config->getKey()->getDomain(), $config->getKey()->getName(), $config->getValue());
        $sql = str_replace(':domain', $conn->Param('domain'), $sql);
        $sql = str_replace(':name', $conn->Param('name'), $sql);
        $sql = str_replace(':value', $conn->Param('value'), $sql);
        $conn->Execute($sql, $params);
        $this->closeAdodbConnection($conn);
    }

    /**
     * {@see Ddth_EhConfig_ConfigManager::deleteAllConfigsInDomain()}
     */
    public function deleteAllConfigsInDomain($domain) {
        $conn = $this->getAdodbConnection();
        $sql = $this->getProperty(self::PROPERTY_SQL_DELETE_ALL_CONFIGS_IN_DOMAIN);
        $sql = str_replace(':domain', $conn->Param('domain'), $sql);
        $rs = $conn->Execute($sql, Array($key->getDomain()));
        $this->closeAdodbConnection($conn);
    }

    /**
     * {@see Ddth_EhConfig_ConfigManager::deleteConfig()}
     */
    public function deleteConfig($key) {
        if ( $key === NULL || !($key instanceof Ddth_EhConfig_ConfigKey ) ) {
            return;
        }
        $conn = $this->getAdodbConnection();
        $sql = $this->getProperty(self::PROPERTY_SQL_DELETE_CONFIG);
        $sql = str_replace(':domain', $conn->Param('domain'), $sql);
        $sql = str_replace(':name', $conn->Param('name'), $sql);
        $rs = $conn->Execute($sql, Array($key->getDomain(), $key->getName()));
        $this->closeAdodbConnection($conn);
    }

    /**
     * {@see Ddth_EhConfig_ConfigManager::getConfig()}
     */
    public function getConfig($key) {
        $conn = $this->getAdodbConnection();
        $conn->SetFetchMode(ADODB_FETCH_NUM);
        $sql = $this->getProperty(self::PROPERTY_SQL_GET_CONFIG);
        $sql = str_replace(':domain', $conn->Param('domain'), $sql);
        $sql = str_replace(':name', $conn->Param('name'), $sql);
        $result = NULL;
        $rs = $conn->Execute($sql, Array($key->getDomain(), $key->getName()));
        if ( !$rs->EOF ) {
            $value = $rs->fields[0];
            $result = new Ddth_EhConfig_Config($key, $value);
        }
        $this->closeAdodbConnection($conn);
        return $result;
    }

    /**
     * Gets ADOdb connection factory.
     *
     * @return Ddth_Adodb_IAdodbFactory
     */
    protected function getAdodbFactory() {
        if ( $this->adodbFactory === NULL ) {
            $clazz = $this->getProperty(self::PROPERTY_ADODB_FACTORY_CLASS, self::DEFAULT_ADODB_FACTORY_CLASS);
            $this->adodbFactory = new $clazz();
        }
        return $this->adodbFactory;
    }

    /**
     * Gets an ADOdb connection
     *
     * @return ADOConnection
     */
    protected function getAdodbConnection() {
        if ( $this->adodbConn === NULL ) {
            $this->adodbConn = $this->getAdodbFactory()->getConnection(true);
        }
        return $this->adodbConn;
    }

    /**
     * Closes the ADOdb connection.
     *
     * @param ADOConnection
     */
    protected function closeAdodbConnection($conn=NULL) {
        if ( $conn === NULL ) {
            $conn = $this->adodbConn;
        }
        if ( $conn !== NULL ) {
            $this->getAdodbFactory()->closeConnection($conn);
            if ( $conn === $this->adodbConn ) {
                $this->adodbConn = NULL;
            }
        }
    }
}
?>
