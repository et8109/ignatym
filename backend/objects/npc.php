<?php
require_once ROOT.'/backend/tables/npcTable.php';

class Npc {
    private $nid;
    private $health;

    const MAX_HEALTH = 5;

    private function __construct($row){
	$this->nid = $row['ID'];
        $this->health = $row['health'];
    }

    public static function fromIds($nid, $sid){
        return new self(NpcTable::getInfo($nid, $sid));
    }

    public function getHit($dmg){
        $this->health = $this->health - $dmg;
        NpcTable::setHealth($this->nid, $this->health);
    }

    public function getId(){
	return $this->nid;
    }
    public function getHealth(){
        return $this->health;
    }
}
?>