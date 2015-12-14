<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
require_once("../../constants.php");
require_once(ROOT."/backend/tables/itemTable.php");
echo ItemTable::getItemDesc($_GET["id"])["Description"];
?>
