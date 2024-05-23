##  Контроллер с регистрацией маршрута

Для создания контроллеров с автоматической регистрацией маршрутов
можно использовать класс `HControllerRouted`

Удобно, когда контроллеров очень много, то нет необходимости 
прописывать отдельно контроллеры и отдельно их маршруты.
Достаточно создать класс контроллера, указать путь
и маршрут будет зарегистрирован автоматически.

### Пример контроллера

Создаем контроллернаследованный от HControllerRouted 
и определяем свойство `$route = '/app/hello' ` и метод обработки
запроса `processGet()`. 
    
    class HelloWord extends HControllerRouted {
    
        // define route url
        protected static string $route = 'get/symbol';

        // Ссылка на иконки псевдографики
        public const R_BPM_SYMBOL = '/sym/{symbol}.svg';
    
        // process request
        public function processGet() {
            $s = $this->in()->paramStr('symbol');
            $svg = file_get_content(basename($s).'.svg');
            return response($svg)
                ->header("Content-Type", "image/svg+xml")
                ->header("Content-Length", strlen($svg))
                ->header("Cache-Control", "max-age=604800");
        }
    
    }

Чтобы все контроллеры автоматически регистирровались, необходимо
добавит одну строку в `routes/web.php`:

    // Register all routs for unauthorized users
    HRoute::registerAllRoutes(HControllerRouted::class);


Для формирования ссылки на данный контроллер используется стандартны `route()`:

    $link = route(HelloWord::$route);
    
    
В контроллере реализованы вспомогательные методы для работы с запросами.

    
