<?php
require_once("interface.php");

class CombatInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function getPlayersInScene($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select playerID,playerName from sceneplayers where sceneID=$sid");
        return $r;
    }

}
?>