<?php

namespace Core;

use Core\Request as Request;

class Database {
    
    private static $LastQueryResult = null;
    private static $QueryCounter = 0;
    private static $ConnectionId = null;
    
    private static function Connect()
    {
        if (empty(self::$ConnectionId)) {
            self::$ConnectionId = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or Request::Error(2);
            mysqli_query(self::$ConnectionId, "SET NAMES utf8");
        }
    }
    
    public static function Query($sql)
    {
        self::Connect();
        self::$LastQueryResult = mysqli_query(self::$ConnectionId, $sql);
        self::$QueryCounter++;
        if (!self::$LastQueryResult) {
            if (DEBUG_MODE) {
                Request::Error(3, mysqli_error(self::$ConnectionId) . ' - ' . $sql);
            } else {
                Request::Error(3);
            }
        }
        
        return self::$LastQueryResult;
    }
    
    public static function GetQueryCount()
    {
        return self::$QueryCounter;
    } 
    
    public static function NumRows($res = 0)
    {
        if ($res === 0) {
            return mysqli_num_rows(self::$LastQueryResult);
        } else {
            return mysqli_num_rows($res);
        }
    }
    
    public static function Escape($str)
    {
        self::Connect();
        return mysqli_real_escape_string(self::$ConnectionId, $str);
    }
    
    public static function EscapeId($str)
    {
        $result = preg_replace('/[^0-9]/', '', $str);
        if (empty($result)) {
            $result = 0;
        }
        return $result;
    }
    
    public static function Fetch($res = 0)
    {
        if ($res === 0) {
            return mysqli_fetch_array(self::$LastQueryResult, MYSQLI_ASSOC);
        } else  {
            return mysqli_fetch_array($res, MYSQLI_ASSOC);
        }
    }
    
    public static function Select($table, $params = '', $fields = '*')
    {
        $result = array();
        self::Query('SELECT ' . $fields . ' FROM ' . DB_PREFIX . $table . ' ' . $params . ';' );
        while ($r = self::Fetch()) {
            $result[] = $r;
        }
        return $result;
    }
    
    public static function Insert($table, $params)
    {
        $fields = '';
        $values = '';
        foreach($params as $field => $value) {
            if($fields !== '') {
                $fields .= ', ';
            }
            if($values !== '') {
                $values .= ', ';
            }
            $fields .= '`'.$field.'`';
            $values .= '"' . self::Escape($value) . '"';
        }
        
        return self::Query('INSERT INTO ' . DB_PREFIX . $table . ' (' . $fields . ') VALUES (' . $values . ');');
    }
    
    public static function Update($table, $id, $fields)
    {
        $params = '';
        
        foreach($fields as $key => $value) {
            if($params !== '') {
                $params .= ', ';
            }
            $params .= '`'.$key.'` = "'.self::Escape($value).'"';
        }
        
        return self::Query('UPDATE ' . DB_PREFIX . $table . ' SET ' .  $params . ' WHERE id=' . self::EscapeId($id) . ';');
    }
    
    public static function UpdateWhere($table, $where, $fields)
    {
        $params = '';
        foreach($fields as $key => $value) {
            if($params !== '') {
                $params .= ', ';
            }
            $params .= '`'.$key.'` = "'.self::Escape($value).'"';
        }
        
        $params2 = '';
        foreach($where as $key => $value) {
            if($params2 !== '') {
                $params2 .= ' AND ';
            }
            $params2 .= '`'.$key.'` = "'.self::Escape($value).'"';
        }

        return self::Query('UPDATE ' . DB_PREFIX . $table . ' SET ' .  $params . ' WHERE ' . $params2 . ';');
    }
    
    public static function Delete($table, $id)
    {
        return self::Query('DELETE FROM ' . DB_PREFIX . $table . ' WHERE id=' . self::EscapeId($id) . ';');
    }
    
    public static function DeleteWhere($table, $where)
    {
        $params = '';
        foreach($where as $key => $value) {
            if($params !== '') {
                $params .= ' AND ';
            }
            $params .= '`'.$key.'` = "'.self::Escape($value).'"';
        }
        
        return self::Query('DELETE FROM ' . DB_PREFIX . $table . ' WHERE '.$params.';');
    }
    
    public static function Find($table, $where = array(), $additional = '')
    {
        $result = array();
        $params = '';
        
        foreach($where as $key => $value) {
            if($params !== '') {
                $params .= ' AND ';
            }
            $params .= '`'.$key.'` = "'.self::Escape($value).'"';
        }
        if($params != '') {
            self::Query('SELECT * FROM ' . DB_PREFIX . $table . ' WHERE '.$params.' '.$additional.';');
        } elseif($additional != '') {
            self::Query('SELECT * FROM ' . DB_PREFIX . $table . ' '.$additional.';');
        } else {
            self::Query('SELECT * FROM ' . DB_PREFIX . $table);
        }
        while ($r = self::Fetch()) {
            $result[] = $r;
        }
        return $result;
    }
    
    public static function FindFields($table, $fields, $where, $additional = '')
    {
        $result = array();
        $params = '';
        $fieldsStr = '';
        
        foreach($where as $key => $value) {
            if($params !== '') {
                $params .= ' AND ';
            }
            $params .= '`'.$key.'` = "'.self::Escape($value).'"';
        }
        foreach($fields as $value) {
            if($fieldsStr !== '') {
                $fieldsStr .= ', ';
            }
            $fieldsStr .= '`'.$value.'`';
        }
        
        if($params != '' || $additional != '') {
            self::Query('SELECT ' . $fieldsStr . ' FROM ' . DB_PREFIX . $table . ' WHERE '.$params.' '.$additional.';');
        } else {
            self::Query('SELECT ' . $fieldsStr . ' FROM ' . DB_PREFIX . $table);
        }
        
        while ($r = self::Fetch()) {
            $result[] = $r;
        }
        return $result;
    }
    
    public static function FindOne($table, $where, $additional = '')
    {
        $result = false;
        $params = '';
        
        foreach($where as $key => $value) {
            if($params !== '') {
                $params .= ' AND ';
            }
            $params .= '`'.$key.'` = "'.self::Escape($value).'"';
        }
        
        if($params != '') {
            self::Query('SELECT * FROM ' . DB_PREFIX . $table . ' WHERE '.$params.' '.$additional.';');
        } elseif($additional != '') {
            self::Query('SELECT * FROM ' . DB_PREFIX . $table . ' '.$additional.';');
        } else {
            self::Query('SELECT * FROM ' . DB_PREFIX . $table);
        }
        
        if ($r = self::Fetch()) {
            $result = $r;
        }
        return $result;
    }
    
}
