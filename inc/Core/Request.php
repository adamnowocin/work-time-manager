<?php

namespace Core;

class Request
{
    
    public static function Error($code = 0, $error = 'Error')
    {
        die('<!DOCTYPE html><head><title>' . $code . ' - ' . $error . '</title><meta charset="utf-8" /></head><body>' . $code . ' - ' . $error . '</body></html>');
    }
    
    public static function Field($field)
    {
        $result = false;
        if (array_key_exists($field, $_GET)) {
            if ($_GET[$field] != '') {
                $result = $_GET[$field];
            }
        }
        if (array_key_exists($field, $_POST)) {
            if ($_POST[$field] != '') {
                $result = $_POST[$field];
            }
        }
        return $result;
    }
    
    public static function Location($url)
    {
        header('Location: ' . $url);
        die;
    }
    
}