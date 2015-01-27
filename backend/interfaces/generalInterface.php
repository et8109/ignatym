<?php
require_once("interface.php");

class GeneralInterface extends Interface_class{
    private function __construct() {}//static only
    
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
    
    //needed?
    public static function getPlayerID($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select ID from playerinfo where Name=$pid");
        return $r;
    }
    
    //needed?
    public static function getPlayerStats($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select Name,craftSkill,health from playerinfo where ID=$pid");
        return $r;
    }
    
    //needed?
    public static function getPlayerKeywords($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->queryMulti("select P.keywordID,P.locationID,P.type,first(W.Word) from playerkeywords P, keywordwords W where P.ID=$pid and W.ID = P.keywordID");
        return $r;
    }
    
    //needed?
    public static function getPlayersItemInfo($pid, $iname){
        $pid = self::prepVar($pid);
        $iname = self::prepVar($iname);
        $r = self::$db->querySingle("select room,ID,insideOf from items where playerID=$pid and Name=$iname");
        return $r;
    }
    
    public static function checkItemHasKeywordType($iid, $kwtype){
        $kwtype = self::prepVar($kwtype);
        $iid = self::prepVar($iid);
        $r = self::$db->queryMulti("select count(1) from itemkeywords where ID=$iid and type=$kwtype");
        return $r;
    }
    
    public static function putItemInItem($putid, $conainerid){
        $putid = self::prepVar($putid);
        $conainerid = self::prepVar($conainerid);
        self::$db->querySingle("update items set insideOf=$conainerid where ID=$putid");
        self::$db->querySingle("update items set room=room-1 where ID=$conainerid");
    }
    
    public static function removeItemFromItem($takeid, $conainerid){
        $takeid = self::prepVar($takeid);
        $conainerid = self::prepVar($conainerid);
        self::$db->querySingle("update items set insideOf=0 where ID=$takeid");
        self::$db->querySingle("update items set room=room+1 where ID=$conainerid");
    }
    
    public static function getItemsInScene($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->queryMulti("select S.itemID, S.note, I.Name from itemsinscenes S, items I where sceneID=$sid and S.itemID = I.ID");
        return $r;
    }
    
    public static function sceneKeywordsOfType($sid, $type){
        $sid = self::prepVar($sid);
        $type = self::prepVar($type);
        $r = self::$db->queryMulti("select Word, ID from keywordwords where ID = (select keywordID from scenekeywords where ID=$sid and type=$type) limit 1");
        return $r;
    }
    
    public static function setFrontLoadScenes($pid, $val){
        $pid = self::prepVar($pid);
        $val = self::prepVar($val);
        $r = self::$db->queryMulti("update playerinfo set frontLoadScenes=$val where ID=$pid");
        return $r;
    }
    
    public static function setFrontLoadKeywords($pid, $val){
        $pid = self::prepVar($pid);
        $val = self::prepVar($val);
        $r = self::$db->queryMulti("update playerinfo set frontLoadKeywords=$val where ID=$pid");
        return $r;
    }
    
    public static function getPlayerAlertMessages($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->queryMulti("select P.alertID, A.Description from playeralerts P, alerts A where playerID=$pid and P.alertID = A.ID");
        return $r;
    }
}
?>