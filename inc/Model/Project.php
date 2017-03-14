<?php

namespace Model;

use Core\DataModel as DataModel;

class Project extends DataModel
{
    protected $fields = array(
        'name' => array(
            'type' => 'string',
            'length' => 200,
        ),
    );

    protected $table = 'project';
}
