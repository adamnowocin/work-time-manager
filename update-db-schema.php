<?php

header('Content-Type: text/plain');

@require('bootstrap.php');

use Core\Database as Database;

$models = scandir(CORE_PATH . '/inc/Model');

echo "Updating schema:\n\n";

foreach ($models as $key => $model) {
    if (substr($model, -3) == 'php') {
        
        $modelName = substr($model, 0, -4);
        echo $modelName . "\n";

        $modelPath = 'Model\\' . $modelName;
        $modelObj = new $modelPath();
        $table = DB_PREFIX . $modelObj->TableName();
        $fields = $modelObj->FieldNames();
        $fieldsSql = array("`id` BIGINT NOT NULL AUTO_INCREMENT,\n");
        $fieldsAlter = array();
        foreach ($fields as $k => $f) {
            $fieldType = 'varchar(255)';
            $default = "DEFAULT ''";
            if(isset($f['type'])) {
                if($f['type'] == 'bool') {
                    $fieldType = 'tinyint(1)';
                    $default = "DEFAULT '0'";
                }
                if($f['type'] == 'int') {
                    $fieldType = 'bigint(20)';
                    $default = "DEFAULT '0'";
                }
                if($f['type'] == 'string') {
                    $length = 255;
                    if(isset($f['length'])) {
                        $length = (int)$f['length'];
                    }
                    if($length == 0 || $length > 255) {
                        $length = 255;
                    }
                    $fieldType = 'varchar('.$length.')';
                }
                if($f['type'] == 'text') {
                    $fieldType = 'text';
                }
            }
            
            if($k != 'id') {
                $fieldsSql[$k] = "`".$k."` ".$fieldType." NOT NULL " . $default . ",\n";
                $fieldsAlter[$k] = array(
                    'alter' => "`".$k."` ".$fieldType." NOT NULL " . $default,
                    'type' => $fieldType
                );
            }
        }
        Database::Query('CREATE TABLE IF NOT EXISTS ' .$table . ' ('.join('', $fieldsSql).' PRIMARY KEY (`id`)) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;');

        Database::Query('SHOW COLUMNS FROM ' .$table);
        $fields = array();
        while($field = Database::Fetch()) {
            $fields[$field['Field']] = $field['Type'];
        }
        
        $fieldsToAdd = array();
        $fieldsToRemove = array();
        $fieldsToChange = array();
        foreach ($fields as $k => $type) {
            if(isset($fieldsAlter[$k])) {
                if($type != $fieldsAlter[$k]['type'] && $k != 'id') {
                    $fieldsToChange[$k] = $fieldsAlter[$k]['alter'];
                }
            } elseif($k != 'id') {
                $fieldsToRemove[$k] = $k;
            }
        }
        foreach ($fieldsAlter as $k => $data) {
            if(!isset($fields[$k]) && $k != 'id') {
                $fieldsToAdd[$k] = $fieldsAlter[$k]['alter'];
            }
        }

        foreach ($fieldsToAdd as $field => $value) {
            echo "  Add: " . $field . "\n";
            Database::Query('ALTER TABLE `' .$table . '` ADD ' . $value);
        }

        foreach ($fieldsToRemove as $field => $value) {
            echo "  Remove: " . $field . "\n";
            Database::Query('ALTER TABLE `' .$table . '` DROP ' . $value);
        }

        foreach ($fieldsToChange as $field => $value) {
            echo "  Change: " . $field . "\n";
            Database::Query('ALTER TABLE `' .$table . '` CHANGE `' . $field . '` ' . $value);
        }
    }
}

echo "\nDone.";
