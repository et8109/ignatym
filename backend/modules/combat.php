<?php

require_once 'interfaces/combatInterface.php';

$function = $_POST['function'];
switch($function){
    case('attack'):
        //check player health
        $healthRow = SharedInterface::getPlayerInfo($_SESSION['playerID']);
        if(!$healthRow || $healthRow['health'] <= 0){
            throw new Exception("Heal yourself first.");
        }
        //check sanctuary
        $sceneRow = CombatInterface::getSceneKeywordIDs($_SESSION['currentScene']));
        if(in_array(keywordIDs::SANCTUARY, $sceneRow){
            throw new Exception("You cannot fight in a sanctuary.");
        }
        $targetID;
        $targetSpanType;
        $opponentCombatLevel;
        //see if player is there
        $row = CombatInterface::getPlayerFromScene($_SESSION['currentScene'], $_POST['Name']);
        if($row != false){
            $targetID = $row['playerID'];
            $targetSpanType = spanTypes::PLAYER;
            $opponentCombatLevel = getPlayerCombatLevel($targetID);
        }
        else{
            $row = CombatInterface::getNpcFromScene($_SESSION['currentScene'], $_POST['Name']);
            if($row != false){
                $targetID = $row['npcID'];
                $targetSpanType = spanTypes::NPC;
                $opponentCombatLevel = SharedInterface::getNpcInfo($targetID);
            } else{
                throw new Exception($_POST['Name']." is not nearby");
            } 
        }
        //determine outcome
        $actionWords;
        $playerCombatLevel = getPlayerCombatLevel($_SESSION['playerID']);
        //math
        $chance = $playerCombatLevel/($playerCombatLevel + $opponentCombatLevel);
        $win = ((rand(0,10)*.1) < $chance);
        if($win){
            $actionWords = " struck ";
            //lower health
            if($targetSpanType == spanTypes::PLAYER){
                CombatInterface::lowerPlayerHealth($targetID, 1);
            } else if($targetSpanType == spanTypes::NPC){
                $killRow = CombatInterface::lowerNpcHealth($targetID, 1, $_SESSION['currentScene']);
                if($killRow['health'] == 0){
                    $actionWords = " defeated ";
                    //if a killing blow, check for npc material
                    $materialRow = CombatInterface::sceneKeywordsOfType($targetID, keywordTypes::MATERIAL, $_SESSION['currentScene']);
                    if($materialRow){
                        //if a material is available, check for job in craft scene
                        $jobLoc = SharedInterface::getJobType($_SESSION['playerID']);
                        if($jobLoc['type'] == keywordTypes::APPSHP || $jobLoc['type'] == keywordTypes::MANAGER){
                            //if a crafter, check amount of materials
                            $numMatRow = CombatInterface::getSceneTotalResources($jobLoc['locationID']);
                            if($numMatRow < constants::maxSceneItems){
                                $matName = SharedInterface::getSingleKeywordFromID($materialRow['keywordID']);
                                $actionWords = " looted ".$matName['Word']." from ";
                                //add material to craft job scene
                                CombatInterface::addSceneKeyword($jobLoc['locationID'],$materialRow['keywordID'],keywordTypes::MATERIAL);
                            }
                        }
                    }
                }
            }
        }
        else{
            $actionWords = " was blocked by ";
            //lower health
            lowerPlayerHealth($_SESSION['playerID'], 1);
        }
        $actionWords .= $playerCombatLevel." -> ".$opponentCombatLevel." ";
        speakActionAttack($targetSpanType,$targetID,$_POST['Name'],$actionWords);
        break;
    
    case('regen'):
        //check if in sanctuary
        $sceneRow = CombatInterface::getSceneKeywordIDs($_SESSION['currentScene']));
        if(!in_array(keywordIDs::SANCTUARY, $sceneRow){
            throw new Exception("You can only regenerate in a sanctuary.");
        }
        //set health to max
        CombatInterface::setPlayerHealth($_SESSION['playerID'], constants::maxHealth);
        break;
}

/**
 *Gets the combat level of the player/npc
 *Does not check if they are nearby
 */
function getPlayerCombatLevel($playerID){
    /**
    * keyword ID => increase in combat skill
    */
   $combatItemKeywords = array(
       1 => 1,//wood
       2 => 2 //metal
   );
    //set initial
    $combatLevel = 1;
    //get player item ids
    $keywordIdRows = SharedInterface::getVisibleItemKeywords($playerID);
    foreach($keywordIdRows as $kw){
        if(isset( $combatItemKeywords[$kw['keywordID']])){
            $combatLevel += $combatItemKeywords[$kw['keywordID']];
        }
    }
    return $combatLevel;
}
?>