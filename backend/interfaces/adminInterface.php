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
                            "Perm tinyint(1),". //if the alert requires something to be removed
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
                            "playerID int(3) ,".
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
                            "ID int(3) AUTO_INCREMENT,".
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
                            "Name char(20) ,".
                            "Description varchar(1000) ,".
                            "Level int(3) ,".
                            "PRIMARY KEY (ID)".
                            ")");
        
        //player ID -> alert ID
        self::$db->querySingle("CREATE TABLE playeralerts (".
                            "playerID int(3) ,".
                            "alertID int(3) ,".
                            "PRIMARY KEY (playerID)".
                            ")");
        
        //player ID -> player info
        self::$db->querySingle("CREATE TABLE playerinfo (".
                            "ID int(3) AUTO_INCREMENT,".
                            "Name char(20) ,".
                            "Password char(20) ,".
                            "Description varchar(1000) ,".
                            "Scene int(3) ,".
                            "CraftSkill int(1) ,".
                            "Health int(1) ,".
                            "FrontLoadScenes tinyint(1) ,".
                            "FrontLoadKeywords tinyint(1) ,".
                            "Email varchar(35) ,".
                            "LoggedIn int(1) ,".
                            "LastLoginTime timestamp ,".
                            "PRIMARY KEY (ID)".
                            ")");
        
        //player ID -> player keywords
        self::$db->querySingle("CREATE TABLE playerkeywords (".
                            "ID int(3) ,".
                            "keywordID int(3) ,".
                            "locationID int(3) ,".
                            "type int(3) ,".
                            "PRIMARY KEY (ID)".
                            ")");
        
        //scene ID -> scene keywords
        self::$db->querySingle("CREATE TABLE scenekeywords (".
                            "ID int(3) ,".
                            "keywordID int(3) ,".
                            "type int(3) ,".
                            "PRIMARY KEY (ID)".
                            ")");
        
        //scene ID -> scene npcs
        self::$db->querySingle("CREATE TABLE scenenpcs (".
                            "sceneID int(3) ,".
                            "npcID int(3) ,".
                            "npcName char(20) ,".
                            "health int(3) ,".
                            "PRIMARY KEY (sceneID, npcID)".
                            ")");
        
        //start scene ID -> end scene ID
        //two-way paths will need 2 entries
        self::$db->querySingle("CREATE TABLE scenepaths (".
                            "startID int(3) ,".
                            "endID int(3) ,".
                            "PRIMARY KEY (startID, endID)".
                            ")");
        
        //scene ID -> playerID
        self::$db->querySingle("CREATE TABLE sceneplayers (".
                            "sceneID int(3) ,".
                            "playerID int(3) ,".
                            "playerName char(20) ,".
                            "PRIMARY KEY (sceneID, playerID)".
                            ")");
        
        //scene ID -> scene info
        self::$db->querySingle("CREATE TABLE scenes (".
                            "ID int(3) AUTO_INCREMENT,".
                            "Name char(20) ,".
                            "Description varchar(1000) ,".
                            "Appshp tinyint(1) ,".
                            "Town int(3) ,".
                            "Land int(3) ,".
                            "PRIMARY KEY (ID)".
                            ")");
    }
}
?>