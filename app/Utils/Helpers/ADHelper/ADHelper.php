<?php

/**
 * Class ADHelper
 */


namespace App\Utils\Helpers\ADHelper;


use Exception;
use Throwable;


/**
 * Class ADHelper
 *
 * Вспомогательный класс для работы с ActiveDirectory.
 * Методы подключения к LDAP, загрузку информации о пользователях и проверки авторизации.
 *
 * @package App\Helpers
 */
class ADHelper {

    private ADHelperConnection $connectionHelper;

    /**
     * ADHelper constructor.
     */
    public function __construct() {
        $this->connectionHelper = new ADHelperConnection();
    }


    /**
     * Only chars and nums
     *
     * @param string $name
     * @return string
     */
    public static function sanitizeADQueryText($name) {
        return preg_replace(/** @lang text */ "/[^А-Яа-яa-zA-Z0-9_\*\.\ -]/u", "", $name);
    }

    /**
     * Make user name with domain name
     * @param string $user
     * @return array
     */
    public function getDomainUser(string $user): array {
        // define domain of user
        $arr = explode('\\', $user);
        if (count($arr) == 2) {
            $domain = $arr[0];
            $user   = $arr[1];
        } else {
            // if user domain not defined - use default domain name
            $domain = $this->connectionHelper->getDefaultDomainName();
        }
        return [$user, $domain];
    }


    /**
     * Test username and pass with LDAP
     * @param $user
     * @param $pass
     * @return bool
     */
    public function authUser($user, $pass) {

        try {

            // define domain of user
            list($user, $domain) = $this->getDomainUser($user);

            // make connection
            $connection = $this->connectionHelper->makeConnection(null, $domain, $user, $pass);

            // try get user properties
            $properties = self::getUserProperties($user, 10, null, true, $connection);

            // has properties - ok
            return (is_array($properties) && count($properties) > 0);

        } catch (Throwable $ex) {
            return false;
        }

    }

    /**
     * Return true if user exists in LDAP.
     * @param string $account
     * @return bool
     * @throws Exception
     */
    public function userExists($account) {

        list($userName, $domain) = $this->getDomainUser($account);
        $connection        = $this->connectionHelper->makeConnection(null, $domain);
        $userNameSanitized = self::sanitizeADQueryText($userName);
        $search            = ldap_search(
            $connection,
            $this->connectionHelper->getBaseADDomainName($domain),
            '(&(objectCategory=Person)(' . ADHelperUser::P_SAMACCOUNTNAME . '=' . $userNameSanitized . '))',
            array(),
            0,
            0);

        return $search ? true : false;

    }


    /**
     *    Find users info in AD.
     *    fields
     *    - null for username find
     *    - true for FIO find
     *    - array('department') - for find by department field
     *
     * @param string      $searchText
     * @param int         $maxEntriesCount
     * @param array|null  $searchByFields - list of fields search in or true for 'samaccountname' or false for 'fio'
     * @param bool        $strictly       - strictly or with '*' at end of string
     * @param string|null $domain         - search in domain
     * @return ADHelperUser[]
     * @throws Exception
     */
    public function getUserProperties(string $searchText, int $maxEntriesCount = 100,
                                      $searchByFields = null, $strictly = false, $domain = null) {

        $domain = $domain ? $domain : $this->connectionHelper->getDefaultDomainName();

        // define search string - min name length 3 characters
        if (strlen($searchText) < 3) {
            return [];
        }

        // make query filter string
        $propertySearchStr = $this->getSearchQuery($searchByFields, $searchText, $strictly); // " (|{$propertySearchStr}) ";
        $ldapSearchString  = '(&(objectCategory=Person)(!(userAccountControl:1.2.840.113556.1.4.803:=2))' . $propertySearchStr . ')';

        // define result fields
        $resultFields = ADHelperUser::getProperties();

        // make ldap search string for active users - exclude inactive records
        // !(userAccountControl:1.2.840.113556.1.4.803:=2)
        $connection = $this->connectionHelper->makeConnection(null, $domain);
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        $search = ldap_search(
            $connection,
            $this->connectionHelper->getBaseADDomainName($domain),
            $ldapSearchString, $resultFields, 0, $maxEntriesCount);

        // get ldap entries
        $entries = $search ? ldap_get_entries($connection, $search) : false;
        if (!$entries) {
            return array();
        }

        // fetch ldap user entries by fields list
        $res = [];
        for ($i = 0; $i < $entries['count'] && $i <= $maxEntriesCount; $i++) {
            $res[] = new ADHelperUser($entries[$i], $domain);
        }
        return $res;
    }


    /**
     * @param bool|array|null $searchByFields
     * @param string          $searchText
     * @param bool            $strictly
     * @return string
     */
    private function getSearchQuery($searchByFields, string $searchText, bool $strictly = true) {
        // define find fields
        if ($searchByFields === null || !is_array($searchByFields) || count($searchByFields) <= 0) {
            $searchByFields = [ADHelperUser::P_SAMACCOUNTNAME];//'sAMAccountName','department','company','title','mail');
        }
        if ($searchByFields === true) {
            // если поиск по тексту - то ищем только по ФИО
            $searchByFields = [ADHelperUser::P_CN];//,'department','company','title','mail');
        }

        // add search conditions for where clause
        $propertySearchStr = "";
        foreach ($searchByFields as $field) {
            $propertySearchStr .= "("
                . $field . "="
                . self::sanitizeADQueryText($searchText)
                . ($strictly ? "" : "*") . ")";
        }
        return " (|{$propertySearchStr}) ";
    }


    /**
     * Get users AD Groups (limited by list)
     * @param string        $account
     * @param string[]|null $enabledADRoles
     * @return array
     * @throws Exception
     */
    public function getMemberOf(string $account, ?array $enabledADRoles = null) {

        // search groups
        list($userName, $domain) = $this->getDomainUser($account);
        $adConnection = $this->connectionHelper->makeConnection(null, $domain);
        $baseName     = $this->connectionHelper->getBaseADDomainName($domain);
        $filter       = '(&(objectCategory=Person)(sAMAccountName=' . self::sanitizeADQueryText($userName) . '))';
        $search       = ldap_search($adConnection, $baseName, $filter, ['memberOf'], 0, 1000);
        $roles        = $search ? ldap_get_entries($adConnection, $search) : [];

        // make groups list
        $res = [];
        $cnt = isset($roles[0]['memberof']['count']) ? $roles[0]['memberof']['count'] : 0;
        for ($i = 0; $i < $cnt; $i++) {
            $v = $roles[0]['memberof'][$i];
            if ($enabledADRoles == null || array_key_exists($v, $enabledADRoles)) {
                $res[] = $v;
            }
        }
        return $res;
    }


    /**
     * Find domain user properties by user name
     * @param string $account
     * @return ADHelperUser | null
     * @throws ADHelperException
     * @throws Exception
     */
    public function getADUserData($account) {

        list($userName, $domain) = $this->getDomainUser(trim($account));

        // get AD record
        $up = $this->getUserProperties($userName, 2, true, true, $domain);
        if (empty($up)) {
            return null;
        }

        // found more then one records
        if (count($up) > 1) {
            throw new ADHelperException("Found more then 1 AD record for [{$userName}]!");
        }

        // test user name and domain name
        if (strtolower($userName) !== strtolower($up[0]->getDisplayName())) {
            throw new ADHelperException("User not found (names differ)!");
        }

        return $up[0];
    }

}
