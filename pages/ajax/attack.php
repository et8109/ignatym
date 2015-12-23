<?php
/**
 * Loads all user info.
 * Loads all npc info.
 * Calcualates attack result.
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/user.php");
require_once(ROOT."/backend/objects/npc.php");
$user = User::fromId($_SESSION['playerID']);
$enemy = Npc::fromIds($_GET["nid"], $_SESSION['currentScene']);
$user->attack($enemy);
?>
