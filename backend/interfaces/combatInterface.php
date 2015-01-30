<?php
require_once("interface.php");

class CombatInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function getSceneKeywordIDs($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select keywordID from scenekeywords where ID=".prepVar($_SESSION['currentScene']));
        return $r;
    }
    
    public static function getPlayerFromScene($sid, $pname){
        $sid = self::prepVar($sid);
        $pname = self::prepVar($pname);
        $r = self::$db->querySingle("SELECT playerID FROM sceneplayers WHERE SceneID =$sid AND playerName = $pname");
        return $r;
    }
    
    public static function getNpcFromScene($sid, $nname){
        $sid = self::prepVar($sid);
        $nname = self::prepVar($nname);
        $r = self::$db->querySingle("SELECT npcID FROM scenenpcs WHERE SceneID =$sid AND npcName = $nname and health>0");
        return $r;
    }
    
    public static function lowerPlayerHealth($pid, $amount){
        $pid = self::prepVar($pid);
        $amount = self::prepVar($amount);
        self::$db->querySingle("update playerinfo set health=health-$amount where ID=$sid and health>0");
    }
}
?>