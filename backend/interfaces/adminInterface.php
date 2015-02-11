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
                            "CraftSkill int(1) DEFAULT '0',".
                            "Health int(1) ,".
                            "FrontLoadScenes tinyint(1) DEFAULT '0',".
                            "FrontLoadKeywords tinyint(1) DEFAULT '0',".
                            "FrontLoadAlerts tinyint(1) DEFAULT '0',".
                            "Email varchar(35) ,".
                            "LoggedIn int(1) DEFAULT '0',".
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
        
        require_once("registerInterface.php");
        RegisterInterface::registerPlayer("guest", "guest");
        //alerts
        self::$db->querySingle("insert into alerts (Description) values".
                               "(An item has been added to your description. You should edit it soon.),".
                               "(An item of yours has been hidden, you can change your description so it it no longer highlighted.),".
                               "(An item of yours was removed, you should change your description to reflect that.),".
                               "(A new job has been added to your description. You should update it soon.),".
                               "(You have been fired from your job. You should change your description to reflect that.),".
                               "(An employee of yours has quit.),".
                               "(You have a new manager at your job.),".
                               "(There is a new lord of your town.),".
                               "(You have a new employee at your job.),".
                               "(Your manager has quit.),".
                               "(An employee of yours has been fired.),".
                               "(Your manager has been fired.),".
                               "(A new spell has been added to your description. You should update it soon.)"
                               );
        //keywords
        self::$db->querySingle("insert into keywords (Description) values".
                               "(Not very strong, it is usually used for the handles of things. The useful ends of object should have a stronger material.),".
                               "(A strong material, but it must be mined and then smelted into a practical shape.),".
                               "(It does what it's supposed to.),".
                               "(Very fancy. Whoever made this is a skilled craftsman.),".
                               "(This item can hold other items.),".
                                "(You can craft items here.),".
                                "(Apprentice at a shop.),".
                                "(Manager of a shop.),".
                                "(The lord of a town.),".
                                "(The monarch of a land.),".
                                "(Food and drinks are sold here.),".
                                "(There is a calming aura here, and combat is not allowed.),".
                                "(This spellbook explains how to reanimate fallen creatures.),".
                                "(Has the ability to raise fallen creatures.)"
                                );
        //keyword words
        self::$db->querySingle("insert into keywordwords (Word, ID, Type) values".
                               "(animatome, 13, 8),".
                               "(anvil, 6, 3),".
                               "(apprentice, 7, 4),".
                               "(bag, 5, 0),".
                               "(beautiful, 4, 2),".
                               "(exquisite, 4, 2),".
                               "(lord, 9, 6),".
                               "(manager, 8, 5),".
                               "(metal, 2, 1),".
                               "(metallic, 2, 1),".
                               "(monarch, 10, 7),".
                               "(necromancer, 14, 9),".
                               "(plain, 3, 2),".
                               "(pub, 11, 3),".
                               "(sanctuary, 12, 3),".
                               "(simple, 3, 2),".
                               "(wood, 1, 1),".
                               "(wooden, 1, 1)"
                               );
        //npcs
        self::$db->querySingle("insert into npcs (name, description, level) values".
                               "(dustball, A clump of dust floating around with the wind., 2),".
                               "(wanderer, They cover their face and body with large brown cloaks., 5)"
                              );
    }
}
?>