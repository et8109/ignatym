<?php
require_once("table.php");

class SceneTable extends Table_class{
    private function __construct() {}//static only

    public static function setDesc($sid, $desc){
        $sid = self::prepVar($sid);
	$desc = self::prepVar($desc);
        self::$db->querySingle("update scenes set Description=$desc where ID=$sid");
    }

    public static function getInfo($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select Description, Name, ID from scenes where ID=$sid");
        return $r;
    }


}
