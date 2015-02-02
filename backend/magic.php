<?php

require_once 'shared/initialize.php';
require_once 'interfaces/magicInterface.php';

//books to spells
$bookToClass = array(
    13 => 14, //animatome to necromancer
    15 => 16  //zephytome to percipitator
);
$spellToClass = array(
    "reanimate" => 14, //necromancer
    "summon boss" => 14,
    "rainfall" => 16, //percipitator
    "sunshine" => 16
);
switch($_POST['function']){
    
    case('readBook')://see book contents
        //make sure book exists
        $IdRow = MagicInterface::getKeywordID($_POST['bookName'], keywordTypes::SPELLBOOK);
        if($IdRow == false){
            sendError("Could not find the ".$_POST['bookName']." here.");
        }
        //make sure scene has spellbook
        $bookRow = SharedInterface::checkSceneKeyword($_SESSION['currentScene'], $IdRow['ID'], keywordTypes::SPELLBOOK);
        if($bookRow[0] != 1){
            sendError("Could not find the ".$_POST['bookName']." here.");
        }
        //display spellbook text
        switch($_POST['bookName']){
            case("animatome"):
                echo "You open the frail pages of the leatherbound book. The first line reads: How to <b>reanimate</b> the dead or <b>summon a boss</b>. Following is a strange sequence of instructions and illustrations.";
                break;
            case("zephytome"):
                echo "The pages of the ancient book feel damp, but they must be dry. The first line reads: How to call forth <b>rainfall</b> and other weather conditions. Following is a strange sequence of instructions and illustrations.";
                break;
        }
        
        break;
    
    case('learnSpell')://learn book contents
        //make sure scene has spellbook
        $IdRow = MagicInterface::getKeywordID($_POST['bookName'], keywordTypes::SPELLBOOK);
        if($IdRow == false){
            sendError("Could not find the ".$_POST['bookName']." here.");
        }
        $bookRow = SharedInterface::checkSceneKeyword($_SESSION['currentScene'], $IdRow['ID'], keywordTypes::SPELLBOOK);
        if($bookRow[0] != 1){
            sendError("Could not find the ".$_POST['bookName']." here.");
        }
        //make sure player does not have a spell
        $spellRow = MagicInterface::checkPlayerKeywordType($_SESSION['playerID'], keywordTypes::SPELL);
        if($spellRow[0] == 1){
            sendError("You already know a spell. You would have to forget that one first.");
        }
        //give spell to player
        addKeywordToPlayer($bookToClass[$IdRow['ID']],keywordTypes::SPELL,0,$_SESSION['playerID']);
        //add new spell alert
        addAlert(alertTypes::newSpell);
        break;
    
    case("forgetSpell"):
        $forgetRow = MagicInterface::removePlayerKeywordType($_SESSION['playerID'], keywordTypes::SPELL);
        break;
    
    case("castSpell"):
        if(!isset($spellToClass[$_POST['name']])){
            sendError($_POST['name']." is not a spell.");
        }
        //make sure they have the spell
        $spellRow = MagicInterface::checkPlayerKeyword($_SESSION['playerID'], $spellToClass[$_POST['name']], keywordTypes::SPELL);
        if($spellRow[0] != 1){
            sendError("You can't cast ".$_POST['name']);
        }
        //cast
        switch($_POST['name']){
            case('reanimate'):
                //revive nearby enemies
                $numRisen = MagicInterface::regenNpcType($_SESSION['currentScene'], npcTypes::CREATURE, constants::maxHealth);
                if($numRisen > 0){
                    echo "You give new life to ".$numRisen." dead creatures nearby.";
                } else{
                    echo "Your spell fizzles, no effect.";
                }
                break;
            case('summon boss'):
                $resRow = MagicInterface::regenNpcType($_SESSION['currentScene'], npcTypes::BOSS, constants::maxHealth);
                if(lastQueryNumRows() == 0){
                    sendError("Could not summon the boss here.");
                }
                //create hear effect nearby
                $posQuery = MagicInterface::getSceneCoords($_SESSION['currentScene']);
                $currentX = $posQuery['posx'];
                $currentY = $posQuery['posy'];
                $scenes = nearbyScenes(3);
                foreach($scenes as $sceneID){
                    //get direction
                    $posQuery = MagicInterface::getSceneCoords($sceneID);
                    $dir = getSceneDir($currentX,$currentY,$posQuery['posx'],$posQuery['posy']);
                    speakActionMessage($sceneID,"You hear the roar of a boss to the ".$dir);
                }
                echo "A boss risies to your challenge.";
                break;
            
            case("rainfall"):
                //set raining constant in db
                $rainQuery = setRaining(1);
                if(lastQueryNumRows() == 1){
                    //speakaction that it is raining to all scenes
                    globalMessage("It starts raining..");
                    echo "You call down the rain from the sky";
                } else{
                    echo "It's already raining";
                }
                break;
            
            case("sunshine"):
                //set raining constant in db
                $rainQuery = setRaining(0);
                if(lastQueryNumRows() == 1){
                    //speakaction that it is raining to all scenes
                        globalMessage("The sun begins to shine though the clouds..");
                    echo "You call forth the sun to shine";
                } else{
                    echo "The sun is already out";
                }
                break;
        }
        //respond with text
        break;
}
?>