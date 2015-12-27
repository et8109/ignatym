<?php
require_once ROOT.'/backend/tables/npcTable.php';

class Npc {
    private $nid;
    private $health;
    private $nname;

    const MAX_HEALTH = 5;

    private function __construct($row){
	$this->nid = $row['ID'];
        $this->health = $row['health'];
        $this->nname = $row['Name'];
    }

    public static function fromIds($nid, $sid){
        return new self(NpcTable::getInfo($nid, $sid));
    }

    public function getHit($dmg){
        $this->health = $this->health - $dmg;
        NpcTable::setHealth($this->nid, $this->health);
    }

    public function respawn(){
        $this->health = Npc::MAX_HEALTH;
        NpcTable::setHealth($this->nid, $this->health);
	return "</br>restored to full life";
    }

    public static function npcsInScene($sid){
        $npcs = [];
        $rows = NpcTable::getInScene($sid);
        foreach($rows as $info){
            $npcs[] = new Npc($info);
        }
        return $npcs;
    }

    public function getHtml(){
        $classes = $this->getCssClasses();
        return "<span id=$this->nid class='$classes' onclick='getNpcDesc($this->nid, this)'>$this->nname</span>";
    }


    public function getCssClasses(){
      $status = "full";
      if($this->health == 0){
        $status = "dead";
      } else if($this->health < Npc::MAX_HEALTH){
        $status = "hurt";
      }
      return "npc $status";
    }

    public function getId(){
	return $this->nid;
    }
    public function getHealth(){
        return $this->health;
    }
}
?>
