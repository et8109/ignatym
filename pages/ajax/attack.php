<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
require_once("../../constants.php");
require_once(ROOT."/backend/objects/user.php");
$user = User::fromId($_SESSION['playerID']);
$enemy = Npc::fromId($_GET["nid"]);
$user->attack($enemy);
?>
