<?php

namespace Nitseditor\System\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class Routes extends AbstractModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $nits_encryption = ['name', 'type', 'redirects'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 'name', 'type', 'redirects'
    ];



}