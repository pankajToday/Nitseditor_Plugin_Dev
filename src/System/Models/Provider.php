<?php
namespace Nitseditor\System\Models;

class Provider extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_access_token_providers';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'oauth_access_token_id';
}