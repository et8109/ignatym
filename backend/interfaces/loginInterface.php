<?php
require_once("interface.php");

class LoginInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function loginPlayer($uname, $pass){
        $uname = self::prepVar($uname);
        $pass = self::prepVar($pass);
        $r = self::$db->querySingle("select id,Name,Scene from playerinfo where Name=$uname and Password=$pass");
        if($r == false || !isset($r['id'])){
            throw new Exception("incorrect username or password");
        }
        return $r;
    }
    
    public static function getLogin($uname, $pass){
        $uname = self::prepVar($uname);
        $pass = self::prepVar($pass);
        $r = self::$db->querySingle("select ID,Name,Scene,loggedIn from playerinfo where Name=$uname and password=$pass");
        return $r;
    }
    
    public static function setLoggedIn($pid, $loginID){
        $pid = self::prepVar($pid);
        $loginID = self::prepVar($loginID);
        self::$db->querySingle("update playerinfo set loggedIn=$loginID, lastLoginTime=CURRENT_TIMESTAMP where ID=$pid");
    }
}
?>