<?php


namespace App\Utils\Helpers\DB\Model;


use App\Utils\Helpers\DB\AppDB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;


/**
 * Class MBaseBulk
 *
 * Add bulk operations for Model classes.
 *
 * @package App\Utils\Helpers\DB\Model
 */
abstract class MBaseBulk extends Model {


    /**
     * Compare lists of Model objects (new and old) and exec update operations.
     *
     * @param AppDB $db
     * @param       $newList
     * @param       $oldList
     * @return int
     */
    public static function bulkCompareAndUpdate(AppDB $db, $newList, $oldList) {
        return static::bulkUpdates($db, static::bulkCompare($newList, $oldList));
    }

    /**
     * Compare new and old list for updates.
     *
     * @param $newList
     * @param $oldList
     * @return array[] - ['n'=>[...objects to be inserted], 'u'=>[...objects to be updated], 'd'=>[...object to be
     *                 deleted]]
     */
    public static function bulkCompare($newList, $oldList) {

        $index = [];

        $indexNew = [];
        $indexDel = [];
        $indexUpdate = [];

        foreach ($newList as $item) {
            $index[$item->getKey()] = [$item, null];
        }

        foreach ($oldList as $item) {
            if (isset($index[$item->getKey()])) {
                $index[$item->getKey()][1] = $item;
            } else {
                $index[$item->getKey()] = [null, $item];
            }
        }

        foreach ($index as $key => $item) {
            if ($item[0] === null) {
                $indexDel[] = $key;
                continue;
            }
            if ($item[1] === null) {
                $indexNew[] = $item[0];
                continue;
            }
            $item[1]->fill($item[0]->toArray());
            $indexUpdate[] = $item[1];
        }

        return ['n' => $indexNew, 'u' => $indexUpdate, 'd' => $indexDel];

    }

    /**
     * Update lists
     * @param AppDB $db
     * @param array $upd - array = ['u'=>[], 'd'=>[], 'n'=>[]]
     * @return int
     */
    public static function bulkUpdates(AppDB $db, array $upd) {
        $cnt = static::bulkDelete($db, $upd['d']);
        $cnt += static::bulkUpdate($db, $upd['u']);
        $cnt += static::bulkInsert($db, $upd['n']);
        return $cnt;
    }

    /**
     * Добавляет записи в пакетном режиме (с учтом ограничений MS SQL сервера)
     * @param AppDB   $db
     * @param MBase[] $objects
     * @return int
     */
    public static function bulkInsert(AppDB $db, $objects) {

        if ($objects instanceof Collection) {
            $objects = $objects->all();
        }

        // is empty - exit
        if (!$objects || empty($objects) || (count($objects)<=0) ) {
            return 0;
        }

        /** @var Builder $query */
        $query = static::q($db);


        // sql server не принимает более 2000 параметров,
        // поэтому надо будет добавлять кусками по 1000 параметров
        // количество параметров
        $params_count = count($objects[0]->getFillable());

        // по сколько строк обновляем
        $by_rows = round(1000 / $params_count);

        $start_index = 0;
        while ($start_index < count($objects)) {

            // обновляем первый пакет
            $pack = array_slice($objects, $start_index, $by_rows);
            $data = array_map(function ($model) { return $model->getAttributes(true); }, $pack);
            $query->insert($data);

            $start_index += $by_rows;
        }

        return count($objects);

    }

    /**
     * Добавляет записи в пакетном режиме (с учтом ограничений MS SQL сервера).
     * @param AppDB   $db
     * @param MBase[] $objects
     * @return int
     */
    public static function bulkUpdate(AppDB $db, $objects) {

        foreach ($objects as $object) {
            $object->setConnection($db->getConnectionName());
            $object->save();
        }

        return count($objects);

    }

    /**
     * @param AppDB $db
     * @param array $list - list of id or Models
     * @return int
     */
    public static function bulkDelete(AppDB $db, $list) {
        if (!$list || count($list) <= 0) {
            return 0;
        }
        $idList = array_map(function ($el) {
            return ($el instanceof MBase) ? $el->getKey() : $el;
        }, $list);
        return static::q($db)->whereIn(static::s_getPrimaryKey(), $idList)->delete();
    }

}
