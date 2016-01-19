<?php
/**
 * Returns an npc to full life
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/user.php");
require_once(ROOT."/backend/objects/npc.php");
$user = User::fromId($_SESSION['playerID']);
$enemy = Npc::fromIds($_GET["nid"], $_SESSION['currentScene']);
try{
    Response::addLog($enemy->respawn());
    Response::updateNpc($enemy);
    Response::send();
} catch (Exception $e){
    Response::addLog($e->getMessage());
    Response::send();
}
?>
