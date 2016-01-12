<?php
require_once ROOT.'/backend/tables/itemTable.php';

class Item {
    private $iid;
    private $iname;
    private $desc;
    private $ownerID;

    const MAX_NAME_LEN = 10;

    private function __construct($row){
	$this->iid = $row['ID'];
	$this->iname = $row['Name'];
	$this->desc = $row['Description'];
        $this->ownerID = $row['playerID'];
    }

    public static function fromId($iid){
	$info = ItemTable::getInfo($iid);
	if($info == NULL){
	  throw new ItemNotFoundException();
        }
        return new self($info);
    }

    public static function createItem($user, $iname, $inputDesc){
        require_once 'desc.php';
        if(trim($iname) == "" ){
	    throw new Exception("item name invalid");
        }
        /*//make sure the player is a blacksmith
        $level = getPlayerManageLevel();
        if($level != keywordTypes::APPSHP && $level != keywordTypes::MANAGER){
            throw new Exception("You don't have permission to craft here.");
        }*/
        /*//make sure the player can take an item
        checkPlayerCanTakeItem();
        $keywordIDs = array();
        $IdOut = -1;
        */
        //make sure all required keyword types were replaced
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

	//check user has room in desc
	if(!$user->hasRoomForItem()){
	    throw new Exception("No room for item");
	}
	$reqKwIds = KeywordTable::getKeywordWordsFromIds("(3)");
        $itemDesc = new Desc($inputDesc);
	$itemDesc = $itemDesc->withKeywords($reqKwIds);
        //add the item into db
        $iid = ItemTable::createItem($user->getId(), $iname, $itemDesc->getDesc(), false);
	foreach($itemDesc->getKeywordIds() as $kwid){
	    KeywordTable::createItemKeywords($iid, $kwid);
	}
        //TODO check player desc has room first
        $user->appendToDesc(" ".Desc::getItemWrapper($iname, $iid));
	//return item object
	return new self($iid, $iname, $itemDesc->getDesc());
    }

    public static function shortcut_delete($iid){
      ItemTable::remove($iid);
    }

    public function getOwnerId(){
      return $this->ownerID;
    }

    public function getId(){
      return $this->iid;
    }

    public function getDesc(){
      return $this->desc;
    }
}

class ItemNotFoundException extends Exception{};
?>
