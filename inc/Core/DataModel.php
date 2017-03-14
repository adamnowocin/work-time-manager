<?php

namespace Core;

use Core\Database as Database;

abstract class DataModel
{
    protected $fields = array();
    protected $table = null;
    
    public function TableFields()
    {
        return $this->fields;
    }
    
    public function TableName()
    {
        return $this->table;
    }
    
    public function All()
    {
        return Database::Find($this->table);
    }

    public function Find($where, $additional = '')
    {
        return Database::Find($this->table, $where, $additional);
    }

    public function First($where = array())
    {
        return Database::FindOne($this->table, $where);
    }

    public function Last($where = array())
    {
        return Database::FindOne($this->table, $where, 'ORDER BY id DESC');
    }

    public function Add($data = array())
    {
        $fields = array();
        foreach($this->fields as $field => $fieldData) {
            if(isset($data[$field])) {
                $fields[$field] = $data[$field];
            }
        }
        Database::Insert($this->table, $fields);
    }
    
    public function Update($where, $fields)
    {
        Database::UpdateWhere(
            $this->table,
            $where,
            $fields
        );
    }
    
    public function Remove($id)
    {
        Database::DeleteWhere($this->table, array(
            'id' => $id
        ));
    }
    
    public function RemoveByFields($where)
    {
        if(!empty($where)) {
            Database::DeleteWhere($this->table, $where);
        }
    }
}
