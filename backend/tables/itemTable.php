<?php
require_once("table.php");

class ItemTable extends Table_class{
    private function __construct() {}//static only

    /**
     * Returns the id of the created item
     */
    public static function createItem($uid, $iname, $idesc, $isContainer){
        $uid = self::prepVar($uid);
        $iname = self::prepVar($iname);
        $idesc = self::prepVar($idesc);
        if($isContainer){
            $room = self::prepVar(2);
            self::$db->querySingle("insert into items (playerID, Name, Description, room) values ($uid,$iname,$idesc,$room)");
        } else{
            self::$db->querySingle("insert into items (playerID, Name, Description) values ($uid,$iname,$idesc)");
        }
        return self::$db->lastQueryID();
    }

    ///////////// old

    public static function setItemOwner($iid, $uid){
        $iid = self::prepVar($iid);
        $uid = self::prepVar($uid);
        self::$db->querySingle("update items set playerID=$uid where ID=$iid");
    }

}
