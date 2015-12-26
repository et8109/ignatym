<?php
/**
 * Returns the desc of the given npc
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/tables/npcTable.php");
$nid = $_GET["id"];
echo NpcTable::getDesc($nid)["Description"];
echo "</br><span class='action' onclick='attack($nid)'>attack</span>";
echo "</br><span class='action' onclick='resurrect($nid)'>resurrect</span>";
?>
