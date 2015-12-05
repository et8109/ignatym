<?php
require_once '../backend/models/itemModel.php';

class ItemLogic {
    private function __construct() {}//static only

    /**
    *the keyword types required in all items
    */
    $itemKeywordTypes = array(
        keywordTypes::MATERIAL,
        keywordTypes::QUALITY
    );

    public static function createItem($uid, $iname, $idesc){
        require_once '../backend/models/descModel.php';
        
	/*//make sure the player is a blacksmith
        $level = getPlayerManageLevel();
        if($level != keywordTypes::APPSHP && $level != keywordTypes::MANAGER){
            throw new Exception("You don't have permission to craft here.");
        }*/
        /*//make sure the player can take an item
        checkPlayerCanTakeItem();
        $keywordIDs = array();
        $IdOut = -1;
        //make sure all required keyword types were replaced
        $desc = $_POST['Description'];
        $numTypes = sizeof($itemKeywordTypes);
        for($i=0; $i<$numTypes; $i++){
            $type = $itemKeywordTypes[$i];
            $desc = replaceKeywordType($desc, $type, $IdOut);
            $keywordIDs[$type] = $IdOut;
            if($desc == false){
                throw new Exception("type ".$keywordTypeNames[$type]." keyword was not found");
            }
        }*/
        /*//check for optional keywords
        $tempDesc = replaceKeywordType($desc, keywordTypes::CONTAINER,$IdOut);
        $isContainer = false;
        if($tempDesc != false){
            $desc = $tempDesc;
            $isContainer = true;
            //add to keywords of item
            $itemKeywordTypes[] = keywordTypes::CONTAINER;
            $keywordIDs[keywordTypes::CONTAINER] = $IdOut;
        }*/
        /*//remove materials from scene
        foreach ($itemKeywordTypes as $t){
            CraftingInterface::removeSceneKeyword($_SESSION['currentScene'], $keywordIDs[$t]);
        }*/
        //make sure desc length is less than max
        //checkDescIsUnderMaxLength($desc, spanTypes::ITEM);

        //add the item into db
        $lastID = ItemModel::createItem($uid, $iname, $idesc, false);
        //add the item to itemKeywords with it's keywords
        /*foreach ($itemKeywordTypes as $t){
            CraftingInterface::createItemKeywords($lastID, $keywordIDs[$t], $t);
        }*/
        ItemModel::addItemToPlayer($uid, $lastID, $iname);
    }

    private static function addItemToPlayer($uid, $itemID, $itemName){
        ItemModel::setItemOwner($itemID, $uid);
        //addWordToPlayerDesc(spanTypes::ITEM,$itemID,$itemName,$_SESSION['playerID']);
        DescModel::appendToUserDesc($id, $itemName);
        //add an alert for the player
        //Req::insert()->alert($_SESSION['playerID'])->newItem()->run();
    }

}
