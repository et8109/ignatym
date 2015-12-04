<?php
require_once("model.php");

class UserModel extends Model_class{
    private function __construct() {}//static only
    
    public static function loginUser($uname, $pass){
        $uname = self::prepVar($uname);
        $pass = self::prepVar($pass);
        return self::$db->querySingle("select ID,loggedIn,Scene,Name from playerinfo where Name=$uname and Password=$pass");
    }

    public static function logoutUser($uid){
        $pid = self::prepVar($uid);
        self::removePlayerFromScene($uid);
        self::$db->querySingle("update playerinfo set loggedIn=0 where ID=$uid");
    }
 
    public static function changePlayerScene($uid, $sid, $pname){
        $uid = self::prepVar($uid);
        $sid = self::prepVar($sid);
        $pname = self::prepVar($pname);
        self::removePlayerFromScene($uid);
        self::$db->querySingle("insert into sceneplayers (sceneID,playerID,playerName) values($sid,$uid,$pname)");
        self::$db->querySingle("Update playerinfo set Scene=$sid where ID=$uid");
    }

    private static function removePlayerFromScene($uid){
        $uid = self::prepVar($uid);//twice?
        self::$db->querySingle("delete from sceneplayers where playerID=$uid");
    }

    public static function setLoggedIn($uid, $loginID){
        $uid = self::prepVar($uid);
        $loginID = self::prepVar($loginID);
        self::$db->querySingle("update playerinfo set loggedIn=$loginID, lastLoginTime=CURRENT_TIMESTAMP where ID=$uid");
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
