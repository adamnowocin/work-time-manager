<?php

namespace Model;

use Core\DataModel as DataModel;

class User extends DataModel
{
    protected $fields = array(
        'login' => array(
            'type' => 'string',
            'length' => 200,
            'required' => true
        ),
        'pass' => array(
            'type' => 'string',
            'length' => 200,
            'required' => true
        ),
    );

    protected $table = 'user';
}
