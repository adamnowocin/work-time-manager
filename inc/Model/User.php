<?php

namespace Model;

use Core\DataModel as DataModel;

class User extends DataModel
{
    protected $fields = array(
        'login' => array(
            'type' => 'string',
            'length' => 200,
        ),
        'pass' => array(
            'type' => 'string',
            'length' => 200,
        ),
        'is_admin' => array(
            'type' => 'boolean',
        ),
    );

    protected $table = 'user';
}
