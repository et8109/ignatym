<?php
require_once("interface.php");

class LoginInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function loginPlayer($uname, $pass){
        $uname = self::prepVar($uname);
        $pass = self::prepVar($pass);
        $r = self::$db->querySingle("select id,Name,Scene from playerinfo where Name=$uname and Password=$pass");
        if(!isset($r['id'])){
            throw new Exception("incorrect username or password");
        }
        return $r;
    }
}
?>