<?php
/**
 *the gate for all http requests
 */
require_once 'shared/constants.php';
require_once 'shared/functions.php';
require_once 'shared/initialize.php';

try{
    $module = $_POST['mod'];
    //$function = $_POST['function'];
    switch($module){
        case('chat'):
            require_once 'modules/chat.php';
            break;
        case('combat'):
            require_once 'modules/combat.php';
            break;
        case('crafting'):
            require_once 'modules/crafting.php';
            break;
        case('magic'):
            require_once 'modules/magic.php';
            break;
        case('setup'):
            require_once 'modules/setup.php';
            break;
        case('general'):
            require_once 'modules/general.php';
            break;
    }
}
catch(Exception $e){
    sendError($e->getMessage());
}
finally{
    echo sendResponse();
}
?>