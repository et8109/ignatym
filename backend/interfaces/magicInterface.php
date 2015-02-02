<?php
require_once("interface.php");

class MagicInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function getKeywordID($word, $ktype){
        $word = self::prepVar($word);
        $ktype = self::prepVar($ktype);
        $r = self::$db->querySingle("select ID from keywordwords where Word=$word and type=$ktype");
        return $r;
    }
    
    public static function checkPlayerKeywordType($pid, $ktype){
        $pid = self::prepVar($pid);
        $ktype = self::prepVar($ktype);
        $r = self::$db->querySingle("select count(1) from playerkeywords where ID=$pid and type=$ktype");
        return $r;
    }
    
    public static function removePlayerKeywordType($pid, $ktype){
        $pid = self::prepVar($pid);
        $ktype = self::prepVar($ktype);
        self::$db->querySingle("delete from playerkeywords where ID=$pid and type=$ktype");
    }
    
    public static function checkPlayerKeyword($pid, $kid, $ktype){
        $pid = self::prepVar($pid);
        $kid = self::prepVar($kid);
        $ktype = self::prepVar($ktype);
        $r = self::$db->querySingle("select count(1) from playerkeywords where ID=$pid and type=$ktype and keywordID=$kid");
        return $r;
    }
    
    /**
     *returns the amount of npcs affected
     */
    public static function regenNpcType($sid, $ntype, $health){
        $sid = self::prepVar($sid);
        $ntype = self::prepVar($ntype);
        $health = self::prepVar($health);
        self::$db->querySingle("update scenenpcs set health=$health where health=0 and sceneID=$sid and type=$ntype");
        return self::$db->lastQueryNumRows();
    }
    
    public static function getSceneCoords($sid){
        $sid = self::prepVar($sid);
        $r = self::$db->querySingle("select posx, posy from scenes where ID=$sid");
        return $r;
    }
    
    /**
     *retuns the amount of rows affected
     */
    public static function setRaining($val){
        if($val == 1){
            self::$db->querySingle("update constants set raining=1 where raining=0");
        }
        else if($val == 0){
            self::$db->querySingle("update constants set raining=0 where raining=1");
        }
        else{
            throw new Exception("illegal raining value");
        }
        return self::$db->lastQueryNumRows();
    }
    
}
?>