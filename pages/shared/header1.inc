<?php
    function handle($e){
        echo "error: ".$e->getMessage();
    }
    set_exception_handler("handle");
    require_once("../constants.php")
    ?>
<html>
    <head>
        <!-- shared favicon code -->
        <meta name="Description" content="Explore a unique world, improve your character, and impact the game in your own way.">
        <meta name="keywords" content="game,online,free,multiplayer,text">
        <meta name="author" content="EE">
        <link rel="icon" href="../images/favicon.ico" type="image/x-icon"/>
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="css/base.css" />
