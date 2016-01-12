<?php
/**
 * Heals the user to full health;
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/user.php");

try{
    User::shortcut_regen($_SESSION['playerID']);
    Response::addLog("Healed to full");
    Response::send();
} catch (Exception $e){
    Response::addLog($e->getMessage());
    Response::send();
}
?>
