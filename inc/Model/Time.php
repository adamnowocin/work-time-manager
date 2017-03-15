<?php

namespace Model;

use Core\DataModel;

class Time extends DataModel
{

    protected $fields = array(
        'user_id'    => array('type' => 'integer'),
        'project_id' => array('type' => 'integer'),
        'time'       => array('type' => 'integer'),
        'date'       => array('type' => 'string', 'length' => 20),
    );

    protected $table = 'time';

}
