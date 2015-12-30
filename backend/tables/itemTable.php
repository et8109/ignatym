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
        $room = self::prepVar(2);
        self::$db->querySingle("insert into items (playerID, Name, Description, room) values ($uid,$iname,$idesc,$room)");
        return self::$db->lastQueryID();
    }

    public static function getIdByName($uid, $name){
        $name = self::prepVar($name);
        $uid = self::prepVar($uid);
        return self::$db->querySingle("select ID from items where playerID=$uid and Name=$name");
    }

    public static function getInfo($id){
	$id = self::prepVar($id);
        return self::$db->querySingle("select Description, ID, Name, playerID from items where ID=$id");
    }

    public static function remove($iid){
        $iid = self::prepVar($iid);
        self::$db->querySingle("delete from items where ID=$iid");
    }


    ///////////// old

    public static function setItemOwner($iid, $uid){
        $iid = self::prepVar($iid);
        $uid = self::prepVar($uid);
        self::$db->querySingle("update items set playerID=$uid where ID=$iid");
    }

}
