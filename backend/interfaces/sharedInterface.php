<?php
require_once("interface.php");

class SharedInterface extends Interface_class{
    private function __construct() {}//static only
    
    /**
     *returns the name and description of the given item id
     */
    public static function getDescItem($iid){
        $iid = self::prepVar($iid);
        $r = self::$db->querySingle("select Name, Description from items where ID=$iid");
        return $r;
    }
    
    /**
     *returns the description of the given keyword word
     */
    public static function getDescKeyword($word){
        $word = self::prepVar($word);
        $r = self::$db->querySingle("select K.Description from keywordwords W, keywords K where W.Word=$word");
        return $r;
    }
    
    /**
     *returns the name and description of the given player id
     */
    public static function getDescPlayer($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select Name, Description from playerinfo where ID=$pid");
        return $r;
    }
    
    /**
     *returns the name and description of the given npc id
     */
    public static function getDescNpc($nid){
        $nid = self::prepVar($nid);
        $r = self::$db->querySingle("select name,description from npcs where ID=$nid");
        return $r;
    }
    
    /**
     *returns the name and description of the given scene id
     */
    public static function getDescScene($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select Name, Description from scenes where ID=$sid");
        return $r;
    }
    
    /**
     *adds the given alert id to the player id
     */
    public static addPlayerAlert($pid, $aid){
        $pid = self::prepVar($pid);
        $aid = self::prepVar($aid);
        self::$db->querySingle("insert into playeralerts (alertID, playerID) values ($aid,$pid)");
    }
    
    /**
     *adds the given keyword info id to the player id
     */
    public static addPlayerKeyword($pid, $kid, $loc, $kwtype){
        $pid = self::prepVar($pid);
        $kid = self::prepVar($kid);
        $loc = self::prepVar($loc);
        $kwtype = self::prepVar($kwtype);
        self::$db->querySingle("insert into playerkeywords (ID,keywordID,locationID,type) values ($pid,$kid,$loc,$kwtype)");
    }
    
    /**
     *removes the given alert id from the player id
     */
    public static removePlayerAlert($pid, $aid){
        $pid = self::prepVar($pid);
        $aid = self::prepVar($aid);
        self::$db->querySingle("delete from playeralerts where playerID=$pid and alertID=$aid");
    }
    
    /**
     *returns the id and type of the given keyword word and id
     */
    public static getKeywordFromWord($word, $kid){
        $word = self::prepVar($word);
        $kid = self::prepVar($kid);
        $r = self::$db->querySingle("select ID,Type from keywordwords where Word=$word and ID=$kid");
        return $r;
    }
    
    /**
     *returns the name and id of all the player's visible items
     */
    public static getVisiableItems($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->queryMulti("select Name,ID from items where playerID=$pid and insideOf=0");
        return $r;
    }
    
    /**
     *returns the name and id of all the player's items
     */
    public static getTotalItems($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->queryMulti("select count(1) from items where playerID=$pid");
        return $r;
    }
    
    /**
     *returns the scenes IDs and names connected to the given scene
     */
    public static getPaths($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->queryMulti("select P.startID, S.Name from scenepaths P, scenes S where P.startID=$sid and P.startID = S.ID");
        return $r;
    }
    
    /**
     *returns the id and type of the given keyword if from the given table
     *type is a span types type
     */
    public static getKeywordFromID($type, $kid){
        $table = self::prepVar(self::getTableKeywords($type));
        $kid = self::prepVar($kid);
        $r = self::$db->querySingle("select keywordID,Type from $table where ID=$kid");
        return $r;
    }
    
    /**
     *returns the first keyword word with the given id
     */
    public static getSingleKeywordFromID($kid){
        $kid = self::prepVar($kid);
        $r = self::$db->querySingle("select Word from keywordwords where ID=$kid limit 1");
        return $r;
    }
    
    /**
     *sets the given description to the given id for the given type
     */
    public static setDescription($desc, $id, $type){
        $table = self::prepVar(self::getTable($type));
        $id = self::prepVar($id);
        $desc = self::prepVar($desc);
        self::$db->querySingle("update $table set Description=$description where ID=$id");
    }
    
    /**
     *sets the owner of the item to the given player
     */
    public static setItemOwner($iid, $pid){
        $iid = self::prepVar($iid);
        $pid = self::prepVar($pid);
        self::$db->querySingle("update items set playerID=$pid where ID=$iid");
    }
    
    /**
     *sets the owner of the item to the given player
     */
    public static removeItemOwner($iid, $pid){
        $iid = self::prepVar($iid);
        $pid = self::prepVar($pid);
        self::$db->querySingle("update items set playerID=0 where playerID=$pid and ID=$iid");
    }
    
    /**
     *returns the type and locationID of the player's job
     */
    public static getJobType($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select type, locationID from playerkeywords where ID=$pid and (type=".keywordTypes::APPSHP." or type=".keywordTypes::MANAGER." or type=".keywordTypes::LORD." or type=".keywordTypes::MONARCH.")");
        return $r;
    }
    
    /**
     *returns the town and land of the given scene id
     */
    public static getSceneLandInfo($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select town, land from scenes where ID=$sid");
        return $r;
    }
    
    /**
     *returns the id of the scene's monarch
     */
    public static getMonarchID($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select P.ID from playerkeywords P, scenes S where S.ID=$sid and P.type =".keywordTypes::MONARCH." and P.locationID = S.ID");
        return $r;
    }
    
    
    
    /**
    *returns the table where the object's keywords are
    */
    private static function getTableKeywords($spanTypesType){
        switch($spanTypesType){
            case(spanTypes::SCENE):
                return 'scenekeywords';
                break;
            case(spanTypes::ITEM):
                return 'items';
                break;
            case(spanTypes::PLAYER):
                return 'playerkeywords';
                break;
            case(spanTypes::KEYWORD):
                return 'keywords';
                break;
        }
        return null;
    }
    
    /**
    *returns the table where the object itself it
    */
    private static function getTable($spanTypesType){
        switch($spanTypesType){
            case(spanTypes::SCENE):
                return 'scenes';
                break;
            case(spanTypes::ITEM):
                return 'items';
                break;
            case(spanTypes::PLAYER):
                return 'playerinfo';
                break;
            case(spanTypes::KEYWORD):
                return 'keywords';
                break;
        }
        return null;
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
    
    //needed?
    public static function getPlayerID($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select ID from playerinfo where Name=$pid");
        return $r;
    }
    
    //needed?
    public static function getPlayersItemID($pid, $iname){
        $pid = self::prepVar($pid);
        $iname = self::prepVar($iname);
        $r = self::$db->querySingle("select ID from items where playerID=$pid and Name=$iname");
        return $r;
    }
    
    //needed?
    public static function getItemContainer($pid, $iname){
        $pid = self::prepVar($pid);
        $iname = self::prepVar($iname);
        $r = self::$db->querySingle("select ID,insideOf from items where playerID=$pid and Name=$iname");
        return $r;
    }
}
?>