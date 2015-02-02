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
}
?>