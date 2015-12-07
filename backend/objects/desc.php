<?php
require_once '../backend/tables/keywordTable.php';

class Desc {
    private $desc;
    private $keywords;
    private $items;
    private $uid;

    private function __construct($desc, $kwIds){
	$this->desc = $desc;
	$this->keywords = [];
	foreach($kwIds as $kw){
		$this->keywords[] = isset($kw['ID']) ? $kw['ID'] : $kw;
	}
    }

    public static function create($desc, $kwIds){
	$d = new self($desc, $kwIds);
	$d->replaceAllKeywords();
	return $d;
    }

    public function withItems($uid, $itemIds){
	require_once("item.php");
	$this->uid = $uid;
	$this->items = [];
	foreach($itemIds as $id){
                $this->items[] = isset($id['ID']) ? $id['ID'] : $id;
        }
	$this->replaceAllItems();
	return $this;
    }

    private function replaceAllKeywords(){
	foreach($this->keywords as $kw){
	    $this->replaceID($kw, "kw");
	}
    }
    
    private function replaceAllItems(){
	foreach($this->items as $item){
            $this->replaceID($item, "item");
        }
    }

    private function replaceID($kwId, $type){
	$descArray = explode(" ",$this->desc);
        $descArrayLength = count($descArray);
        for($i=0; $i<$descArrayLength; $i++){
		$word = $descArray[$i];
   		$wordId;
		if($type == "kw"){
		    $wordId = KeywordTable::getKeywordID(strtolower($word));
		} else if($type == "item"){
		    $wordId = ItemTable::getIdByName($this->uid, strtolower($word));
		}
		if(isset($wordId['ID'])){
                /*//if a material, make sure it is available
                if($keywordType == keywordTypes::MATERIAL){
                    $numMatRow = SharedInterface::checkSceneKeyword($_SESSION['currentScene'], $keywordRow['ID'], keywordTypes::MATERIAL);
                    if($numMatRow[0] < 1){
                        throw new Exception("You don't have enough material for: ".$word);
                    }
                }*/
                //find correct span to replace with
                /*$spanType = spanTypes::KEYWORD;
                if($keywordType == keywordTypes::SCENE_ACTION){
                    $spanType = spanTypes::ACTION;
                }*/
               	    $descArray[$i] = Desc::getKeywordWrapper($word, $wordId['ID']);
		    $this->desc = implode(" ",$descArray);
		    return;
		}
	}
	throw new Exception("kw id not found");
    }


	//find prerequisites
    	/*$minID = 0;
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
            	break;*/

    private static function getKeywordWrapper($word, $id){
	return "<span class='keyword' onclick='addDesc($id)'>$word</span>";	
    }

    public function getDesc(){
	return $this->desc;
    }

    public function getKeywordIds(){
	return $this->keywords;
    }
}
?>
