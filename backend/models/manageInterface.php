<?php
require_once("interface.php");

class ManageInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function countItemsInScene($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select count(1) from itemsinscenes where sceneID=$sid");
        return $r;
    }
    
    public static function addItemToScene($sid, $iid, $inote){
        $sid = self::prepVar($sid);
        $iid = self::prepVar($iid);
        $inote = self::prepVar($inote);
        self::$db->querySingle("insert into itemsinscenes (sceneID,itemID,note) values ($sid,$iid,$note)");
    }
    
    public static function removeItemFromScene($sid, $iid){
        $sid = self::prepVar($sid);
        $iid = self::prepVar($iid);
        self::$db->querySingle("delete from itemsInScenes where sceneID=$sid and itemID=$iid");
    }
    
    public static function checkItemInScene($sid, $iid){
        $sid = self::prepVar($sid);
        $iid = self::prepVar($iid);
        $r = self::$db->querySingle("select count(1) from itemsinscenes where sceneID=$sid and itemID=$iid");
        return $r;
    }
    
    public static function changeItemNote($iid, $note){
        $iid = self::prepVar($iid);
        $note = self::prepVar($note);
        self::$db->querySingle("update itemsinscenes set note=$note where itemID=$iid");
    }
    
    public static function getPlayerIDFromSceneJob($sid, $ktype){
        $ktype = self::prepVar($ktype);
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select count(1) from playerkeywords where type=$ktype and locationID=$sid");
        return $r;
    }
    
    public static function removePlayerJob($pid){
        $pid = self::prepVar($pid);
        self::$db->querySingle("delete from playerkeywords where ID=$pid and (type=".keywordTypes::APPSHP." or type=".keywordTypes::MANAGER." or type=".keywordTypes::LORD." or type=".keywordTypes::MONARCH.")");
    }
    
    public static function checkLocationAcceptsApprentice($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle(("select count(1) from scenes where ID=$sid and appshp=1");
        return $r;
    }
    
    public static function alertApprentacesManager($alertNum, $mangerTypeNum, $sceneID){
        $alertNum = self::prepVar($alertNum);
        $mangerTypeNum = self::prepVar($mangerTypeNum);
        $sceneID = self::prepVar($sceneID);
        self::$db->querySingle("insert into alerts (playeralerts, playerID) ".
                            "select $alertNum, ID from playerkeywords where type=$mangerTypeNum and locationID=$sceneID");
        
    }
    
    public static function alertManagersApprentaces($alertNum, $apprenticeTypeNum, $sceneID){
        $alertNum = self::prepVar($alertNum);
        $apprenticeTypeNum = self::prepVar($apprenticeTypeNum);
        $sceneID = self::prepVar($sceneID);
        self::$db->querySingle("insert into alerts (playeralerts, playerID) ".
                            "select $alertNum, ID from playerkeywords where type=$apprenticeTypeNum and locationID=$sceneID");
        
    }
    
    public static function alertManagersLord($alertNum, $lordTypeNum, $sceneID){
        $alertNum = self::prepVar($alertNum);
        $lordTypeNum = self::prepVar($lordTypeNum);
        $sceneID = self::prepVar($sceneID);
        self::$db->querySingle("insert into alerts (playeralerts, playerID) ".
                    "select $alertNum, P.ID from playerkeywords P, scenes S where P.type=$lordTypeNum and S.ID=$sceneID and S.town=P.locationID");
        
    }
    
    public static function alertLordsManagers($alertNum, $managerTypeNum, $sceneID){
        $alertNum = self::prepVar($alertNum);
        $managerTypeNum = self::prepVar($managerTypeNum);
        $sceneID = self::prepVar($sceneID);
        self::$db->querySingle("insert into alerts (playeralerts, playerID) ".
            "select $alertNum, P.ID from playerkeywords P where P.type=$managerTypeNum and P.locationID=(select ID from scenes where town=(select town from scenes where ID=$sceneID))");
        
    }
    
    public static function alertLordsMonarch($alertNum, $monarchTypeNum, $sceneID){
        $alertNum = self::prepVar($alertNum);
        $monarchTypeNum = self::prepVar($monarchTypeNum);
        $sceneID = self::prepVar($sceneID);
        self::$db->querySingle("insert into alerts (playeralerts, playerID) ".
            "select $alertNum, P.ID from playerkeywords P, scenes S where P.type=$monarchTypeNum and P.locationID=S.land and S.ID=$sceneID");
    }
    
    public static function alertMonarchsLords($alertNum, $lordTypeNum, $sceneID){
        $alertNum = self::prepVar($alertNum);
        $lordTypeNum = self::prepVar($lordTypeNum);
        $sceneID = self::prepVar($sceneID);
        self::$db->querySingle("insert into alerts (playeralerts, playerID) ".
            "select $alertNum, P.ID from playerkeywords P, scenes S where P.type=$lordTypeNum and P.locationID=S.town and S.ID=$sceneID");
    }
    
}
?>