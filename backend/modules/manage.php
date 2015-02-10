<?php

require_once 'interfaces/manageInterface.php';

$function = $_POST['function'];
switch($function){
    case('addItemToScene'):
        //must be at least an apprentice
        if(getPlayerManageLevel() < keywordTypes::APPSHP){
            sendError("You don't have permission");
        }
        if(itemTypeInScene() == -1){
            sendError("Items cannot be stored here.");
        }
        //get item id
        $idRow = SharedInterface::getPlayersItemInfo($_SESSION['playerID'], $_POST['Name']);
        if(is_bool($idRow)){
            sendError("You do not have a ".$_POST['Name']);
        }
        //make sure it's not a container
        $itemIsBagRow = SharedInterface::checkItemHasKeywordType($idRow['ID'], keywordTypes::CONTAINER);
        if($itemIsBagRow[0] > 0){
            sendError("You can't put a container into a location.");
        }
        //make sure scene has less than max items
        $numItems = ManageInterface::countItemsInScene($_SESSION['currentScene']);
        if($numItems[0] >= constants::maxSceneItems){
            sendError("This location is full already");
        }
        //remove item from player
        removeItemIdFromPlayer($idRow['ID']);
        //add item to items in scenes, along with note
        ManageInterface::addItemToScene($_SESSION['currentScene'], $idRow['ID'], $_POST['Note']);
        break;
    
    case('removeItemFromScene'):
        //must be at least manager
        if(getPlayerManageLevel() < keywordTypes::MANAGER){
            sendError("You don't have permission");
        }
        if(itemTypeInScene() == -1){
            sendError("Items cannot be removed from here.");
        }
        //get item id
        $idRow = SharedInterface::getItemName($_POST['Name']);
        if(is_bool($idRow)){
            sendError("That item does not exist");
        }
        //make sure the player can take an item
        checkPlayerCanTakeItem();
        //remove item from scene list
        ManageInterface::removeItemFromScene($_SESSION['currentScene'], $idRow['ID']);
        addItemIdToPlayer($idRow['ID'], $_POST['Name']);
        break;
    
    case('changeItemNote'):
        //must be at least apprentice
        if(getPlayerManageLevel() < keywordTypes::APPSHP){
            sendError("You don't have permission");
        }
        if(itemTypeInScene() == -1){
            sendError("Item notes cannot be changed here.");
        }
        $idRow = SharedInterface::getItemName($_POST['Name']);
        if($idRow == false){
            sendError($_POST['Name']." does not exist.");
        }
        //get item id
        $itemRow = ManageInterface::checkItemInScene($_SESSION['currentScene'], $idRow['ID']);
        if($itemRow[0] != 1){
            sendError($_POST['Name']." not found in this location.");
        }
        ManageInterface::changeItemNote($idRow['ID'], $_POST['Note']);
        break;
    
    case('changeSceneDesc'):
        $manageLevel = getPlayerManageLevel();
        if($manageLevel == keywordTypes::MONARCH){
            updateDescription($_SESSION['currentScene'],$_POST['desc'],spanTypes::SCENE,$keywordTypeNames);
        }
        else{
            //else, no permission
            sendError("You don't have permission");
        }
        break;
    
    case('getManageSceneText'):
        //find player manage level
        $manageLevel = getPlayerManageLevel();
        //can't manage anything
        if($manageLevel == 0){
            sendError("You cannot manage this location");
        }
        addText("<span class='active action' onclick='quitJobPrompt()'>quit job</span>");
        addText("<span class='active action' onclick='getItemsInScene()'>view items</span>");
        //if items are accepted
        if(itemTypeInScene() != -1){
            addText("<span class='active action' onclick='addItemToScenePrompt()'>add item</span>");
            addText("<span class='active action' onclick='changeItemNotePrompt()'>change an items note</span>");
            if ($manageLevel >= keywordTypes::MANAGER) {
                //at least manager
                addText("<span class='active action' onclick='removeItemFromScenePrompt()'>take item</span>");
            }
        }
        if ($manageLevel >= keywordTypes::LORD) {
            //at least lord
        }
        if ($manageLevel >= keywordTypes::MONARCH) {
            //at least monarch
            addText("<span class='active action' onclick='newSceneDescPrompt()'>edit scene desc</span>");
            addText("can't edit scene title yet");
        }
        break;
    
    case('becomeManager'):
        //make sure there are jobs here
        if(!checkLocationAcceptsApprentice()){
            sendError("There are no jobs here."); 
        }
        //make sure there is no manager already
        $positionRow = getPlayerIDFromSceneJob($_SESSION['currentScene'], keywordTypes::MANAGER);
        if(isset($positionRow['ID'])){
            sendError("Someone is already a manager here.");
        }
        //make sure they don't have a job
        if(checkPlayerHasJob()){
            sendError("You already have a job.");
        }
        //add keyword
        addKeywordToPlayer(8,keywordTypes::MANAGER,$_SESSION['currentScene'],$_SESSION['playerID']);
        //let employee know
        addAlert(alertTypes::newJob);
        //let above and below know
        ManageInterface::alertManagersApprentaces(alertTypes::newManager, keywordTypes::APPSHP, $_SESSION['currentScene']);
        ManageInterface::alertManagersLord(alertTypes::newEmployee, keywordTypes::LORD, $_SESSION['currentScene']);
        break;
    
    case("hireEmployee"):
        //get employeeID
        $IdRow = SharedInterface::getPlayerID($_POST['name']);
        if($IdRow == false){
            sendError($_POST['name']." was not found");
        }
        $employeeID = $IdRow['ID'];
        $employeeKeywordRow = getJobType($employeeID);
        if($employeeKeywordRow != false){
            sendError("They already have a job");
        }
        $manageRow = getJobType($_SESSION['playerID']);
        //player has no job
        if($manageRow == false){
            sendError("You have no job");
        }
        $playerManageLevel = $manageRow['type'];
        if($playerManageLevel == keywordTypes::APPSHP){
            sendError("You cannot hire anyone to work for you");
        }
        if($playerManageLevel == keywordTypes::MANAGER){
            if($manageRow['locationID'] != $_SESSION['currentScene']){
                sendError("You don't work here");
            }
            if(!checkLocationAcceptsApprentice()){
               sendError("There are no jobs here"); 
            }
            $startingKeywordID = 7;
            $position = keywordTypes::APPSHP;
            $location = $_SESSION['currentScene'];
        }
        if($playerManageLevel == keywordTypes::LORD){
            //make sure they work here
            $townRow = getSceneLandInfo($_SESSION['currentScene']);
            if($manageRow['locationID'] != $townRow['town']){
                sendError("You don't rule this town");
            }
            if(!checkLocationAcceptsApprentice()){
               sendError("There are no jobs here"); 
            }
            //make sure there is no manager already
            $positionRow = getPlayerIDFromSceneJob($_SESSION['currentScene'], keywordTypes::MANAGER);
            if(isset($positionRow['ID'])){
                sendError("Someone already has that position");
            }
            //let thier new employees know
            ManageInterface::alertManagersApprentaces(alertTypes::newManager, keywordTypes::APPSHP, $_SESSION['currentScene']);
            $startingKeywordID = 8;
            $position = keywordTypes::MANAGER;
            $location = $_SESSION['currentScene'];
        }
        if($playerManageLevel == keywordTypes::MONARCH){
            //get id of current town,land
            $townRow = getSceneLandInfo($_SESSION['currentScene']);
            //make sure they work here
            if($manageRow['locationID'] != $townRow['land']){
                sendError("You don't rule this land");
            }
            //make sure there is no lord already
            $positionRow = getPlayerIDFromSceneJob($townRow['town'], keywordTypes::MANAGER);
            if($positionRow[0] == 1){
                sendError("Someone already has that position");
            }
            ///let their new manager's know
            ManageInterface::alertLordsManagers(alertTypes::newLord, keywordTypes::MANAGER, $_SESSION['currentScene']);
            $startingKeywordID = 9;
            $position = keywordTypes::LORD;
            $location = $townRow['town'];
        }
        //add keyword
        addKeywordToPlayer($startingKeywordID,$position,$location,$employeeID);
        //let employee know
        addAlert(alertTypes::newJob, $employeeID);
        break;
    
    case("fireEmployee"):
        //get employee ID
        $employeeRow = SharedInterface::getPlayerID($_POST['name']);
        if($employeeRow == false){
            sendError("Player not found");
        }
        $managerRow = SharedInterface::getJobType($_SESSION['playerID']);
        if($managerRow == false){
            sendError("You have no job");
        }
        $managerLevel = intval($managerRow['type']);
        switch($managerLevel){
            //apprentace has no one to fire
            case(keywordTypes::APPSHP):
                sendError("You don't have any employees");
                break;
            //manager fires apprentice
            case(keywordTypes::MANAGER):
                //make sure they work for you
                $jobRow = SharedInterface::getJobType($employeeRow['ID']);
                if($jobRow == false || $jobRow['locationID'] != $managerRow['locationID'] || $jobRow['type'] != keywordTypes::APPSHP){
                    sendError("Player does not work for you");
                }
                break;
            //lord fires manager
            case(keywordTypes::LORD):
                //find the location ID of the manager
                $locationRow = SharedInterface::getJobType($employeeRow['ID']);
                if($locationRow == false || $locationRow['type'] != keywordTypes::MANAGER){
                    sendError("Player does not work for you");
                }
                //make sure they work for you
                $jobRow = SharedInterface::getSceneLandInfo($locationRow['locationID']);
                if(intval($jobRow['town']) != $managerRow['locationID']){
                    sendError("Player does not work for you");
                }
                ManageInterface::alertManagersApprentaces(alertTypes::managerFired, keywordTypes::APPSHP, $_SESSION['currentScene']);
                break;
            //monarch fires lord
            case(keywordTypes::MONARCH):
                //find the location ID of the lord
                $locationRow = SharedInterface::getJobType($employeeRow['ID']);
                if($locationRow == false || $locationRow['type'] != keywordTypes::LORD){
                    sendError("Player does not work for you");
                }
                //make sure they work for you
                $jobRow = SharedInterface::getSceneLandInfo($locationRow['locationID']);
                if(intval($jobRow['land']) != $managerRow['locationID']){
                    sendError("Player does not work for you");
                }
                ManageInterface::alertLordsManagers(alertTypes::lordFired, keywordTypes::MANAGER, $_SESSION['currentScene']);
                break;
        }
        //on success:
        ManageInterface::removePlayerJob($employeeRow['ID']);
        //give alert to fired employee
        addAlert(alertTypes::fired,$employeeRow['ID']);
        break;
    
    case('quitJob'):
        //make sure player has a job
        if(!checkPlayerHasJob()){
            sendError("You have no job");
        }
        //get job type and location
        $jobRow = SharedInterface::getJobType($_SESSION['playerID']);
        $jobType = intval($jobRow['type']);
        //remove job
        ManageInterface::removePlayerJob($_SESSION['playerID']);
        //let above and below know
        if($jobType == keywordTypes::APPSHP){
            ManageInterface::alertalertApprentacesManager(alertTypes::employeeQuit, keywordTypes::MANAGER, $_SESSION['currentScene']);
        }
        elseif($jobType == keywordTypes::MANAGER){
            ManageInterface::alertManagersApprentaces(alertTypes::managerQuit, keywordTypes::APPSHP, $_SESSION['currentScene']);
            ManageInterface::alertManagersLord(alertTypes::managerQuit, keywordTypes::LORD, $_SESSION['currentScene']);
        }
        elseif($jobType == keywordTypes::LORD){
            ManageInterface::alertLordsManagers(alertTypes::lordQuit, keywordTypes::MANAGER, $_SESSION['currentScene']);
            ManageInterface::alertLordsMonarch(alertTypes::lordQuit, keywordTypes::MONARCH, $_SESSION['currentScene']);
        }
        elseif($jobType == keywordTypes::LORD){
            ManageInterface::alertMonarchsLords(alertTypes::monarchQuit, keywordTypes::MANAGER, $_SESSION['currentScene']);
        }
        break;
}

