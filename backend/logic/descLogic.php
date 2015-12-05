<?php
require_once '../backend/models/descModel.php';

class DescLogic {
    private function __construct() {}//static only

    public static function getUserDesc($uid){
        return DescModel::getDescPlayer($uid)["Description"];
    }

    public static function getSceneDesc($sid){
        $info = DescModel::getDescScene($sid);
        if ($info == null){
	   throw new Exception("Scene not found");
	}
	return $info;
    }

}
            /*case(spanTypes::ITEM):
                $item =  SharedInterface::getDescItem($_POST['ID']);
                sendText(getSpanText(spanTypes::ITEM,$_POST['ID'],$item["Name"]));
                sendText($item["Description"]);
                break;
            case(spanTypes::KEYWORD):
                $kw = SharedInterface::getDescKeyword($_POST['ID']);//currently searches by kw name, not id
                sendText(getSpanText(spanTypes::KEYWORD,$_POST['ID'],$_POST['ID']));
                sendText($kw["Description"]);
                break;
            case(spanTypes::NPC):
                $info = SharedInterface::getDescNpc($_POST['ID']);
                sendText(getSpanText(spanTypes::NPC,$_POST['ID'],$info['Name']));
                sendText($info['Description']);
                break;
            case(spanTypes::SCENE):
                //if no id set, it's the current scene
                $ID = is_numeric($_POST['ID']) ? $_POST['ID'] : $_SESSION['currentScene'];
                $info = SharedInterface::getDescScene($ID);
                sendText(getSpanText(spanTypes::SCENE,$ID,$info["Name"]));
                sendText($info["Description"]);
                //players
                $playersResult = GeneralInterface::getPlayersInScene($_SESSION['currentScene']);
                foreach($playersResult as $player){
                    sendText(getSpanText(spanTypes::PLAYER,$player['playerID'],$player['playerName']));
                }
                //npcs
                $npcsResult = GeneralInterface::getNpcsInScene($_SESSION['currentScene']);
                foreach($npcsResult as $npc){
                    sendText(getSpanText(spanTypes::NPC,$npc['npcID'],$npc['npcName']));
                }
                break;
        }
        break;
    
    case('closeLook'):
        //town and land
        $sceneRow = GeneralInterface::getSceneJobs($_SESSION['currentScene']);
        if($sceneRow == false){
            throw new Exception("Could not find this location");
        }
        $info = [];
        $info["town"] = $sceneRow['town'];
        $info["land"] = $sceneRow['land'];
        $jobsBool = intval($sceneRow['appshp']) > 0 ? "Yes" : "No";
        $info["job"] = $jobsBool;
        //manager
        if(intval($sceneRow['appshp']) > 0){
            $info = GeneralInterface::getSceneWorker(keywordTypes::MANAGER, $_SESSION['currentScene']);
            if($info == false){
                $info["manager"] = "No manager. <span class='active action' onclick='beManager()'>Manage this location.</span>";
            } else{
                $info["manager"] = "Manager: ".$info['Name'];
            }
        }
        //lord
        $lord = GeneralInterface::getSceneWorker(keywordTypes::MANAGER, $sceneRow['town']);
        if($lordRow == false){
            $info["lord"] = "Lord: None. The monarch should appoint one.";
        }
        else{
            $info["lord"] = "Lord: ".$lord['Name'];
        }
        sendInfo($info);
        break;
    
    case('updateDescription'):
        $success = updateDescription($_SESSION['playerID'], $_POST['Description'], spanTypes::PLAYER,$keywordTypeNames);
        if($success){
            removeAlert(alertTypes::newItem);
            removeAlert(alertTypes::removedItem);
            removeAlert(alertTypes::hiddenItem);
            removeAlert(alertTypes::newJob);
            removeAlert(alertTypes::fired);
            removeAlert(alertTypes::newSpell);
        }
        break;
    
    case('moveScenes'):
        //recieve id or name of scene, update this players location in cookie and db
        GeneralInterface::changePlayerScene($_SESSION['playerID'], $_POST['newScene'], $_SESSION['playerName']);
        $_SESSION['currentScene'] = $_POST['newScene'];
        $info = GeneralInterface::getSceneName($_SESSION['currentScene']);
        speakActionWalk($_SESSION['currentScene'],$info['Name']);
        updateChatTime();
        break;
    
    case('destroyItem'):
        //make sure player has item
        $itemRow = SharedInterface::getItemID($_POST['name']);
        if($itemRow == false){
            throw new Exception("could not find item: ".$_POST['name']);
        }
        GeneralInterface::deleteItem($itemRow['ID']);
        Req::insert()->alert($_SESSION['playerID'])->removedItem()->run();
        break;
    
    case('giveItemTo'):
        //find id of reciever
        $playerRow = SharedInterface::getPlayerID($_POST['playerName']);
        if($playerRow == false){
            throw new Exception("Could not find ".$_POST['playerName']." nearby.");
        }
        //find id of item
        $itemRow = SharedInterface::getPlayersItemInfo($_SESSION['playerID'], $_POST['itemName']);
        if($itemRow == false){
            throw new Exception("Could not find ".$_POST['itemName']);
        }
        checkPlayerCanTakeItem($playerRow['ID']);
        removeItemIdFromPlayer($itemRow['ID']);
        addItemIdToPlayer($itemRow['ID'], $_POST['itemName']);
        break;
    
    case('putItemIn'):
        $itemName = prepVar($_POST['itemName']);
        $containerName = prepVar($_POST['containerName']);
        //get item and container info
        $itemRow = SharedInterface::getPlayersItemInfo($_SESSION['playerID'], $itemName);
        $containerRow = SharedInterface::getPlayersItemInfo($_SESSION['playerID'], $containerName);
        //make sure item was found
        if(!isset($itemRow['ID'])){
            throw new Exception("the ".$itemName." was not found");
        }
        //make sure container was found
        if(!isset($containerRow['ID'])){
            throw new Exception("the ".$containerName." was not found");
        }
        //make sure second item is a container
        if($containerRow['room'] == 0){
            throw new Exception("either ".$containerName." is full, or it can not hold any items");
        }
        //make sure the first item is not in something else
        if($itemRow['insideOf'] != 0){
            throw new Exception($itemName." is inside of something else. Remove it first.");
        }
        //make sure the item is not a bag
        $itemIsBagRow = SharedInterface::checkItemHasKeywordType($itemRow['ID'], keywordTypes::CONTAINER);
        if($itemIsBagRow[0] > 0){
            throw new Exception("You can't put a container into another container.");
        }
        //put in
        GeneralInterface::putItemInItem($itemRow['ID'], $containerRow['ID']);
        //add alert
        Req::insert()->alert($_SESSION['playerID'])->hiddenItem()->run();
        break;
    
    case('takeItemFrom'):
        $itemRow = SharedInterface::getPlayersItemInfo($_SESSION['playerID'], $_POST['itemName']);
        $containerRow = SharedInterface::getPlayersItemInfo($_SESSION['playerID'], $_POST['containerName']);
        if($itemRow == false){
            throw new Exception("could not find ".$_POST['itemName']);
        }
        if($containerRow == false){
            throw new Exception("could not find ".$_POST['containerName']);
        }
        //make sure item is in the container
        if($itemRow['insideOf'] != $containerRow['ID']){
            throw new Exception("The ".$_POST['itemName']." is not in the ".$_POST['containerName']);
        }
        GeneralInterface::removeItemFromItem($itemRow['ID'], $containerRow['ID']);
        //add name to desc
        addItemIdToPlayer($itemRow['ID'],$_POST['itemName']);
        break;
    
    case('getItemsInScene'):
        //get item ids
        $itemIDsResult = GeneralInterface::getItemsInScene($_SESSION['currentScene']);
        //store itemID note connection
        $itemNotes = array();
        //get items names and ids
        foreach($itemIDsResult as $item){
            //seperate into <>
            sendInfo(array(
            "item" => true,
            "spanText" => getSpanText(spanTypes::ITEM,$item['itemID'],$item['Name']),
            "note" => $item['note']
            ));
        }
        //materials
        $matIDsResult = GeneralInterface::sceneKeywordsOfType($_SESSION['currentScene'], keywordTypes::MATERIAL);
        //get material names and ids
        foreach($matIDsResult as $mat){
            //seperate into <>
            sendInfo(array(
            "material" => true,
            "spanText" => getSpanText(spanTypes::KEYWORD,$mat['ID'],$mat['Word'])
            ));
        }
        break;
    
    //used for /self
    case('getPlayerInfo'):
        //info
        $playerRow = SharedInterface::getPlayerInfo($_SESSION['playerID']);
        if($playerRow == false){
            throw new Exception("Error finding your stats.");
        }
        $info = array(
            "name" => $playerRow['Name'],
            "ID" => $_SESSION['playerID'],
            "craftSkill" => $playerRow['craftSkill'],
            "health" => $playerRow['health']
            );
        //keywords
        $keywordsResult = GeneralInterface::getPlayerKeywords($_SESSION['playerID']);
        if(count($keywordsResult) < 1){
            $info["keywords"] = "No keywords";
        }
        else{
            $info["keywords"] = "-Keywords:";
            foreach($keywordsResult as $kw){
                $info["keywords"] .= $kw['word'];
                //find location name, if applicable
                if($kw['locationID'] != 0){
                    if(intval($kw['type'])==keywordTypes::LORD){
                        $info["keywords"] .= " of town ".$kw['locationID'];
                    }
                    else if(intval($kw['type'])==keywordTypes::MONARCH){
                        $info["keywords"] .= " of land ".$kw['locationID'];
                    }
                    else{
                        $locationRow = GeneralInterface::getSceneNamequery($kw['locationID']);
                        $info["keywords"] .= " of: ".$locationRow['name'];
                    }
                }
            }
        }
        //items
        $itemsResult = SharedInterface::getTotalItems($_SESSION['playerID']);
        if(count($itemsResult) < 1){
            //no items
            $info["items"] = "No items";
        }
        else{
            $info["items"] = "-Items:";
            foreach($itemsResult as $item){
                $info["items"] .= $item['name'].",";
                //rtrim($string, ",")
            }
        }
        sendInfo($info);
        break;
    
    case('setFrontLoadScenes'):
        GeneralInterface::setFrontLoadScenes($_SESSION['playerID'],$_POST['load']);
        break;
    
    case('setFrontLoadKeywords'):
        GeneralInterface::setFrontLoadKeywords($_SESSION['playerID'], $_POST['load']);
        break;
    
    case('getAlertMessages'):
        //get all alert ids
        $alerts = GeneralInterface::getPlayerAlertMessages($_SESSION['playerID']);
        foreach($alerts as $alert){
             sendText($alert['Description']);
        }
        break;
	*/    
    //case('clearAlerts'):
        /*$permAlerts = array(
            alertTypes::hiddenItem,
            alertTypes::newItem,
            alertTypes::removedItem,
            alertTypes::newJob,
            alertTypes::fired,
            alertTypes::newSpell
        );*/
        /*GeneralInterface::clearAlerts($_SESSION['playerID']);
        break;
    
    case('getTime'):
        sendInfo(array(
        "time" => getTime(),
        "tod" => getTimeOfDayWord()
        ));
        break;
    
    case('logout'):
        GeneralInterface::logoutPlayer($_SESSION['playerID']);
        session_destroy();
        throw new Exception("logged out. <a href='login.php'>Back to login</a>");
        break;
}*/
?>
