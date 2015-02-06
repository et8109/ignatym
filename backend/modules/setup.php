<?php

require_once ('interfaces/setupInterface.php');

$version = 4;
if(intval($_POST['version']) != $version){
    throw new Exception("You're using an old version of Ignatym. Clear your cache and try again. ".$_POST['version']."_".$version);
}

$function = $_POST['function'];
switch($function){
    case('setUp'):
        $playerInfo = SetupInterface::getPlayerInfo($_SESSION['playerID']);
        sendInfo(array(
        "pid" => $_SESSION['playerID'],
        "sceneID" => $playerInfo['Scene'],
        "fls" => $playerInfo['frontLoadScenes'],
        "flkw" => $playerInfo['frontLoadKeywords']
        ));
        //add player to scene list
        SetupInterface::putPlayerInScene($_SESSION['playerID'], $_SESSION['currentScene'], $_SESSION['playerName']);
        break;
    
    case('frontLoadScenes'):
        $scenesInfo = SetupInterface::getSceneInfo();
        foreach($scenesInfo as $scene){
            sendInfo(array(
            "sid" => $scene['ID'],
            "spanText" => getSpanText(spanTypes::SCENE,$scene['ID'],$scene['Name']),
            "desc" => $scene['Description']
            ));
        }
        break;
    
    case('frontLoadKeywords'):
        //keyword words to description. //type not needed
        $keywordsInfo = SetupInterface::getKeywordInfo();
        foreach($keywordsInfo as $keywords){
            sendInfo(array(
            "word" => $keywords['Word'],
            "spanText" => getSpanText(spanTypes::KEYWORD,$keywords['ID'],$keywords['Word']),
            "desc" => $keywords['Description']
            ));
        }
        break;
}
?>