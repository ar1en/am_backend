
# Генерируемые классы Model 

Библиотека для управления моделями приложения на сонове метаданных БД.

Позволяет на основе созданных объектов в базе данных (таблиц и представления)
**сгенерировать и актуализировать объекты Model** для работы с БД 
в приложении Laravel (Eloquent). 
Это позволяет сократить трудоемкость разработки приложения в части 
работы с БД.   

Таким образом, чтобы в приложении появились объекты для работы 
с данными достаточно созать в БД необходимые объекты, описать связи между полями 
и выполнить консольную команду:

    php artisan make:dbModels <prefix_>

Где <prefix_> указывает на выбор объектов БД для которых будет сгенерирован код
Model классов для каждой таблицы и представления.
И после этойго в приложении можно использовать эти классы для доступа к данным.


### Генерация классов 
 
Утилита генерирует классы двух типов для каждой таблицы и представления
в БД, имена которых начинаются с `prefix_`:

* `MModel` - класс является наследником Model, код класса полностью
синхронизируется с концигурацией таблицы/представления.  
* `DModel` - наследуется от MModel и в дальнейшем не меняется скриптом. 
Разработчик может вносить в этот класс методы, 
расширяющие функции модели.
 
Сгенерированные классы размещаются в папках, 
указанных в настроках консольной команды.

**Наименования классов**

``MClass`` сгенерированный из таблицы или представления 
бедет иметь наименование, соответствующее ее/его имени. 
Наименование таблицы будет разделено на слова симовлом '_',
первая буква каждого слова будет преобразована в большую,
слова будут соеденены в одно наименование класса.
Если таблица называется  `aaa_bbb_ccc_ddd` и префикс объектов `aaa_`,
то имя класса MModel будет иметь вид `MBbbCbbDbb`,
и `DBbbCccDdd` для имениа класса DModel.

Например для таблицы 'prefix_events' будет сгенеирован класс:

    class MEvents extends DBClass {
        ...
    }
 
 
Для каждого `MClass`генерируется `DClass` наследованный от `MClass`.
Пример:  

    /**
     * Class DEvents
     * Data class for db table bpm_events.
     * @package App\BPM\Model\DBClasses\Data
     */
    class DEvents extends MEvents {
    ...

 
**Содержание классов MModel**  

Для классов ``MModel`` будут сформированы:
 
* поля объекта соответствующие колонкам таблицы с phpdoc
* константы с именами колонок таблиц, имена констант имею префикс `F_...` 
(и `FT_..` для контант, содержащих и имя таблиц, например `evetns.title`)
* константы с именами колонок в формате json, с прекисом  `FJ_...` 
(конвертируется `field_value_exam` to `fieldValueExam`)
* property `protected $table = 'table_name';`
* property `protected $fillable = [...];`
* property `protected $visible = [...];`
* property `protected $jsonable = [...];` - list of visible fields
with json format names 
* методы `belongsTo()` и `hasMany()`

Пример сгенерированного свойства в phpdoc для класса:
    
    /**
    ...
    * @property  string       id             [1] type:uuid      !NULL PRIMARY
    ...
    */
  
Пример сгенерированных констант с именами полей таблиц:

    class MEvents extends DBClass {
    
    
        const  F_ID            = 'id';
        const  FT_ID           = 'events.id';
        const  FJ_ID           = 'id';
        const  F_DATE_EVENT    = 'date_event';
        const  FT_DATE_EVENT   = 'events.date_event';
        const  FJ_DATE_EVENT   = 'dateEvent';
        const  F_TITLE         = 'title';
        const  FT_TITLE        = 'events.title';
        const  FJ_TITLE        = 'title';
        ...


Пример генерации полей для Model :

    // настройки преобразования данных в json
    public static array $jsonable = [
        MEvents::FJ_ID           => [MEvents::F_ID, null, "string"],
        MEvents::FJ_DATE_EVENT   => [MEvents::F_DATE_EVENT, null, "string"],
        ...
    ];

		protected $visible = [
			self::F_ID,
			self::F_DATE_EVENT,
			self::F_TITLE,
			...
		];

		protected $fillable = [
			 self::F_DATE_EVENT,
			 self::F_TITLE,
			 ...
		]; 


Пример генерации методов отношений, формироуется на основании constraints:

        /**
         * @return DModel|BelongsTo
         */
        public function relModelId(){
            return $this->belongsTo(
                DModel::class,
                self::F_MODEL_ID, 
                DModel::F_ID);
        }


        /**
         * @return DWorkspace|BelongsTo
         */
        public function relWorkspaceId(){
            return $this->belongsTo(
                DWorkspace::class,
                self::F_WORKSPACE_ID, 
                DWorkspace::F_ID);
        }


### Расширение функционала классов DModel 

Для добавления функций и свойств моделей необходимо 
из вносить в класс DModel. Например, добавлять расширенный перечень полей,
методы выборки данных и обновления, менять настройки конвертации в json...


### Консаольная команда

Консольная команда генерирует классы моделей. 
    
    php artisan make:dbModels prefix_

Для созадания консольной команды необходимо:
 1. Создать класс коносольной команды на базе класса `CommandMakeDBModels`.
 2. Указать параметр - подключение к БД
 3. Указать имя базового класса Model (например AppBaseModel)
 от которого будут наследоваться все модели и который будет определять путь 
 размещения генерируемых файлов.
 
 Пример:
   

    namespace App\Console\Commands;
    ...    
    class CommandMakeDbClasses extends CommandMakeDBModels {
        public function getConnectionName(): string {
            return config('app.db_connection');
        }
        public function getDBModelBaseClassName(): string {
            return AppBaseModel::class;
        }
    }
