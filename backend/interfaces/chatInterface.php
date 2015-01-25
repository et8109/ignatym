<?php
require_once("interface.php");

class ChatInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function getNumAlerts($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select count(1) from playeralerts where playerID=$pid");
        return $r;
    }
}
?>