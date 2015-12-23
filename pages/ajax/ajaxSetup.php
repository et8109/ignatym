<?php
/**
 Checks that the player is logged in.
 imports the constants page.
 */
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
require_once("../../constants.php");
?>
