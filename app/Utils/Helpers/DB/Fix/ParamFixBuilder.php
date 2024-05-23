<?php


namespace App\Utils\Helpers\DB\Fix;


use Illuminate\Database\Eloquent\Builder;

/**
 * Fix SQL query parameters limits.
 *
 * @package App\BPM\Model\Base
 */
class ParamFixBuilder extends Builder {


    protected array $parametersLimits = [
        'MySqlConnection'     => 65000,
        'SQLiteConnection'    => 900,
        'SqlServerConnection' => 2000,
    ];

    /**
     * @param array $models
     * @return array
     */
    public function eagerLoadRelations(array $models) {

        foreach ($this->parametersLimits as $class=>$limit){
            $classname = class_basename($this->query->getConnection());
            if ( $classname===$class && count($models)>$limit ){
                $models_result = [];
                foreach (array_chunk($models, $limit) as $chunk) {
                    $chunk_result = parent::eagerLoadRelations($chunk);
                    $models_result = array_merge($models_result,$chunk_result);
                }
                return $models_result;
            }
        }

        return parent::eagerLoadRelations($models);
    }

}
