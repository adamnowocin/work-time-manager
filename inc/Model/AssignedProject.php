<?php

namespace Model;

use Core\DataModel as DataModel;

class AssignedProject extends DataModel
{
    protected $fields = array(
        'user_id' => array(
            'type' => 'integer',
        ),
        'project_id' => array(
            'type' => 'integer',
        ),
    );

    protected $table = 'assigned_project';
}
