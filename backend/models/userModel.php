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
        self::removeUserFromScene($uid);
        self::$db->querySingle("update playerinfo set loggedIn=0 where ID=$uid");
    }
 
    public static function changeUserScene($uid, $sid, $uname){
        $uid = self::prepVar($uid);
        $sid = self::prepVar($sid);
        $uname = self::prepVar($uname);
        self::removeUserFromScene($uid);
        self::$db->querySingle("insert into sceneplayers (sceneID,playerID,playerName) values($sid,$uid,$uname)");
        self::$db->querySingle("Update playerinfo set Scene=$sid where ID=$uid");
    }

    private static function removeUserFromScene($uid){
        $uid = self::prepVar($uid);//twice?
        self::$db->querySingle("delete from sceneplayers where playerID=$uid");
    }

    public static function setLoggedIn($uid, $loginID){
        $uid = self::prepVar($uid);
        $loginID = self::prepVar($loginID);
        self::$db->querySingle("update playerinfo set loggedIn=$loginID, lastLoginTime=CURRENT_TIMESTAMP where ID=$uid");
    }

    public static function registerUser($uname, $pword){
        $uname = self::prepVar($uname);
        $pword = self::prepVar($pword);
        self::$db->querySingle("insert into playerinfo (Name, Password, Description, Scene, Health) values ($uname, $pword, 'I\'m new, so be nice to me!', 101, 3)");
        if (self::$db->lastQueryNumRows() != 1){
           throw new Exception("username taken");
	}
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
