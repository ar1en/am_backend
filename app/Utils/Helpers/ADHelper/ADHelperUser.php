<?php


namespace App\Utils\Helpers\ADHelper;

/**
 * Class ADHelperUser
 *
 * Объект содержит информацию о пользователе LDAP
 *
 * @package App\Utils\Helpers\ADHelper
 */
class ADHelperUser {


    // AD attribute names
    public const P_DOMAIN = 'domain';
    public const P_SAMACCOUNTNAME = 'samaccountname';
    public const P_DISPLAYNAME = 'displayname';
    public const P_MAIL = 'mail';
    public const P_COMPANY = 'company';
    public const P_DEPARTMENT = 'department';
    public const P_TITLE = 'title';
    public const P_TELEPHONENUMBER = 'telephonenumber';
    public const P_DISTINGUISHEDNAME = 'distinguishedname';
    public const P_DN = 'dn';
    public const P_MAILNICKNAME = 'mailnickname';
    public const P_GIVENNAME = 'givenname';
    public const P_CN = 'cn';


    private array $properties = [];

    /**
     * Список свойств для запроса
     * @return string[]
     */
    public static function getProperties() {
        // define result fields
        return [
            self::P_DEPARTMENT,                                   // управление
            self::P_DISPLAYNAME,                                  // тоже ФИО
            self::P_TELEPHONENUMBER,                              // тел
            self::P_TITLE,                                        // должность
            self::P_CN,                                           // ФИО
            self::P_GIVENNAME,                                    // имя (и отчество)
            self::P_COMPANY,                                      // компания
            self::P_MAILNICKNAME,                                 //
            self::P_SAMACCOUNTNAME,                               // учетка
            self::P_MAIL,                                         // почта
            self::P_DN,                                           // dn
            self::P_DISTINGUISHEDNAME,                            //
            //'direct'.'reports', 	                              // подчиненные
            //'manager',
        ];

    }

    /**
     * ADHelperUser constructor.
     * @param array  $props
     * @param string $domain
     */
    public function __construct(array $props, string $domain) {
        $this->properties                 = $props;
        $this->properties[self::P_DOMAIN] = $domain;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getProperty(string $name) {
        return isset($this->properties[$name][0]) ? $this->properties[$name][0] : '';
    }

    /**
     * @return string
     */
    public function getUserName() {
        return $this->getProperty(self::P_SAMACCOUNTNAME);
    }

    /**
     * @return string
     */
    public function getDisplayName() {
        return $this->getProperty(self::P_DISPLAYNAME);
    }

    /**
     * @return string
     */
    public function getMail() {
        return $this->getProperty(self::P_MAIL);
    }

    /**
     * @return string
     */
    public function getOrganization() {
        return $this->getProperty(self::P_COMPANY);
    }

    /**
     * @return string
     */
    public function getDepartment() {
        return $this->getProperty(self::P_DEPARTMENT);
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->getProperty(self::P_TITLE);
    }

    /**
     * @return string
     */
    public function getTelephone() {
        return $this->getProperty(self::P_TELEPHONENUMBER);
    }

    /**
     * @return mixed
     */
    public function getDomain() {
        return $this->properties[self::P_DOMAIN];
    }


}
