<?php

require_once 'interfaces/craftingInterface.php';

/**
 *the keyword types required in all items
 */
$itemKeywordTypes = array(
    keywordTypes::MATERIAL,
    keywordTypes::QUALITY
);

$function = $_POST['function'];
switch($function){
        /**
         *adds the item to the item list
         *adds the item's id to the player's item list
         *adds the item to the player's description.
         *adds an alert for the player
         */
    case('craftItem'):
        //make sure the player is a blacksmith
        $level = getPlayerManageLevel();
        if($level != keywordTypes::APPSHP && $level != keywordTypes::MANAGER){
            throw new Exception("You don't have permission to craft here.");
        }
        //make sure the player can take an item
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
        }
        //check for optional keywords
        $tempDesc = replaceKeywordType($desc, keywordTypes::CONTAINER,$IdOut);
        $isContainer = false;
        if($tempDesc != false){
            $desc = $tempDesc;
            $isContainer = true;
            //add to keywords of item
            $itemKeywordTypes[] = keywordTypes::CONTAINER;
            $keywordIDs[keywordTypes::CONTAINER] = $IdOut;
        }
        //remove materials from scene
        foreach ($itemKeywordTypes as $t){
            CraftingInterface::removeSceneKeyword($_SESSION['currentScene'], $keywordIDs[$t]);
        }
        //make sure desc length is less than max
        checkDescIsUnderMaxLength($desc, spanTypes::ITEM);
        //add the item into db
        $lastID = createItem($_SESSION['playerID'], $_POST['Name'], $desc, $isContainer);
        //add the item to itemKeywords with it's keywords
        foreach ($itemKeywordTypes as $t){
            CraftingInterface::createItemKeywords($lastID, $keywordIDs[$t], $t);
        }
        addItemIdToPlayer($lastID, $_POST['Name']);
        break;
    
    case('getCraftInfo'):
        $row = SharedInterface::getPlayerInfo($_SESSION['playerID']);
        sendInfo(array(
            "craftInfo" => true,
            "info" => $row['craftSkill']
        ));
        break;
}

/**
 *replaces the first keyword of the given type in the given desc
 *returns error on insufficient materials
 *returns false on not type not found
 *should work for scene actions if corrent kwt is given
 */
function replaceKeywordType($desc, $keywordType, &$IdOut){
    //find prerequisites
    $minID = 0;
    switch($keywordType){
        case(keywordTypes::QUALITY):
            $row = SharedInterface::getPlayerInfo($_SESSION['playerID']);
            if($row == false){
                throw new Exception("error finding craft level");
            }
            switch($row['craftSkill']){
                case(0):
                    $minID = 3;
                    break;
                case(1):
                    $minID = 4;
                    break;
            }
            break;
    }
    //find and replace the word
    $descArray = explode(" ",$desc);
    $descArrayLength = count($descArray);
    for($i=0; $i<$descArrayLength; $i++){
        $word = $descArray[$i];
        $keywordRow = CraftingInterface::getKeywordID(strtolower($word), $keywordType, $minID);
        if(isset($keywordRow['ID'])){
            //if a material, make sure it is available
            if($keywordType == keywordTypes::MATERIAL){
                $numMatRow = SharedInterface::checkSceneKeyword($_SESSION['currentScene'], $keywordRow['ID'], keywordTypes::MATERIAL);
                if($numMatRow[0] < 1){
                    throw new Exception("You don't have enough material for: ".$word);
                }
            }
            //find correct span to replace with
            $spanType = spanTypes::KEYWORD;
            if($keywordType == keywordTypes::SCENE_ACTION){
                $spanType = spanTypes::ACTION;
            }
            $descArray[$i] = getSpanText($spanType,$descArray[$i],$descArray[$i]);
            $IdOut = $keywordRow['ID'];
            return implode(" ",$descArray);
        }
    }
    return false;
}
?>