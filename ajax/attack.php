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
try{
    Response::addLog($user->attack($enemy));
    Response::updateNpc($enemy);
    Response::updateUser($user);
    Response::send();
} catch (Exception $e){
    Response::addLog($e->getMessage());
    Response::send();
}
?>
