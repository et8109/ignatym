<?php
require_once("table.php");
/*
 * Needed?
 */
class KeywordTable extends Table_class{
    private function __construct() {}//static only
    
    public static function removeSceneKeyword($sid, $kid){
        $sid = self::prepVar($sid);
        $kid = self::prepVar($kid);
        self::$db->querySingle("remove from scenekeywords where ID=$sid and keywordID=$kid limit 1");
    }
    
    public static function createItemKeywords($iid, $kid){
        $iid = self::prepVar($iid);
        $kid = self::prepVar($kid);
        self::$db->querySingle("insert into itemkeywords (ID, keywordID) values ($iid,$kid)");
    }
    
    public static function getKeywordID($word){
        $word = self::prepVar($word);
        return self::$db->querySingle("select ID from keywordwords where Word=$word");
    }

    public static function getKeywordDesc($kwid){
	$kwid = self::prepVar($kwid);
        return self::$db->querySingle("select Description from keywords where ID=$kwid");

    }
}
?>
