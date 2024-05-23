
## Генерация API классов для TypeScrypt

Применение команд:

`` php artisan make:jsonData `` - 
созадает TypeScript файлы типов объектов входов и выходов.

`` php artisan make:ts `` - 
создает TypeScript объекты Storage для 
доступа к данным через api

Обеспечивает генерацию TypeScrypt классов, применяемых при взаимодействии 
клиентской и серверной части через API. Сгенерированные классы помещаются в папку: 
    
    \resources\app\remoteTypes\cls\
    
#### Генерация типов объектов
    
Для каждого php классса, наследованного от класса 
``JsonData`` и класса ``JsonModel``,  формируется файл .ts который содержит 
преобразованный класс. Преобразованные классы будут иметь аналогичные
имена, но с префиксом ``IApi...``. Так, для класса ``AData`` будет сформирован 
файл ``IApiAData.ts``.

#### Генерация объектоа для api

Кроме того, для каждого ``action`` метода класса контроллера API, унаследованного 
от ``ControllerApi``, генерируется файл ``IApi<ClassName>.ts``,
в котором создается функция с паратрами fetch запросов: 

    export const GetApiAppWorkspaceList = (root:string, data: IApiJWorkspaceListParameter) => {
        ...
    }   

И создаются классы доступа к API сервера: 

    export class ApiStorageAppWorkspaceList extends ApiAction<IApiJWorkspaceGetParameter,IApiJWorkspace> {
        ...
    }


### Концепция архитектуры контроллера API 

Для взаимодействия клиента с сервером создается один общий http контроллер,
через который серверу направляются запросы. В нашем случае
сздается контроллер ``ControllerApiRouter``, который в маршрутах прописыватся 
по адресу ``/api/do``:  

    class ControllerApiRouter extends ControllerApiAuthorized {
    
        use ControllerHttpPostTrait;
    
        const FUNCTION_ACTION_PREFIX = 'action';
        const I_ACTION               = 'action';
        const I_METHOD               = 'method';
        const I_DATA                 = 'data';
        const C_ROUTE_URL            = '/do';
    
        protected static string $route = self::C_ROUTE_URL;

        public function processPost() {...}
        
    }

Контролер обрабатывает POST запросы и через параметры ``action`` и ``method`` опредляет
какой контроллер выполнять. Все API контроллеры нследуются от классов 
``ControllerApiAuthorized`` или ``ControllerApiNotAuthorized``.


### Класс JsonData и JsonModel

Входные и выходные данные API контролера должны быть сериализуемы.
Для этого все используются объекты, наследуемые от класса JsonData или JsonModel.
Для всех наследуюмых класса генерируются TypeScript классы для ипользования в лиентской 
части приложения. 
 
