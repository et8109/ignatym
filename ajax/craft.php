<?php
/**
 * Creates and item and assigns it to the player
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/user.php");
require_once(ROOT."/backend/objects/item.php");
try{
    $user = User::fromId($_SESSION['playerID']);
    $item = Item::createItem($user, $_POST['name'], $_POST['desc']);
    Response::addLog("done! got ".$_POST['name']." and ".$_POST['desc']);
    Response::send();
} catch (Exception $e){
    Response::addLog($e->getMessage());
    Response::send();
}
?>
