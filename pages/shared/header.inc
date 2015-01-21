<?php
        function handle($e){  
            //prevents the rest of the page from loading
            echo "general exception:";
            echo $e->getMessage();
            
                //db exception
                /*if($e instanceof dbException){
                    if($e->getCode() == dbException::CODE_COULD_NOT_CONNECT){
                        echo $e->getMessage();
                    }
                //normal exception
                } else{
                    echo "general exception:";
                    echo $e->getMessage();
                }*/
            }
        set_exception_handler("handle");
    ?>
<html>
    <head>
        <!-- shared favicon code -->
        <meta name="description" content="Explore a unique world, improve your character, and impact the game in your own way.">
        <meta name="keywords" content="game,online,free,multiplayer,text">
        <meta name="author" content="EE">
        <title>Ignatym</title>
        <link rel="icon" href="../images/favicon.ico" type="image/x-icon"/>
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="base.css" />
    </head>
    <body>
        <!-- error message -->
        <img id="errorPoint" src="../images/errorPoint.png" style="visibility: hidden"><span id="error"></span></br>
        <!-- exception handling -->