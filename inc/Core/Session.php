<?php

namespace Core;

class Session {
    
    public static function Get($field)
    {
        $result = false;
        if (array_key_exists($field, $_SESSION)) {
            $result = $_SESSION[$field];
        }
        return $result;
    }
    
    public static function Set($field, $value)
    {
        $_SESSION[$field] = $value;
    }

    public static function Remove($field)
    {
        unset($_SESSION[$field]);
    }
    
}
