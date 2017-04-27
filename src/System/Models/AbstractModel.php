<?php

namespace Nitseditor\System\Models;


use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model
{
    /**
     *  Defining connection for database
     *
     * @var string
     **/
    protected $connection='mysql';

}