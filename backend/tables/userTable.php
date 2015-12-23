<?php
require_once("table.php");

class UserTable extends Table_class{
    private function __construct() {}//static only

    public static function getInfo($uid){
        $uid = self::prepVar($uid);
        return self::$db->querySingle("select Description,ID,loggedIn,Name, Health from playerinfo where ID=$uid");
    }


    public static function login($uname, $pass){
        $uname = self::prepVar($uname);
        $pass = self::prepVar($pass);
        return self::$db->querySingle("select Description,ID,loggedIn,Scene,Name from playerinfo where Name=$uname and Password=$pass");
    }

    public static function logoutUser($uid){
        $pid = self::prepVar($uid);
        self::$db->querySingle("update playerinfo set loggedIn=0 where ID=$uid");
    }

    public static function getDesc($uid){
        $uid = self::prepVar($uid);
        $r = self::$db->querySingle("select Description from playerinfo where ID=$uid");
        return $r;
    }

    public static function setDesc($uid, $desc){
        $uid = self::prepVar($uid);
	$desc = self::prepVar($desc);
        self::$db->querySingle("update playerinfo set Description=$desc where ID=$uid");
    }

    public static function appendToDesc($uid, $str){
        $uid = self::prepVar($uid);
        $str = self::prepVar($str);
        self::$db->querySingle("update playerinfo set Description=concat(Description,$str) where ID=$uid");
    }

 
    public static function changeScene($uid, $sid){
        $uid = self::prepVar($uid);
        $sid = self::prepVar($sid);
        self::$db->querySingle("Update playerinfo set Scene=$sid where ID=$uid");
    }

    public static function setLoggedIn($uid, $loginID){
        $uid = self::prepVar($uid);
        $loginID = self::prepVar($loginID);
        self::$db->querySingle("update playerinfo set loggedIn = 1, lastLoginTime=CURRENT_TIMESTAMP where ID=$uid");
    }

    public static function register($uname, $pword){
        $uname = self::prepVar($uname);
        $pword = self::prepVar($pword);
        self::$db->querySingle("insert into playerinfo (Name, Password, Description, Scene, Health) values ($uname, $pword, 'I\'m new, so be nice to me!', 101, 3)");
        if (self::$db->lastQueryNumRows() != 1){
           throw new Exception("username taken");
	}
        return self::$db->lastQueryID();
    }

    public static function getKeywords($uid){
        $uid = self::prepVar($uid);
        $r = self::$db->querySingle("select uk.keywordID as ID, kw.Word from playerkeywords uk, keywordwords kw where uk.ID=$uid and kw.ID=uk.keywordID");

        if(isset($r['ID'])){
            $ret = [];
            $ret[] = $r;
            return $ret;
        }
        return $r;

    }

    public static function getItems($uid){
        $uid = self::prepVar($uid);
        $r = self::$db->querySingle("select ID, Name from items as ID where playerID=$uid");

	if(isset($r['ID'])){
            $ret = [];
            $ret[] = $r;
            return $ret;
        }
        return $r;

    }

    public static function setHealth($uid, $health){
        $uid = self::prepVar($uid);
        $health = self::prepVar($health);
        self::$db->querySingle("update playerinfo set Health=$health where ID=$uid");
    }

    //--------- from before
    public static function getLogin($uname, $pass){
        $uname = self::prepVar($uname);
        $pass = self::prepVar($pass);
        $r = self::$db->querySingle("select ID,Name,Scene,loggedIn from playerinfo where Name=$uname and password=$pass");
        return $r;
    }
    
}
?>
