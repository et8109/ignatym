<?php
require_once ROOT.'/backend/tables/sceneTable.php';

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
	$newDesc = new Desc($desc);
	$newDesc = $newDesc->withKeywords(SceneTable::getKeywords($sid))->withPaths(SceneTable::getPaths($sid));
	SceneTable::setDesc($sid, $newDesc->getDesc());
    }

    public function getDesc($tags=True){
	$d = $this->desc;
	return $tags ? $d : strip_tags($d);
    }
 
    public function getName(){
        return $this->sname;
    }

    public function getId(){
        return $this->sid;
    }
}
