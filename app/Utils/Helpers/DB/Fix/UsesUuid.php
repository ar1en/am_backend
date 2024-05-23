<?php


namespace App\Utils\Helpers\DB\Fix;



use App\Utils\Helpers\UUID\UUID;

/**
 * Trait UsesUuid
 * Model helper trait for UUID implementation.
 *
 * @package App\BPM\Model\Base
 */
trait UsesUuid {

    /**
     *
     * @noinspection PhpUnused
     */
    protected static function bootUsesUuid() {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = UUID::newV4(); // old style - (string)Str::uuid();
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     * @return bool
     * @noinspection PhpUnused
     */
    public function getIncrementing() {
        return false;
    }

    /**
     * @return string
     */
    public function getKeyType() {
        return 'string';
    }

}
