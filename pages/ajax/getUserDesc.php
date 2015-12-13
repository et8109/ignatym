<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
require_once("../../constants.php");
require_once(ROOT."/backend/tables/userTable.php");
echo UserTable::getDesc($_GET["id"])["Description"];
?>
