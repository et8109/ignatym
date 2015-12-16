<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
require_once("../../constants.php");
require_once(ROOT."/backend/tables/npcTable.php");
$nid = $_GET["id"];
echo NpcTable::getDesc($nid)["Description"];
echo "</br><span class='action' onclick='attack($nid)'>attack</span>";
?>
