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
        $r = self::$db->querySingle("select K.Description from keywordwords W, keywords K where W.Word=$word and K.ID = W.ID limit 1");
        return $r;
    }
    
    /**
     *returns the description of the given keyword word
     */
    public static function getDescKeywordFromID($kid){
        $kid = self::prepVar($kid);
        $r = self::$db->querySingle("select Description from keywords where ID=$kid");
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
     *returns general player info
     *NOT the player's desc
     */
    public static function getPlayerInfo($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select Name,craftSkill,health,loggedIn from playerinfo where ID=$pid");
        return $r;
    }
    
    /**
     *returns the player's id from thier name
     */
    public static function getPlayerID($name){
        $name = self::prepVar($name);
        $r = self::$db->querySingle("select ID from playerinfo where Name=$name");
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
     *returns the non-description npcs info
     */
    public static function getNpcInfo($nid){
        $nid = self::prepVar($nid);
        $r = self::$db->querySingle("select level from npcs where ID=$nid");
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
    public static function addPlayerAlert($pid, $aid){
        $pid = self::prepVar($pid);
        $aid = self::prepVar($aid);
        self::$db->querySingle("insert into playeralerts (alertID, playerID) values ($aid,$pid)");
    }
    
    /**
     *adds the given keyword info id to the player id
     */
    public static function addPlayerKeyword($pid, $kid, $loc, $kwtype){
        $pid = self::prepVar($pid);
        $kid = self::prepVar($kid);
        $loc = self::prepVar($loc);
        $kwtype = self::prepVar($kwtype);
        self::$db->querySingle("insert into playerkeywords (ID,keywordID,locationID,type) values ($pid,$kid,$loc,$kwtype)");
    }
    
    public static function getPlayersItemInfo($pid, $iname){
        $pid = self::prepVar($pid);
        $iname = self::prepVar($iname);
        $r = self::$db->querySingle("select room,ID,insideOf from items where playerID=$pid and Name=$iname");
        return $r;
    }
    
    public static function getItemID($iname){
        $iname = self::prepVar($iname);
        $r = self::$db->querySingle("select ID from items where Name=$iname");
        return $r;
    }
    
    /**
     *removes the given alert id from the player id
     */
    public static function removePlayerAlert($pid, $aid){
        $pid = self::prepVar($pid);
        $aid = self::prepVar($aid);
        self::$db->querySingle("delete from playeralerts where playerID=$pid and alertID=$aid");
    }
    
    /**
     *returns the id and type of the given keyword word and id
     */
    public static function getKeywordFromWord($word, $kid){
        $word = self::prepVar($word);
        $kid = self::prepVar($kid);
        $r = self::$db->querySingle("select ID,Type from keywordwords where Word=$word and ID=$kid");
        return $r;
    }
    
    /**
     *returns the name and id of all the player's visible items
     */
    public static function getVisibleItems($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->queryMulti("select Name,ID from items where playerID=$pid and insideOf=0");
        return $r;
    }
    
    /**
     *returns the name and id of all the player's items
     */
    public static function getTotalItems($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->queryMulti("select name,ID from items where playerID=$pid");
        return $r;
    }
    
    /**
     *returns the count for item id and type
     */
    public static function checkItemHasKeywordType($iid, $kwtype){
        $kwtype = self::prepVar($kwtype);
        $iid = self::prepVar($iid);
        $r = self::$db->queryMulti("select count(1) from itemkeywords where ID=$iid and type=$kwtype");
        return $r;
    }
    
    /**
     *returns the scenes IDs and names connected to the given scene
     */
    public static function getPaths($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->queryMulti("select P.startID, S.Name from scenepaths P, scenes S where P.startID=$sid and P.startID = S.ID");
        return $r;
    }
    
    /**
     *returns the id and type of the given keyword if from the given table
     *type is a span types type
     */
    public static function getKeywordFromID($type, $kid){
        $table = self::prepVar(self::getTableKeywords($type));
        $kid = self::prepVar($kid);
        $r = self::$db->querySingle("select keywordID,Type from $table where ID=$kid");
        return $r;
    }
    
    /**
     *returns the first keyword word with the given id
     */
    public static function getSingleKeywordFromID($kid){
        $kid = self::prepVar($kid);
        $r = self::$db->querySingle("select Word from keywordwords where ID=$kid limit 1");
        return $r;
    }
    
    /**
     *sets the given description to the given id for the given type
     */
    public static function setDescription($desc, $id, $type){
        $table = self::prepVar(self::getTable($type));
        $id = self::prepVar($id);
        $desc = self::prepVar($desc);
        self::$db->querySingle("update $table set Description=$description where ID=$id");
    }
    
    /**
     *sets the owner of the item to the given player
     */
    public static function setItemOwner($iid, $pid){
        $iid = self::prepVar($iid);
        $pid = self::prepVar($pid);
        self::$db->querySingle("update items set playerID=$pid where ID=$iid");
    }
    
    /**
     *sets the owner of the item to the given player
     */
    public static function removeItemOwner($iid, $pid){
        $iid = self::prepVar($iid);
        $pid = self::prepVar($pid);
        self::$db->querySingle("update items set playerID=0 where playerID=$pid and ID=$iid");
    }
    
    /**
     *returns the type and locationID of the player's job
     */
    public static function getJobType($pid){
        $pid = self::prepVar($pid);
        $r = self::$db->querySingle("select type, locationID from playerkeywords where ID=$pid and (type=".keywordTypes::APPSHP." or type=".keywordTypes::MANAGER." or type=".keywordTypes::LORD." or type=".keywordTypes::MONARCH.")");
        return $r;
    }
    
    /**
     *returns the town and land of the given scene id
     */
    public static function getSceneLandInfo($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select town, land from scenes where ID=$sid");
        return $r;
    }
    
    public static function getPlayerIDFromSceneJob($sid, $ktype){
        $ktype = self::prepVar($ktype);
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select ID from playerkeywords where type=$ktype and locationID=$sid");
        return $r;
    }
    
    /**
     *returns count(1) of matching keyword
     */
    public static function checkSceneKeyword($sid, $kid, $ktype){
        $sid = self::prepVar($sid);
        $kid = self::prepVar($kid);
        $ktype = self::prepVar($ktype);
        $r = self::$db->querySingle("select count(1) from scenekeywords where ID=$sid and keywordID=$kid and type=$ktype");
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
}
?>