<?php
require_once("interface.php");

class RegisterInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function registerPlayer($uname, $pass){
        $uname = self::prepVar($uname);
        $pass = self::prepVar($pass);
        self::$db->querySingle("insert into playerinfo (Name, Password, Description, Scene, Health) values ($uname, $pass, 'I\'m new, so be nice to me!', 101, 3)");
    }
    
    public static function getNumPlayers(){
        $r = self::$db->querySingle("select count(1) from playerinfo");
        return intval($r['count(1)']);
    }
}
?>