<?php
require_once("interface.php");

class SetupInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function getPlayerInfo($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select Scene,frontLoadAlerts,frontLoadScenes,frontLoadKeywords from playerinfo where ID=$pid");
        return $r;
    }
    
    public static function putPlayerInScene($pid, $sid, $pname){
        $pid = self::prepVar($pid);
        $sid = self::prepVar($sid);
        self::$db->querySingle("insert into sceneplayers (sceneID,playerID,playerName) values(".prepVar($_SESSION['currentScene']).",".prepVar($_SESSION['playerID']).",".prepVar($_SESSION['playerName']).")");
    }
    
    public static function getSceneInfo(){
        $r = self::$db->queryMulti("select ID, Name, Description from scenes");
        return $r;
    }
    
    public static function getKeywordInfo(){
        $r = self::$db->queryMulti("select W.Word, W.ID, K.Description from keywordwords W, keywords K");
        return $r;
    }
}
?>