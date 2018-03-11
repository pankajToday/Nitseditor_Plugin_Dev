<?php

namespace Nitseditor\System\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class AbstractModel extends Model
{
    /**
     *  Defining connection for database
     *
     * @var string
     **/
    protected $connection='mysql';

    /**
     *  Defining connection for database
     *
     * @var array
     **/
    protected $nits_encryption = [];

    protected static function boot()
    {
        parent::boot();

        // Decrypt the attributes.
        static::retrieved(function ($instance) {
            foreach ($instance->nits_encryption as $encryptedKey) {
                $instance->attributes[$encryptedKey] = Crypt::decryptString($instance->attributes[$encryptedKey]);
            }
        });

        // Encrypt the attributes.
        static::saving(function ($instance) {
            foreach ($instance->nits_encryption as $encryptedKey) {
                $instance->attributes[$encryptedKey] = Crypt::encryptString($instance->attributes[$encryptedKey]);
            }
        });
    }
}