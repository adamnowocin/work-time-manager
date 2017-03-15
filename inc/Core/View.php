<?php

namespace Core;

use Core\Request;

class View {
    
    private static $Blocks = array();
    private static $Template = '';
    private static $Extension = '.html';

    public static function Get($file, $variables = false) {
        if (!($tpl = @file_get_contents(CORE_PATH . '/inc/View/' . $file . self::$Extension))) {
            Request::Error(4);
        }
        if($variables !== false) {
            foreach($variables as $name => $value) {
                $tpl = str_replace('[@' . $name . ']', $value, $tpl);
            }
        }
        return $tpl;
    }

    public static function Load($file, $isPart = false) {
        if (!($tpl = @file_get_contents(CORE_PATH . '/inc/View/' . $file . self::$Extension))) {
            Request::Error(4);
        }
        if($isPart) {
            self::$Blocks['part_' . $file] = $tpl;
        } else {
            self::$Template = $tpl;
        }
    }
    
    public static function Variable($name, $value) {
        self::$Blocks[$name] = $value;
    }
    
    public static function Render() {
        foreach(self::$Blocks as $name => $value) {
            self::$Template = str_replace( '[@' . $name . ']', $value, self::$Template );
        }
        die(self::$Template);
    }

    private static function LoopParts($key, $value, $tpl) {
        $html = explode('[@'.$key.']', $tpl);
        if (is_array($html)) {
            foreach ($html as $k => $r) {
                $pos = strpos($r, '[/'.$key.']');
                if ($pos !== false) {
                    $loopEnd = $pos + strlen('[/'.$key.']');
                    $part = '';
                    foreach($value as $id => $val) {
                        $part .= self::DataTemplate($val, substr($r, 0, $pos));
                    }
                    $html[$k] =  $part . substr($r, $loopEnd);
                }
            }
        }
        return join('', $html);
    }

    public static function DataTemplate($data, $tpl) {
        
        $html = $tpl;
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $html = self::LoopParts($key, $value, $html);
            } else {
                $html = str_replace('[@'.$key.']', $value, $html);
            }
        }
        
        return $html;
    }
    
    public static function LinkHelper($link) {
        $parsedLink = strtolower($link);
        
        if(substr($parsedLink, 0, 7) != 'http://' && substr($parsedLink, 0, 8) != 'https://' && substr($parsedLink, 0, 6) != 'ftp://') {
            $parsedLink = 'http://' . $parsedLink;
        }
        
        return $parsedLink;
    }
    
}