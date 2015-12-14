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

    public static function getKeywords($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select sk.keywordID as ID, kw.Word from scenekeywords sk, keywordwords kw where sk.ID=$sid and kw.ID=sk.keywordID");
	if(isset($r['ID'])){
	    $ret = [];
	    $ret[] = $r;
	    return $ret;
	}
	return $r;
    }

    public static function getPaths($sid){
        $sid = self::prepVar($sid);
        return self::$db->queryMulti("select s.Name, sp.endID as ID from scenepaths sp, scenes s where sp.startID=$sid and sp.endID=s.ID");
    }

    public static function getPlayers($sid){
	$sid = self::prepVar($sid);
	$r = self::$db->queryMulti("select ID, Name from playerinfo where scene=$sid and loggedIn = 1");
	if(isset($r['ID'])){
            $ret = [];
            $ret[] = $r;
            return $ret;
        }
        return $r;
    }
}
