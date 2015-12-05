<?php
require_once '../backend/models/userModel.php';

class UserLogic {
    private function __construct() {}//static only

    public static function loginUser($uname, $pword){
	//sanitize
        if($uname == null || $uname == ""){
            throw new Exception("Enter a valid username");
        }
        if($pword == null || $pword == ""){
            throw new Exception("Enter a valid password");
        }
	$info = UserModel::loginUser($uname, $pword);
        if($info == false){
            throw new Exception("Incorrect username or password");
        }
        if($info['loggedIn'] == false){
            UserModel::changeUserScene($info['ID'], $info['Scene'], $info['Name']);

        }
        //find next login id
        $lastLogin = intval($info['loggedIn']);
        $nextLogin = $lastLogin < 9 ? $lastLogin+1 : 1;
        UserModel::setLoggedIn($info['ID'], $nextLogin);
        //select needed info from playerinfo
        $_SESSION['playerID'] = $info['ID'];
        $_SESSION['playerName'] = $info['Name'];
        $_SESSION['currentScene'] = $info['Scene'];
        $_SESSION['loginID'] = $nextLogin;
        //updateChatTime();
        $_SESSION['lastChatTime'] = date_timestamp_get(new DateTime());
    }

    public static function logoutUser($uid){
        UserModel::logoutUser($uid);
    }

    public static function setUserScene($uid, $from, $to, $name){
        UserModel::changeUserScene($uid, $to, $name);
        //$info = GeneralInterface::getSceneName($_SESSION['currentScene']);
        //speakActionWalk($_SESSION['currentScene'],$info['Name']);
        //updateChatTime();
    }

    public static function registerUser($uname, $pword){
	UserModel::registerUser($uname, $pword);
    }
}
    /*case('updateDescription'):
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
}*/
?>
