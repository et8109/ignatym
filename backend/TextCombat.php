<?php
require_once 'shared/initialize.php';
require_once 'interfaces/generalInterface.php';

try{
    $function = $_POST['function'];
    switch($function){
        case('getDesc'):
            switch($_POST['type']){
                case(spanTypes::ITEM):
                    $item =  Req::select()->fromItemID($_POST['ID'])->desc()->name()->run();
                    echo getSpanText(spanTypes::ITEM,$_POST['ID'],$item->Name)."<>".$item->Desc;
                    break;
                case(spanTypes::KEYWORD):
                    $kw = Req::select()->fromKeywordID($_POST['ID'])->desc()->run();
                    echo getSpanText(spanTypes::KEYWORD,$_POST['ID'],$_POST['ID'])."<>".$kw->Desc;
                    break;
                case(spanTypes::PLAYER):
                    //if no id is set, make it the player
                    $ID = isset($_POST['ID']) ? $_POST['ID'] : $_SESSION['playerID'];
                    $player = Req::select()->fromPlayerID($ID)->name()->desc()->run();
                    echo getSpanText(spanTypes::PLAYER,$ID,$player->Name)."<>".$player->Description;
                    break;
                case(spanTypes::NPC):
                    $info = Req::select()->fromNpcID($_POST['ID'])->name()->desc()->run();
                    echo getSpanText(spanTypes::NPC,$_POST['ID'],$info['Name'])."<>".$info['Description'];
                    break;
                case(spanTypes::SCENE):
                    //if no id set, it's the current scene
                    $ID = is_numeric($_POST['ID']) ? $_POST['ID'] : $_SESSION['currentScene'];
                    $info = SharedInterface::getDescScene($ID));
                    echo getSpanText(spanTypes::SCENE,$ID,$info["Name"])."<>".$info["Description"];
                    //players
                    $playersResult = GeneralInterface::getPlayersInScene($_SESSION['currentScene']);
                    foreach($playersResult as $player){
                        echo "<>-".getSpanText(spanTypes::PLAYER,$player['playerID'],$player['playerName']);
                    }
                    //npcs
                    $npcsResult = GeneralInterface::getNpcsInScene($_SESSION['currentScene']);
                    foreach($npcsResult as $npc){
                        echo "<>-!".getSpanText(spanTypes::NPC,$npc['npcID'],$npc['npcName']);
                    }
                    break;
            }
            break;
        
        case('closeLook'):
            //town and land
            $sceneRow = GeneralInterface::getSceneJobs($_SESSION['currentScene']);
            if($sceneRow == false){
                sendError("Could not find this location");
            }
            echo "Town: ".$sceneRow['town'];
            echo "<>Land: ".$sceneRow['land'];
            $jobsBool = intval($sceneRow['appshp']) > 0 ? "Yes" : "No";
            echo "<>Jobs: ".$jobsBool;
            //manager
            if(intval($sceneRow['appshp']) > 0){
                $info = GeneralInterface::getSceneWorker(keywordTypes::MANAGER, $_SESSION['currentScene']);
                if($info == false){
                    echo "<>No manager. <span class='active action' onclick='beManager()'>Manage this location.</span>";
                } else{
                    echo "<>Manager: ".$info['Name'];
                }
            }
            //lord
            $lord = GeneralInterface::getSceneWorker(keywordTypes::MANAGER, $sceneRow['town']);
            if($lordRow == false){
                echo "<>Lord: None. The monarch should appoint one.";
            }
            else{
                echo "<>Lord: ".$lord['Name'];
            }
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
            $itemRow = GeneralInterface::getItemName($_POST['name']);
            if($itemRow == false){
                sendError("could not find item: ".$_POST['name']);
            }
            GeneralInterface::deleteItem($itemRow['ID']);
            Req::insert()->alert($_SESSION['playerID'])->removedItem()->run();
            break;
        
        case('giveItemTo'):
            //find id of reciever
            $playerRow = GeneralInterface::getPlayerID($_POST['playerName']);
            if($playerRow == false){
                sendError("Could not find ".$_POST['playerName']." nearby.");
            }
            //find id of item
            $itemRow = GeneralInterface::getPlayersItemInfo($_SESSION['playerID'], $_POST['itemName']);
            if($itemRow == false){
                sendError("Could not find ".$_POST['itemName']);
            }
            checkPlayerCanTakeItem($playerRow['ID']);
            removeItemIdFromPlayer($itemRow['ID']);
            addItemIdToPlayer($itemRow['ID'], $_POST['itemName']);
            break;
        
        case('putItemIn'):
            $itemName = prepVar($_POST['itemName']);
            $containerName = prepVar($_POST['containerName']);
            //get item and container info
            $itemRow = GeneralInterface::getPlayersItemInfo($_SESSION['playerID'], $itemName);
            $containerRow = GeneralInterface::getPlayersItemInfo($_SESSION['playerID'], $containerName);
            //make sure item was found
            if(!isset($itemRow['ID'])){
                sendError("the ".$itemName." was not found");
            }
            //make sure container was found
            if(!isset($containerRow['ID'])){
                sendError("the ".$containerName." was not found");
            }
            //make sure second item is a container
            if($containerRow['room'] == 0){
                sendError("either ".$containerName." is full, or it can not hold any items");
            }
            //make sure the first item is not in something else
            if($itemRow['insideOf'] != 0){
                sendError($itemName." is inside of something else. Remove it first.");
            }
            //make sure the item is not a bag
            $itemIsBagRow = GeneralInterface::checkItemHasKeywordType($itemRow['ID'], keywordTypes::CONTAINER);
            if($itemIsBagRow[0] > 0){
                sendError("You can't put a container into another container.");
            }
            //put in
            GeneralInterface::putItemInItem($itemRow['ID'], $containerRow['ID']);
            //add alert
            Req::insert()->alert($_SESSION['playerID'])->hiddenItem()->run();
            break;
        
        case('takeItemFrom'):
            $itemRow = GeneralInterface::getPlayersItemInfo($_SESSION['playerID'], $_POST['itemName']);
            $containerRow = GeneralInterface::getPlayersItemInfo($_SESSION['playerID'], $_POST['containerName']);
            if($itemRow == false){
                sendError("could not find ".$_POST['itemName']);
            }
            if($containerRow == false){
                sendError("could not find ".$_POST['containerName']);
            }
            //make sure item is in the container
            if($itemRow['insideOf'] != $containerRow['ID']){
                sendError("The ".$_POST['itemName']." is not in the ".$_POST['containerName']);
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
                echo getSpanText(spanTypes::ITEM,$item['itemID'],$item['Name'])."<>";
                echo $item['note']];
            }
            //materials
            $matIDsResult = GeneralInterface::sceneKeywordsOfType($_SESSION['currentScene'], keywordTypes::MATERIAL);
            //get material names and ids
            foreach($matIDsResult as $mat){
                //seperate into <>
                echo getSpanText(spanTypes::KEYWORD,$mat['ID'],$mat['Word'])."<>";
            }
            break;
        
        //used for /self
        case('getPlayerInfo'):
            //info
            $playerRow = SharedInterface::getPlayerInfo($_SESSION['playerID']);
            if($playerRow == false){
                sendError("Error finding your stats.");
            }
            echo "Name: ".$playerRow['Name'];
            echo "<>ID: ".$_SESSION['playerID'];
            echo "<>Craft skill: ".$playerRow['craftSkill'];
            echo "<>Health: ".$playerRow['health'];
            //keywords
            $keywordsResult = GeneralInterface::getPlayerKeywords($_SESSION['playerID']);
            if(count($keywordsResult) < 1){
                echo "<>No keywords";
            }
            else{
                echo "<>-Keywords:";
                foreach($keywordsResult as $kw){
                    echo "<>".$kw['word'];
                    //find location name, if applicable
                    if($kw['locationID'] != 0){
                        if(intval($kw['type'])==keywordTypes::LORD){
                            echo " of town ".$kw['locationID'];
                        }
                        else if(intval($kw['type'])==keywordTypes::MONARCH){
                            echo " of land ".$kw['locationID'];
                        }
                        else{
                            $locationRow = GeneralInterface::getSceneNamequery($kw['locationID']);
                            echo " of: ".$locationRow['name'];
                        }
                    }
                }
            }
            //items
            $itemsResult = SharedInterface::getTotalItems($_SESSION['playerID']));
            if(count($itemsResult) < 1){
                //no items
                echo "<>No items";
            }
            else{
                echo "<>-Items:<>";
                foreach($itemsResult as $item){
                    echo $item['name'].",";
                    //rtrim($string, ",")
                }
            }
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
                echo "</br>".$alert['Description'];
            }
            break;
        
        case('clearAlerts'):
            /*$permAlerts = array(
                alertTypes::hiddenItem,
                alertTypes::newItem,
                alertTypes::removedItem,
                alertTypes::newJob,
                alertTypes::fired,
                alertTypes::newSpell
            );*/
            GeneralInterface::clearAlerts($_SESSION['playerID']);
            break;
        
        case('getTime'):
            echo getTime();
            echo ", ".getTimeOfDayWord();
            break;
        
        case('login'):
            //make sure they are not logged in
            if(isset($_SESSION['playerID'])){
                sendError("You already logged in. Try refreshing the page.");
            }
            //sanitize
            $uname = $_POST['uname'];
            $pass = $_POST['pass'];
            if($uname == null || $uname == ""){
                sendError("Enter a valid username");
            }
            if($pass == null || $pass == ""){
                sendError("Enter a valid password");
            }
            //get username, password
            $playerRow = GeneralInterface::getLogin($uname, $pass);
            if($playerRow == false){
                sendError("Incorrect username or password");
            }
            if($playerRow['loggedIn'] == false){
                GeneralInterface::changePlayerScene($playerRow['ID'], $playerRow['Scene'], $playerRow['Name']);
    
            }
            //find next login id
            $lastLogin = intval($playerRow['loggedIn']);
            $nextLogin = $lastLogin < 9 ? $lastLogin+1 : 1;
            GeneralInterface::setLoggedIn($playerRow['ID'], $nextLogin);
            //select needed info from playerinfo
            $_SESSION['playerID'] = $playerRow['ID'];
            $_SESSION['playerName'] = $playerRow['Name'];
            $_SESSION['currentScene'] = $playerRow['Scene'];
            $_SESSION['loginID'] = $nextLogin;
            updateChatTime();
            break;
        
        case('register'):
            //make sure they are not logged in
            if(isset($_SESSION['playerID'])){
                sendError("You already logged in. Try refreshing the page.");
            }
            //check amount of players
            $numPlayers = query("select count(1) from playerinfo");
            if($numPlayers[0] > 2){
                sendError("Sorry, max amount of players reached. Check the updates for when we can let more in.");
            }
            //sanitize
            $uname = $_POST['uname'];
            $pass = $_POST['pass'];
            $pass2 = $_POST['pass2'];
            //check password similarity
            if($pass != $pass2){
                sendError("Your passwords don't match");
            }
            //check players for name
            $sharedNameRow = getPlayerID($uname);
            if($sharedNameRow != false){
                sendError("Someone already has that name");
            }
            //add player
            $playerID = GeneralInterface::addNewUser($uname, $pass, constants::initDesc, constants::startSceneID);
            break;
        
        case('logout'):
            GeneralInterface::logoutPlayer($_SESSION['playerID']);
            session_destroy();
            sendError("logged out. <a href='login.php'>Back to login</a>");
            break;
    }
}
catch(Exception $e){
    sendError("chat error: ".$e->getMessage());
}
?>