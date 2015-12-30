<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
require_once("../../constants.php");
require_once(ROOT."/backend/objects/item.php");
$item = Item::fromId($_GET["id"]);
echo $item->getDesc();
if($item->getOwnerId() == $_SESSION['playerID']){
    $iid = $item->getId();
    echo"</br><span class='action' onclick='dropItem($iid)'>drop</span>";
}
?>
