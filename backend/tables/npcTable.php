<?php
require_once("table.php");

class NpcTable extends Table_class{
    private function __construct() {}//static only

    public static function getDesc($nid){
        $nid = self::prepVar($nid);
        return self::$db->querySingle("select Description from npcs where ID=$nid");
    }

}
