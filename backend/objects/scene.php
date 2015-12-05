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
	SceneTable::setDesc($sid, $desc);
    }

    public function getDesc(){
	return $this->desc;
    }
 
    public function getName(){
        return $this->sname;
    }

}
