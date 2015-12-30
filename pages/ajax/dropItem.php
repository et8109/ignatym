<?php
/**
 * Removes an item from the player.
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/item.php");
try{
    Item::shortcut_delete($_GET['iid']);
    Response::addLog("Dropped item.");
    Response::send();
} catch (Exception $e){
    Response::addLog($e->getMessage());
    Response::send();
}
?>
