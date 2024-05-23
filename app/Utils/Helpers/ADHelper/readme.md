# ADHelper

Набор вспомогательных rлассов для взаимодействия с LDAP.


Для настройки необходимо создать файл конфигурации `config/adconfig.php`

    'domainConnections' => [
        'rosneft' => [  // rosneft domain parameters
            'rootUser'     => env_require('AD_USER'),
            'rootPassword' => env_require('AD_PASS'),
            'hostname'     => env_require('AD_HOSTNAME'),
            'port'         => env_require('AD_PORT'),
            'ldapBaseDN'   => env_require('AD_BASEDN'),
        ],

Для работы с хелпером надо создать объект:
    
    $adHelper = new ADHelper();

Для проверки авторизации пользователя: 

    if ( $adHelper->authUser($user, $pass) ) { ... 

Для чтения информации о пользователе:

    $searchText = 'username';
    $userList = $adHelper->getUserProperties($searchText,100,null,true);


Или более общий подход:

    $user = $adHelper->getADUserData("domain\\user");

Можно искат ьинформацию о пользователях по маске:

    $searchText = 'partOfUserNameOrEmail'
    $userList = getUserProperties($searchText, 100,null,false);

Также можно получить перечень групп, в которые входит пользователь:

    $groups = $adHelper->getMemberOf($userAccount);


Остальное в phpdoc
