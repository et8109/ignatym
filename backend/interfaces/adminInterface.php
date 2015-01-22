<?php
require_once("interface.php");

class AdminInterface extends Interface_class{
    private function __construct() {}//static only
    
    public static function resetDatabase(){
        $dbName = self::getDatabaseName();
        self::$db->querySingle("DROP DATABASE $dbName");
        self::$db->querySingle("CREATE DATABASE $dbName");
        self::$db->querySingle("USE $dbName");
        
        //alert ID -> alert message
        self::$db->querySingle("CREATE TABLE alerts (".
                            "ID int(3) AUTO_INCREMENT,".
                            "Description varchar(100),".
                            "PRIMARY KEY (ID)".
                            ")");
        
        //item ID -> keywords
        self::$db->querySingle("CREATE TABLE itemkeywords (".
                            "ID int(3) ,".
                            "keywordID int(3) ,".
                            "type int(3) ,".
                            "PRIMARY KEY (ID, keywordID)".
                            ")");
        
        //item ID -> item info
        self::$db->querySingle("CREATE TABLE items (".
                            "ID int(3) AUTO_INCREMENT,".
                            "plauerID int(3) ,".
                            "Name char(20) ,".
                            "Description varchar(500) ,".
                            "room int(3) ,".
                            "insideOf int(3) ,".
                            "PRIMARY KEY (ID)".
                            ")");
        
        //scene ID -> item ID
        self::$db->querySingle("CREATE TABLE itemsinscenes (".
                            "sceneID int(3) ,".
                            "itemID int(3) ,".
                            "note tinytext ,".
                            "PRIMARY KEY (sceneID, itemID)".
                            ")");
        
        //keyword ID -> keyword description
        self::$db->querySingle("CREATE TABLE keywords (".
                            "ID int(3) ,".
                            "Description varchar(255) ,".
                            "PRIMARY KEY (ID)".
                            ")");
        
        //keyword ID -> possible synonyms
        self::$db->querySingle("CREATE TABLE keywordwords (".
                            "Word varchar(20) ,".
                            "ID int(3) ,".
                            "Type int(3) ,".
                            "PRIMARY KEY (ID, Word)".
                            ")");
        
        //npc ID -> npc keywords
        self::$db->querySingle("CREATE TABLE npckeywords (".
                            "ID int(3) ,".
                            "keywordID int(3) ,".
                            "type int(3) ,".
                            "PRIMARY KEY (ID, keywordID)".
                            ")");
        
        //npc ID -> item ID
        self::$db->querySingle("CREATE TABLE npcs (".
                            "ID int(3) ,".
                            "name char(20) ,".
                            "description varchar(1000) ,".
                            "level int(3) ,".
                            "PRIMARY KEY (ID)".
                            ")");
        
        //player ID -> alert ID
        self::$db->querySingle("CREATE TABLE playeralerts (".
                            "playerID int(3) ,".
                            "alertID int(3) ,".
                            "PRIMARY KEY (playerID)".
                            ")");
        
    }
}
?>