<?php

define('CORE_PATH', dirname(__FILE__));

@require_once(CORE_PATH . '/config.php');

if (DEBUG_MODE) {
    error_reporting(E_ALL);
}

// autoload classes based on mapping from namespace to directory structure
function spl_loader_fnc($className)
{
    $ds              = DIRECTORY_SEPARATOR;
    $pathToClassFile = str_replace('\\', $ds, $className);
    $file            = CORE_PATH . $ds . 'inc' . $ds . $pathToClassFile . '.php';
    if (is_readable($file)) {
        @require $file;
    }
}
spl_autoload_register('spl_loader_fnc');


function checkClassFile($className)
{
    $ds              = DIRECTORY_SEPARATOR;
    $pathToClassFile = str_replace('\\', $ds, $className);
    $file            = CORE_PATH . $ds . 'inc' . $ds . $pathToClassFile . '.php';
    if (is_readable($file)) {
        return true;
    }
    return false;
}
