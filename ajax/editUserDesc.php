<?php
/**
 * Saves new user desc.
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/user.php");
try{
    User::shortcut_setDesc($_SESSION['playerID'], $_POST['desc']);
    Response::addLog("set desc");
    Response::send();
} catch (Exception $e){
    Response::addLog($e->getMessage());
    Response::send();
}
?>
