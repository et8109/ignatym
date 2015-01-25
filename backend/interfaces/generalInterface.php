<?php
require_once("interface.php");

class GeneralInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function getDescItem($iid){
        $iid = self::prepVar($iid);
        $r = self::$db->querySingle("select Name, Description from items where ID=$iid");
        return $r;
    }
    
    public static function getDescKeyword($word){
        $word = self::prepVar($word);
        $r = self::$db->querySingle("select K.Description from keywordwords W, keywords K where W.Word=$word");
        return $r;
    }
    
    public static function getDescPlayer($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select Name, Description from playerinfo where ID=$pid");
        return $r;
    }
    
    public static function getDescNpc($nid){
        $nid = self::prepVar($nid);
        $r = self::$db->querySingle("select name,description from npcs where ID=$nid");
        return $r;
    }
    
    public static function getDescScene($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select Name, Description from scenes where ID=$sid");
        return $r;
    }
    
    public static function getPlayersInScene($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select playerID,playerName from sceneplayers where sceneID=$sid");
        return $r;
    }
    
    public static function getNpcsInScene($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select npcID,npcName from scenenpcs where sceneID=$sid and health>0");
        return $r;
    }
    
    public static function getSceneJobs($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select town,land,appshp from scenes where ID=$sid");
        return $r;
    }
    
    public static function getSceneWorker($type, $locID){
        $locID = self::prepVar($locID);
        $type = self::prepVar($type);
        $r = self::$db->querySingle("select P.Name from playerkeywords K, playerinfo P where K.type=$type and K.locationID=$locID");
        return $r;
    }
    
    public static function changePlayerScene($pid, $sid, $pname){
        $pid = self::prepVar($pid);
        $sid = self::prepVar($sid);
        $pname = self::prepVar($pname);
        self::$db->querySingle("delete from sceneplayers where playerID=$pid");
        self::$db->querySingle("insert into sceneplayers (sceneID,playerID,playerName) values($sid,$pid,$pname)");
        self::$db->querySingle("Update playerinfo set Scene=$sid where ID=$pid");
    }
    
    public static function deleteItem($iid){
        $iid = self::prepVar($iid);
        self::$db->querySingle("delete from items where ID=$iid");
        self::$db->querySingle("delete from itemkeywords where ID=$iid");
    }
    
    //needed?
    public static function getSceneName($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select Name from scenes where ID=$sid");
        return $r;
    }
    
    //needed?
    public static function getItemName($iid){
        $iid = self::prepVar($iid);
        $r = self::$db->querySingle("select ID from items where Name=$iid");
        return $r;
    }
}
?>