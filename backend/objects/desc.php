<?php
require_once '../backend/tables/keywordTable.php';

class Desc {
    private $descArray;
    private $numWords;

    public function __construct($desc){
	$this->descArray = explode(" ",$desc);
	$this->numWords = count($this->descArray);
    }
    
    public function withKeywords($keywords){
	$IdToWords = [];
        foreach($keywords as $kw){
	    $IdToWords[$kw['ID']][] = $kw['Word'];
        }
	foreach($IdToWords as $id => $words){
	    $this->replaceWord($words, $id, "kw");
	}
	return $this;
    }

    public function withItems($items){
	foreach($items as $item){
            $this->replaceWord([$item['Name'],], $item['ID'], "item");
        }
	return $this;
    }

    public function withPaths($paths){
        foreach($paths as $path){
	    $this->replaceWord([$path['Name'],], $path['ID'], "path");
        }
        return $this;
    }

    private function replaceWord($words, $id, $type){
        for($i=0; $i < $this->numWords; $i++){
	    foreach($words as $word){
		if($this->descArray[$i] === $word){
		    if($type == "kw"){
		        $this->descArray[$i] = Desc::getKeywordWrapper($word, $id);
		    } else if($type == "item"){
		        $this->descArray[$i] = Desc::getItemWrapper($word, $id);
		    } else if($type == "path"){
		        $this->descArray[$i] = Desc::getPathWrapper($word, $id);
		    }
                return;
	        }
	    }
        }
	throw new Exception("id not found: $id, $type.");
    }

    private static function getKeywordWrapper($word, $id){
	return "<span class='keyword' onclick='getKwDesc($id)'>$word</span>";	
    }

    private static function getItemWrapper($word, $id){
        return "<span class='item' onclick='getItemDesc($id)'>$word</span>"; 
    }

    private static function getPathWrapper($word, $id){
	return "<a href='walk.php?sid=$id'>$word</a>";
    }

    public function getDesc(){
	return implode(" ",$this->descArray);
    }
}
?>
