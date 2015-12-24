<?php
require_once ROOT.'/backend/tables/userTable.php';

class User {
    private $uid;
    private $uname;
    private $pword;
    private $health;
    private $isLoggedIn;
    private $desc;
    private $items;
    private $scene;

    const MAX_DESC_LEN = 200;
    const MAX_HEALTH = 5;

    private function __construct($row){
	$this->uid = $row['ID'];
	$this->uname = $row['Name'];
	$this->isLoggedIn = $row['loggedIn'];
	$this->desc = $row['Description'];
        $this->health = $row['Health'];
    }

    public static function fromId($uid){
        return new self(UserTable::getInfo($uid));
    }

    public static function login($uname, $pword){
	//sanitize
        if($uname == null || $uname == ""){
            throw new Exception("Enter a valid username");
        }
        if($pword == null || $pword == ""){
            throw new Exception("Enter a valid password");
        }
	$info = UserTable::login($uname, $pword);
        if($info == false){
            throw new Exception("Incorrect username or password");
        }
	$user = new self($info);
        if(!$user->isLoggedIn()){
            UserTable::changeScene($user, $user->scene);

        }
        //find next login id
        $lastLogin = intval($info['loggedIn']);
        $nextLogin = $lastLogin < 9 ? $lastLogin+1 : 1;
        UserTable::setLoggedIn($user->getId(), $nextLogin);
        //select needed info from playerinfo
        $_SESSION['playerID'] = $info['ID'];
        $_SESSION['playerName'] = $info['Name'];
        $_SESSION['currentScene'] = $info['Scene'];
        $_SESSION['loginID'] = $nextLogin;
        //updateChatTime();
        $_SESSION['lastChatTime'] = date_timestamp_get(new DateTime());
    }

    public static function logout($uid){
        UserTable::logoutUser($uid);
    }

    public static function shortcut_setDesc($uid, $desc){
	require_once("desc.php");
	$kws = UserTable::getKeywords($uid);
	$kws = !$kws ? [] : $kws;
	$items = UserTable::getItems($uid);
        $items = !$items ? [] : $items;
	$userDesc = new Desc($desc);
	$userDesc->withKeywords($kws)->withItems($items);
	UserTable::setDesc($uid, $userDesc->getDesc());
    }

    public static function shortcut_getDesc($uid, $tags=True){
	$d = UserTable::getDesc($uid)["Description"];
	return $tags ? $d : strip_tags($d);
    }

    public static function shortcut_walk($uid, $from, $to, $name){
        UserTable::changeScene($uid, $to, $name);
        //$info = GeneralInterface::getSceneName($_SESSION['currentScene']);
        //speakActionWalk($_SESSION['currentScene'],$info['Name']);
        //updateChatTime();
    }
   
    public static function shortcut_regen($uid){
        UserTable::setHealth($uid, User::MAX_HEALTH);
    }


    public static function register($uname, $pword){
	$id = UserTable::register($uname, $pword);
	$user = User::fromId($id);
        require_once("item.php");
        $item = Item::createItem($user, "rags", "simple rags");
	User::shortcut_setDesc($id, "Me and some rags .");
    }

    public function hasRoomForItem(){
	return strlen($this->desc) + Item::MAX_NAME_LEN < self::MAX_DESC_LEN;
    }

    public function attack($npc){
        if($this->health <= 0){
	    throw new Exception("</br>You are dead");
	    return;
        }
        /*if($npc->getHealth() <= 0){
            throw new Exception("</br>Enemy is dead");
            return;
        }*/
        $npc->getHit(1);
	$this->health = $this->health-1;
        UserTable::setHealth($this->uid, $this->health);
	return "</br>Hit for 1 dmg";
    }

    public function appendToDesc($str){
	UserTable::appendToDesc($this->uid, $str);
    }

    public function isLoggedIn(){
	return $this->isLoggedIn;
    }
    public function getId(){
	return $this->uid;
    }
    public function getUname(){
        return $this->uname;
    }
    public function getDesc(){
        return $this->desc;
    }
    public function getHealth(){
        return $this->health;
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