/**
 *returns true if the player has a job, false if not
 */
function checkPlayerHasJob(){
    //make sure player has no job
    $playerRow = SharedInterface::getJobType($employeeRow['ID']);
    return ($playerRow != false);
}

/**
 *returns true if the locations accepts apprentices, false if not
 */
function checkLocationAcceptsApprentice(){
    //make sure the location accepts/has room for apprentice
    $sceneRow = ManageInterface::checkLocationAcceptsApprentice($_SESSION['currentScene']);
    return ($sceneRow[0] > 0);
}

/**
 *sends an email to the player after checking email settings
 */
function sendEmail($playerID, $header, $body){
    //check email settings**
    //headers
    $headerSubject = "Ignatym: ";
    $headerBody = "**If you do not know where this email came from, please disregard it. Sorry!**
    
    This email is from Ignatym. You can change your email settings by logging in.
    
    ";
    $footnoteBody = "
    Contact us though the forums at ignatym.freeforums.net or contact@ignatym.com
    Have a nice day!";
    
    //send email
    mail($playerID, $headerSubject.$header, $headerBody.$body.$footnoteBody);
    
}

/**
 *returns the keyword type that items need to be placed in this scene
 *-1 means no items accepted
 *0 means all items accepted
 */
function itemTypeInScene(){
    //check scene keywords
    return checkSceneKeyword($_SESSION['currentScene'], 11, keywordTypes::ECT) ? 0, -1;
}
?>