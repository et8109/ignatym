<?php
require_once '../backend/tables/keywordTable.php';

class Desc {
    private $desc;
    private $keywords;
    private $items;
    private $uid;
    private $paths;

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

    private function replaceAllKeywords(){
	foreach($this->keywords as $kw){
            $this->replaceID($kw, "kw");
        }
    }

    public function withItems($uid, $itemIds){
	require_once("../backend/tables/itemTable.php");
	$this->uid = $uid;
	$this->items = [];
	foreach($itemIds as $id){
                $this->items[] = isset($id['ID']) ? $id['ID'] : $id;
        }
	foreach($this->items as $item){
            $this->replaceID($item, "item");
        }
	return $this;
    }

    public function withPaths($paths){
        require_once("../backend/tables/sceneTable.php");
        $this->paths = [];
        foreach($this->paths as $path){
	    $this->replaceWord([$path['Name'],], $path['ID'], "path");
            //$this->replaceID($pid, "path");
        }
        return $this;
    }


    private function replaceWord($words, $id, $type){
	$descArray = explode(" ",$this->desc);
        $descArrayLength = count($descArray);
        for($i=0; $i<$descArrayLength; $i++){
	    for($words as $word){
		if($descArray[$i] == $word){
		    if($type == "kw"){
		        $descArray[$i] = getKeywordWrapper($word, $id);
		    } else if($type == "item"){
		        $descArray[$i] = getItemWrapper($word, $id);
		    } else if($type == "path"){
		        $descArray[$i] = getPathWrapper($word, $id);
		    }
		$this->desc = implode(" ",$descArray);
                return;
	        }
	    }
	throw new Exception("id not found: $id, $type.");
    }

    private function replaceID($kwId, $type){
	$descArray = explode(" ",$this->desc);
        $descArrayLength = count($descArray);
        for($i=0; $i<$descArrayLength; $i++){
		$word = $descArray[$i];
   		$wordId;
		if($type == "kw"){
		    $wordId = KeywordTable::getKeywordID(strtolower($word));
		    if(isset($wordId['ID'])){
                        $descArray[$i] = Desc::getKeywordWrapper($word, $wordId['ID']);
                        $this->desc = implode(" ",$descArray);
                        return;
                    }

		} else if($type == "item"){
		    $wordId = ItemTable::getIdByName($this->uid, strtolower($word));
		    if(isset($wordId['ID'])){
                        $descArray[$i] = Desc::getItemWrapper($word, $wordId['ID']);
                        $this->desc = implode(" ",$descArray);
                        return;
                    }

		} else if($type == "path"){
                    $wordId = SceneTable::getId($word);
		    var_dump($wordId);
		    if(isset($wordId['ID'])){
			$descArray[$i] = Desc::getPathWrapper($word, $wordId['ID']);
                        $this->desc = implode(" ",$descArray);
                        return;
		    }
                }
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
	}
	throw new Exception("kw id not found: $type $kwId");
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
	return "<span class='keyword' onclick='getKwDesc($id)'>$word</span>";	
    }

    private static function getItemWrapper($word, $id){
        return "<span class='item' onclick='getItemDesc($id)'>$word</span>"; 
    }

    private static function getPathWrapper($word, $id){
        return "<span class='path' onclick='walk($id)'>$word</span>"; 
    }


    public function getDesc(){
	return $this->desc;
    }

    public function getKeywordIds(){
	return $this->keywords;
    }
}
?>
