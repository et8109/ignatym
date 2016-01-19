-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ignatymc_main
-- ------------------------------------------------------
-- Server version	5.5.46-0ubuntu0.14.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alerts` (
  `ID` int(3) NOT NULL AUTO_INCREMENT,
  `Description` varchar(100) DEFAULT NULL,
  `Perm` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alerts`
--

LOCK TABLES `alerts` WRITE;
/*!40000 ALTER TABLE `alerts` DISABLE KEYS */;
INSERT INTO `alerts` VALUES (1,'An item has been added to your description. You should edit it soon.',NULL),(2,'An item of yours has been hidden, you can change your description so it it no longer highlighted.',NULL),(3,'An item of yours was removed, you should change your description to reflect that.',NULL),(4,'A new job has been added to your description. You should update it soon.',NULL),(5,'You have been fired from your job. You should change your description to reflect that.',NULL),(6,'An employee of yours has quit.',NULL),(7,'You have a new manager at your job.',NULL),(8,'There is a new lord of your town.',NULL),(9,'You have a new employee at your job.',NULL),(10,'Your manager has quit.',NULL),(11,'An employee of yours has been fired.',NULL),(12,'Your manager has been fired.',NULL),(13,'A new spell has been added to your description. You should update it soon.',NULL);
/*!40000 ALTER TABLE `alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemkeywords`
--

DROP TABLE IF EXISTS `itemkeywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemkeywords` (
  `ID` int(3) NOT NULL DEFAULT '0',
  `keywordID` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`,`keywordID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemkeywords`
--

LOCK TABLES `itemkeywords` WRITE;
/*!40000 ALTER TABLE `itemkeywords` DISABLE KEYS */;
INSERT INTO `itemkeywords` VALUES (12,3),(13,3),(15,3),(16,3),(17,3),(18,3),(19,3),(20,3),(21,3),(22,3),(23,3),(24,3),(25,3),(26,3),(27,3),(28,3);
/*!40000 ALTER TABLE `itemkeywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `ID` int(3) NOT NULL AUTO_INCREMENT,
  `playerID` int(3) DEFAULT NULL,
  `Name` char(20) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `room` int(3) DEFAULT NULL,
  `insideOf` int(3) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (25,1,'sdfds','<span class=\'keyword\' onclick=\'getKwDesc(3, this)\'>simple</span> asd',2,NULL),(28,1,'yolo2','<span class=\'keyword\' onclick=\'getKwDesc(3, this)\'>simple</span>',2,NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemsinscenes`
--

DROP TABLE IF EXISTS `itemsinscenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemsinscenes` (
  `sceneID` int(3) NOT NULL DEFAULT '0',
  `itemID` int(3) NOT NULL DEFAULT '0',
  `note` tinytext,
  PRIMARY KEY (`sceneID`,`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemsinscenes`
--

LOCK TABLES `itemsinscenes` WRITE;
/*!40000 ALTER TABLE `itemsinscenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `itemsinscenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keywords` (
  `ID` int(3) NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keywords`
--

LOCK TABLES `keywords` WRITE;
/*!40000 ALTER TABLE `keywords` DISABLE KEYS */;
INSERT INTO `keywords` VALUES (1,'Not very strong, it is usually used for the handles of things. The useful ends of object should have a stronger material.'),(2,'A strong material, but it must be mined and then smelted into a practical shape.'),(3,'It does what it\'s supposed to.'),(4,'Very fancy. Whoever made this is a skilled craftsman.'),(5,'This item can hold other items.'),(6,'You can craft items here.</br><span class=\'action\' onclick=startCraft(this)>craft</span>'),(7,'Apprentice at a shop.'),(8,'Manager of a shop.'),(9,'The lord of a town.'),(10,'The monarch of a land.'),(11,'Food and drinks are sold here.'),(12,'There is a calming aura here, and combat is not allowed.</br><span class=\'action\' onclick=regen()>regenerate</span>'),(13,'This spellbook explains how to reanimate fallen creatures.'),(14,'Has the ability to raise fallen creatures.');
/*!40000 ALTER TABLE `keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keywordwords`
--

DROP TABLE IF EXISTS `keywordwords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keywordwords` (
  `Word` varchar(20) NOT NULL DEFAULT '',
  `ID` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`,`Word`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keywordwords`
--

LOCK TABLES `keywordwords` WRITE;
/*!40000 ALTER TABLE `keywordwords` DISABLE KEYS */;
INSERT INTO `keywordwords` VALUES ('wood',1),('wooden',1),('metal',2),('metallic',2),('plain',3),('simple',3),('beautiful',4),('exquisite',4),('bag',5),('anvil',6),('apprentice',7),('manager',8),('lord',9),('monarch',10),('pub',11),('sanctuary',12),('animatome',13),('necromancer',14);
/*!40000 ALTER TABLE `keywordwords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npckeywords`
--

DROP TABLE IF EXISTS `npckeywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `npckeywords` (
  `ID` int(3) NOT NULL DEFAULT '0',
  `keywordID` int(3) NOT NULL DEFAULT '0',
  `type` int(3) DEFAULT NULL,
  PRIMARY KEY (`ID`,`keywordID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npckeywords`
--

LOCK TABLES `npckeywords` WRITE;
/*!40000 ALTER TABLE `npckeywords` DISABLE KEYS */;
/*!40000 ALTER TABLE `npckeywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npcs`
--

DROP TABLE IF EXISTS `npcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `npcs` (
  `ID` int(3) NOT NULL AUTO_INCREMENT,
  `Name` char(20) DEFAULT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `Level` int(3) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npcs`
--

LOCK TABLES `npcs` WRITE;
/*!40000 ALTER TABLE `npcs` DISABLE KEYS */;
INSERT INTO `npcs` VALUES (1,'dustball','A clump of dust floating around with the wind.',2),(2,'wanderer','They cover their face and body with large brown cloaks.',5);
/*!40000 ALTER TABLE `npcs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `playeralerts`
--

DROP TABLE IF EXISTS `playeralerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playeralerts` (
  `playerID` int(3) NOT NULL DEFAULT '0',
  `alertID` int(3) DEFAULT NULL,
  PRIMARY KEY (`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playeralerts`
--

LOCK TABLES `playeralerts` WRITE;
/*!40000 ALTER TABLE `playeralerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `playeralerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `playerinfo`
--

DROP TABLE IF EXISTS `playerinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playerinfo` (
  `ID` int(3) NOT NULL AUTO_INCREMENT,
  `Name` char(20) DEFAULT NULL,
  `Password` char(20) DEFAULT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `Scene` int(3) DEFAULT NULL,
  `CraftSkill` int(1) DEFAULT '0',
  `Health` int(1) DEFAULT NULL,
  `FrontLoadScenes` tinyint(1) DEFAULT '0',
  `FrontLoadKeywords` tinyint(1) DEFAULT '0',
  `FrontLoadAlerts` tinyint(1) DEFAULT '0',
  `Email` varchar(35) DEFAULT NULL,
  `LoggedIn` int(1) DEFAULT '0',
  `LastLoginTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playerinfo`
--

LOCK TABLES `playerinfo` WRITE;
/*!40000 ALTER TABLE `playerinfo` DISABLE KEYS */;
INSERT INTO `playerinfo` VALUES (1,'guest','guest','oo ef  <span class=\'item\' onclick=\'getItemDesc(25, this)\'>sdfds</span> sdfds sdfds yolo1 <span class=\'item\' onclick=\'getItemDesc(28, this)\'>yolo2</span>',103,0,0,0,0,0,NULL,0,'2016-01-15 16:29:00'),(4,'yolo','yolo','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-05 05:14:15'),(16,'yar','yar','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-05 05:30:44'),(17,'what','what','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-05 05:30:57'),(21,'yes','yes','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-05 05:34:35'),(23,'heyy','nope','I\'m new, so be nice to me!',100,0,3,0,0,0,NULL,0,'2015-12-05 22:31:12'),(24,'dope','dope','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-05 23:51:49'),(26,'qwe','qwe','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-05 23:52:30'),(28,'q','q','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-05 23:55:08'),(30,'w','w','0yolo',101,0,3,0,0,0,NULL,0,'2015-12-06 00:02:45'),(32,'e','e','0',101,0,3,0,0,0,NULL,0,'2015-12-05 23:57:20'),(33,'r','r','0',101,0,3,0,0,0,NULL,0,'2015-12-05 23:59:13'),(34,'t','t','0',101,0,3,0,0,0,NULL,0,'2015-12-06 00:00:12'),(35,'y','y','0',101,0,3,0,0,0,NULL,0,'2015-12-06 00:01:11'),(36,'a','a','I\'m new, so be nice to me!rags',101,0,3,0,0,0,NULL,0,'2015-12-06 01:08:48'),(37,'z','z','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 00:39:16'),(39,'x','x','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 00:40:48'),(41,'c','c','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 00:43:58'),(43,'v','v','I\'m new, so be nice to me!rags',101,0,3,0,0,0,NULL,0,'2015-12-07 00:44:17'),(46,'b','b','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 00:44:52'),(48,'n','n','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 00:45:21'),(50,'m','m','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 00:46:43'),(52,'l','l','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 00:47:13'),(54,'o','o','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 02:29:05'),(56,'i','i','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 02:29:54'),(57,'h','h','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 02:29:59'),(59,'g','g','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 22:54:07'),(61,'d','d','I am a new player and i got soem rags <span class=\'item\' onclick=\'getItemDesc(18, this)\'>blarg</span> . dope. <span class=\'item\' onclick=\'getItemDesc(16, this)\'>yoloy</span> qwe . im awesome.',102,0,5,0,0,0,NULL,0,'2015-12-30 05:05:09'),(62,'qw','qw','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 04:59:33'),(64,'qa','qa','I\'m new, so be nice to me!',101,0,3,0,0,0,NULL,0,'2015-12-07 05:00:17'),(66,'as','as','Me and some <span class=\'keyword\' onclick=\'addDesc(12)\'>rags</span> .',101,0,3,0,0,0,NULL,0,'2015-12-07 05:05:11'),(67,'q1','q1','Me and some <span class=\'keyword\' onclick=\'addDesc(13)\'>rags</span> .',101,0,3,0,0,0,NULL,0,'2015-12-07 23:06:19');
/*!40000 ALTER TABLE `playerinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `playerkeywords`
--

DROP TABLE IF EXISTS `playerkeywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playerkeywords` (
  `ID` int(3) NOT NULL DEFAULT '0',
  `keywordID` int(3) DEFAULT NULL,
  `locationID` int(3) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playerkeywords`
--

LOCK TABLES `playerkeywords` WRITE;
/*!40000 ALTER TABLE `playerkeywords` DISABLE KEYS */;
/*!40000 ALTER TABLE `playerkeywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenekeywords`
--

DROP TABLE IF EXISTS `scenekeywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenekeywords` (
  `ID` int(3) NOT NULL DEFAULT '0',
  `keywordID` int(3) DEFAULT NULL,
  `type` int(3) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenekeywords`
--

LOCK TABLES `scenekeywords` WRITE;
/*!40000 ALTER TABLE `scenekeywords` DISABLE KEYS */;
INSERT INTO `scenekeywords` VALUES (100,11,3),(101,12,3),(102,6,3),(104,13,8);
/*!40000 ALTER TABLE `scenekeywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenenpcs`
--

DROP TABLE IF EXISTS `scenenpcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenenpcs` (
  `sceneID` int(3) NOT NULL DEFAULT '0',
  `npcID` int(3) NOT NULL DEFAULT '0',
  `npcName` char(20) DEFAULT NULL,
  `health` int(3) DEFAULT NULL,
  PRIMARY KEY (`sceneID`,`npcID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenenpcs`
--

LOCK TABLES `scenenpcs` WRITE;
/*!40000 ALTER TABLE `scenenpcs` DISABLE KEYS */;
INSERT INTO `scenenpcs` VALUES (103,1,'dustball',5),(103,2,'wanderer',5);
/*!40000 ALTER TABLE `scenenpcs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenepaths`
--

DROP TABLE IF EXISTS `scenepaths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenepaths` (
  `startID` int(3) NOT NULL DEFAULT '0',
  `endID` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`startID`,`endID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenepaths`
--

LOCK TABLES `scenepaths` WRITE;
/*!40000 ALTER TABLE `scenepaths` DISABLE KEYS */;
INSERT INTO `scenepaths` VALUES (100,101),(100,103),(101,100),(101,102),(101,104),(102,101),(103,100),(104,101);
/*!40000 ALTER TABLE `scenepaths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenes`
--

DROP TABLE IF EXISTS `scenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenes` (
  `ID` int(3) NOT NULL AUTO_INCREMENT,
  `Name` char(20) DEFAULT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `Appshp` tinyint(1) DEFAULT NULL,
  `Town` int(3) DEFAULT NULL,
  `Land` int(3) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenes`
--

LOCK TABLES `scenes` WRITE;
/*!40000 ALTER TABLE `scenes` DISABLE KEYS */;
INSERT INTO `scenes` VALUES (100,'Pub','browse the <span class=\'keyword\' onclick=\'getKwDesc(11, this)\'>pub</span> . To the north is the <span class=\'path\' onclick=\'walk(103)\'>Wilderness</span> . Back to the <span class=\'path\' onclick=\'walk(101)\'>Town_Square</span> ?',1,1,1),(101,'Town_Square','yolo <span class=\'keyword\' onclick=\'getKwDesc(12, this)\'>sanctuary</span> yolo. goes to the <span class=\'path\' onclick=\'walk(100)\'>Pub</span> north, <span class=\'path\' onclick=\'walk(102)\'>Blacksmith</span> south, <span class=\'path\' onclick=\'walk(104)\'>Library</span> east.',0,1,1),(102,'Blacksmith','crafting place. North is the <span class=\'path\' onclick=\'walk(101)\'>Town_Square</span> . There is an <span class=\'keyword\' onclick=\'getKwDesc(6, this)\'>anvil</span> here. test. yolo',1,1,1),(103,'Wilderness','Sparse trees decorate the otherwise barren landscape. The town\'s <span class=\'path\' onclick=\'walk(100)\'>Pub</span> lies to the south.',0,0,1),(104,'Library','An old building, with lines of wooden shelves. Most are empty, but a few books are held here for the public to read. On the closest shelf you see a copy of the <span class=\'keyword\' onclick=\'getKwDesc(13, this)\'>animatome</span> .The exit leads to the <span class=\'path\' onclick=\'walk(101)\'>Town_Square</span> .',0,1,1);
/*!40000 ALTER TABLE `scenes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-15 11:46:03
