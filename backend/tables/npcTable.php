<?php
require_once("table.php");

class NpcTable extends Table_class{
    private function __construct() {}//static only

    public static function getDesc($nid){
        $nid = self::prepVar($nid);
        return self::$db->querySingle("select Description from npcs where ID=$nid");
    }

    public static function getInfo($nid, $sid){
        $nid = self::prepVar($nid);
        $sid = self::prepVar($sid);
        return self::$db->querySingle("select NpcID as ID, health from scenenpcs where sceneID=$sid and npcID=$nid");
    }

    public static function setHealth($nid, $health){
	$nid = self::prepVar($nid);
        $health = self::prepVar($health);
	self::$db->querySingle("update scenenpcs set health=$health where npcID=$nid");
    }
}
