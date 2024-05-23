<?php


namespace App\Utils\Helpers\ADHelper;


use App\PVU\Exceptions\BPM\EActiveDirectory;

/**
 * AD Connection helper
 *
 * Реализует подключения к LDAP.
 * Настройки подключения должны быть указаны в файле конфигурации - adconfig.php.
 *
 * @package App\Utils\Helpers\ADHelper
 */
class ADHelperConnection {


    // AD configuration parameters
    public const APP_CONFIG_NAME = 'adconfig';
    public const CFG_DOMAIN_CONNECTIONS = 'domainConnections';
    public const CFG_DOMAIN_DEFAULT_NAME = 'domainDefaultName';
    public const CFG_ROOT_USER = 'rootUser';
    public const CFG_ROOT_PASSWORD = 'rootPassword';
    public const CFG_LDAP_BASE_DN = 'ldapBaseDN';
    public const CFG_HOSTNAME = 'hostname';
    public const CFG_PORT = 'port';

    /** @var resource[] collect AD connections */
    protected static array $connections = array();


    /**
     * Return default domain name
     *
     * @return string|null
     */
    public function getDefaultDomainName(): ?string {
        return config(self::APP_CONFIG_NAME . '.' . self::CFG_DOMAIN_DEFAULT_NAME);
    }


    /**
     * Return AD connection configuration or throw Exception
     * @param string $domainName
     * @return array
     * @throws EActiveDirectory
     */
    public function getDomainConfig($domainName) {
        $domain   = strtolower($domainName);
        $adConfig = config(self::APP_CONFIG_NAME);
        if (!isset($adConfig[self::CFG_DOMAIN_CONNECTIONS])) {
            throw new EActiveDirectory("Domain configuration is empty: [{$domain}]!");
        }
        if (!isset($adConfig[self::CFG_DOMAIN_CONNECTIONS][$domain])) {
            throw new EActiveDirectory("Domain configuration is empty for domain: [{$domain}]!");
        }
        if (!is_array($adConfig[self::CFG_DOMAIN_CONNECTIONS][$domain])) {
            throw new EActiveDirectory("Domain configuration is incorrect (not array) for domain: [{$domain}]!");
        }
        $cfg = $adConfig[self::CFG_DOMAIN_CONNECTIONS][$domain];
        if (true !== ($err = $this->isValidConfig($cfg))) {
            throw new EActiveDirectory("Domain [{$domain}] configuration is incorrect! {$err}");
        }
        return $cfg;
    }


    /**
     * Validate AD configuration.
     * @param array $cfg
     * @return bool|string
     */
    private function isValidConfig($cfg) {

        if (!is_array($cfg)) {
            return "Config is not array!";
        }

        $props = [
            self::CFG_ROOT_USER,
            self::CFG_ROOT_PASSWORD,
            self::CFG_LDAP_BASE_DN,
            self::CFG_HOSTNAME,
            self::CFG_PORT
        ];

        foreach ($props as $p) {
            if (!array_key_exists($p, $cfg) || empty($cfg[$p])) {
                return "Value is not string [{$p}].";
            }
        }

        return true;

    }

    /**
     * Get LDAP base domain name for domain.
     * @param $domain
     * @return mixed
     * @throws EActiveDirectory
     */
    public function getBaseADDomainName($domain) {
        $adConfig = $this->getDomainConfig($domain);
        return $adConfig[self::CFG_LDAP_BASE_DN];
    }

    /**
     * Создает подключение к LDAP в соответствии с конфигурацией
     * @param array       $config
     * @param string      $domain
     * @param string|null $user
     * @param string|null $pass
     * @return false|resource
     * @throws EActiveDirectory
     */
    public function makeConnection(?array $config = null, ?string $domain = null, ?string $user = null, ?string $pass = null) {

        // check domain
        if ($domain == null) {
            $domain = $this->getDefaultDomainName();
        }

        // make connection config
        if ($config == null) {
            $config = self::getDomainConfig($domain);
            if (!is_array($config)) {
                throw new EActiveDirectory("AD Config not defied for [{$domain}]");
            }
        }

        // make auth connection
        if ($user !== null && $pass !== null) {
            $config[self::CFG_ROOT_USER]     = $user;
            $config[self::CFG_ROOT_PASSWORD] = $pass;
        }

        // get opened LDAP controller IP
        $hostIP = $this->getADControllerIP($config[self::CFG_HOSTNAME], $config[self::CFG_PORT]);
        if (!$hostIP) {
            throw new EActiveDirectory('Can`t resolve AD host ' . $domain);
        }

        // make connection
        $connection = ldap_connect($hostIP, $config[self::CFG_PORT]);
        if (!$connection) {
            throw new EActiveDirectory('Can`t resolve AD connection to host ' . $domain);
        }

        // set options
        if (!ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3)) {
            throw new EActiveDirectory('Unable to set LDAP protocol version for domain' . $domain);
        }
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($connection, LDAP_OPT_SIZELIMIT, 1000);

        // auth
        $bind = @ldap_bind($connection, $config[self::CFG_ROOT_USER], $config[self::CFG_ROOT_PASSWORD]);
        if (!$bind) {
            throw new EActiveDirectory('Can`t bind AD connection to host ' . $domain);
        }

        return $connection;

    }


    /**
     * Check is LDAP port is opened for LDAP domain name
     *
     * @param $domainName
     * @param $port
     * @return false|string
     */
    private function getADControllerIP(string $domainName, int $port) {
        /** @var string[] $dcList */
        $dcList = gethostbynamel($domainName);
        foreach ($dcList as $dc) {
            if (self::servicePing($dc, $port) === true) {
                return $dc;
            }
        }
        return false;
    }


    /**
     * Check is LDAP port is opened for LDAP domain name
     * @param string $host
     * @param int    $port
     * @param int    $timeout - sec
     * @return bool
     */
    private function servicePing($host, $port = 389, $timeout = 5): bool {
        $errStr = '';
        $op     = fsockopen($host, $port, $errno, $errStr, $timeout);
        if (!$op) {
            return false;   //DC is N/A
        } else {
            fclose($op);    //explicitly close open socket connection
            return true;    //DC is up & running, we can safely connect with ldap_connect
        }
    }


}
