<?php
include 'shared/initialize.php';
include 'shared/functions.php';
include 'interfaces/setupInterface.php';
try{
    $version = 4;
    if(intval($_POST['version']) != $version){
        sendError("You're using an old version of Ignatym. Clear your cache and try again. ".$_POST['version']."_".$version);
    }
    
    $function = $_POST['function'];
    switch($function){
        case('setUp'):
            $playerInfo = SetupInterface::getPlayerInfo($_SESSION['playerID']);
            echo "<>".$_SESSION['playerID'];
            echo "<>".$playerInfo['Scene'];
            echo "<>".$playerInfo['frontLoadScenes'];
            echo "<>".$playerInfo['frontLoadKeywords'];
            //add player to scene list
            SetupInterface::putPlayerInScene($_SESSION['playerID'], $_SESSION['currentScene'], $_SESSION['playerName']);
            break;
        
        case('frontLoadScenes'):
            $scenesInfo = SetupInterface::getSceneInfo();
            foreach($scenesInfo as $scene){
                echo "<>".$scene['ID']."<>".getSpanText(spanTypes::SCENE,$scene['ID'],$scene['Name'])."<>".$scene['Description'];
            }
            break;
        
        case('frontLoadKeywords'):
            //keyword words to description. //type not needed
            $keywordsInfo = SetupInterface::getKeywordInfo();
            foreach($keywordsInfo as $keywords){
                echo "<>".$keywords['Word']."<>".getSpanText(spanTypes::KEYWORD,$keywords['ID'],$keywords['Word'])."<>".$keywords['Description'];
            }
            break;
    }
}
catch(Exception $e){
    sendError("setup error: ".$e->getMessage());
}
?>