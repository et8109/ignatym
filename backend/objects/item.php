<?php
require_once '../backend/tables/itemTable.php';

class Item {
    private $iid;
    private $iname;
    private $desc; 

    const MAX_NAME_LEN = 10;

    private function __construct($row){
	$this->iid = $row['ID'];
	$this->iname = $row['Name'];
	$this->desc = $row['Description'];
    }

    public function fromId($iid){
        return new self(ItemTable::getInfo($iid));
    }

    public static function createItem($user, $iname, $inputDesc){
        require_once 'desc.php';
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
	$reqKwIds = [3,];
        $itemDesc = Desc::create($inputDesc, $reqKwIds);
        //add the item into db
        $iid = ItemTable::createItem($user->getId(), $iname, $itemDesc->getDesc(), false);
	foreach($itemDesc->getKeywordIds() as $kwid){
	    KeywordTable::createItemKeywords($iid, $kwid);
	}
	//return item object
	return new self($iid, $iname, $itemDesc->getDesc());
    }

}
?>
