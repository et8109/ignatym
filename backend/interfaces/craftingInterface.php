<?php
require_once("interface.php");

class CraftingInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function removeSceneKeyword($sid, $kid){
        $sid = self::prepVar($sid);
        $kid = self::prepVar($kid);
        self::$db->querySingle("remove from scenekeywords where ID=$sid and keywordID=$kid limit 1");
    }
    
    /**
     *returns the id of the creted item
     */
    public static function createItem($pid, $iname, $idesc, $isContainer){
        $pid = self::prepVar($pid);
        $iname = self::prepVar($iname);
        $idesc = self::prepVar($idesc);
        if($isContainer){
            $room = self::prepVar(2);
            self::$db->querySingle("insert into items (playerID, Name, Description, room) values ($pid,$iname,$idesc,$room)");
        } else{
            self::$db->querySingle("insert into items (playerID, Name, Description) values ($pid,$iname,$idesc)");
        }
        return self::$db->lastQueryID();
    }
    
    public static function createItemKeywords($iid, $kid, $ktype){
        $iid = self::prepVar($iid);
        $kid = self::prepVar($kid);
        $ktype = self::prepVar($ktype);
        self::$db->querySingle("insert into itemkeywords (ID, keywordID, type) values ($iid,$kid,$ktype)");
    }
    
    public static function getKeywordID($word, $ktype, $minID){
        $word = self::prepVar($word);
        $ktype = self::prepVar($ktype);
        $r = null;
        if($minID > 0){
            $minID = self::prepVar($minID);
            $r = self::$db->querySingle("select ID from keywordwords where Word=$word and Type=$ktype and ID<=$minID");
        } else{
            $r = self::$db->querySingle("select ID from keywordwords where Word=$word and Type=$ktype");
        }
        return $r;
    }
}
?>