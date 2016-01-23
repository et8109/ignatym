<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
    header("Location: login.php");
}

include("shared/phpHelpers.php");

try{
    require_once(ROOT."/backend/objects/user.php");
    User::logout($_SESSION['playerID']);
    session_destroy();
    header("Location: login.php");
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
die();
?>
