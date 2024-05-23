<?php


namespace App\Utils\Helpers\DB\Fix;


use Illuminate\Database\Query\Builder;

/**
 * Trait ParamFixLimit
 * @package App\BPM\Model\Base
 */
trait ParamFixLimit {
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param Builder $query
     * @return ParamFixBuilder
     * @noinspection PhpUnused
     */
    public function newEloquentBuilder($query) {
        return new ParamFixBuilder($query);
    }
}
