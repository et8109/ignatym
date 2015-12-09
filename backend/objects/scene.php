<?php
require_once '../backend/tables/sceneTable.php';

class Scene {
    private $sid;
    private $sname;
    private $desc;

    private function __construct($row) {
	$this->sid = $row['ID'];
	$this->sname = $row['Name'];
	$this->desc = $row['Description'];
    }

    public static function fromId($sid){
        return new self(SceneTable::getInfo($sid));
    }

    public static function shortcut_setDesc($sid, $desc){
	require_once 'desc.php';
	$newDesc = Desc::create($desc, SceneTable::getKeywordIds($sid))->withPaths(SceneTable::getPaths($sid));
	SceneTable::setDesc($this->sid, $newDesc->getDesc());
    }

    public function getDesc($tags=True){
	$d = $this->desc;
	return $tags ? $d : strip_tags($d);
    }
 
    public function getName(){
        return $this->sname;
    }

}
