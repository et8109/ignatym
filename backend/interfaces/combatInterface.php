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
    
    public static function setPlayerHealth($pid, $amount){
        $pid = self::prepVar($pid);
        $amount = self::prepVar($amount);
        self::$db->querySingle("update playerinfo set health=$amount where ID=$pid");
    }
    
    /**
     *reduced health and returns current health
     */
    public static function lowerNpcHealth($nid, $amount, $sid){
        $nid = self::prepVar($nid);
        $amount = self::prepVar($amount);
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("update scenenpcs set health=health-$amount OUTPUT INSERTED.health where sceneID=$sid and npcID=$nid and health>0");
        return $r;
    }
    
    /**
     *returns the words and IDs of the npc's keywords of the given type
     */
    public static function sceneKeywordsOfType($nid, $type, $sid){
        $nid = self::prepVar($nid);
        $type = self::prepVar($type);
        $sid = self::prepVar($sid);
        $r = self::$db->queryMulti("select keywordID from npckeywords where ID=$nid and sceneID=$sid and type=$type");
        return $r;
    }
    
    /**
     *returns the number of resources in the scene
     */
    public static function getSceneTotalResources($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->queryMulti("select count(1) from scenekeywords where ID=$sid");
        return $r;
    }
    
    /**
     *adds given keyword to scene
     */
    public static function addSceneKeyword($sid, $kid, $ktype){
        $sid = self::prepVar($sid);
        $kid = self::prepVar($kid);
        $ktype = self::prepVar($ktype);
        self::$db->queryMulti("insert into scenekeywords (ID,keywordID,type) values ($sid,$kid,$ktype)");
    }
    
    /**
     *returns the Name, ID, and keywordID of the player's visible items
     */
    public static getVisibleItemKeywords($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->queryMulti("select I.Name,I.ID,K.keywordID from items I, itemKeywords K where I.playerID=$pid and I.insideOf=0 and I.ID = K.ID");
        return $r;
    }
}
?>