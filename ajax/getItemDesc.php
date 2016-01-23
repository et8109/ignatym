<?php
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/item.php");
try{
    $item = Item::fromId($_GET["id"]);
} catch(ItemNotFoundException $e){
    echo "Item not found";
    die();
}

echo $item->getDesc();
if($item->getOwnerId() == $_SESSION['playerID']){
    $iid = $item->getId();
    echo"</br><span class='action' onclick='dropItem($iid)'>drop</span>";
}
?>
