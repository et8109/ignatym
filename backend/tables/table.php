<?php

require_once(ROOT."/backend/database.php");

/**
 *The model which controls actual back end data.
 */
class Table_class {
    protected static $db;
    
    public static function init(){
        self::$db = new Database();
    }
    
    protected static function getDatabaseName(){
        return self::$db->getDatabaseName();
    }
    
    protected static function prepVar($var){
        $var = Table_class::$db->escapeString($var);
        //replace ' with ''
        //$var = str_replace("'", "''", $var);
        //if not a number, surround in quotes
        if(!is_numeric($var)){
            $var = "'".$var."'";
        }
        return $var;
    }
}
//initialize db object
Table_class::init();
?>
