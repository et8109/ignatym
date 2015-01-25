<?php
include_once 'constants.php';
error_reporting(0);
session_start();
//check inputs
_checkInputIsClean();
//check if logged in
_checkIfLoggedIn();

/**
 *makes sure an input is clean
 *throws error if not
 *assumes inputs are all get
 */
function _checkInputIsClean(){
    /**
    *the characters or strings not allowed in inputs
    */
    $restrictedInputs = array(
       "<",
       ">",
       "<?php",
       "\r",
       "\n"
    );
    $numRestricted = sizeof($restrictedInputs);
    foreach ($_POST as $key => $value) {
        if($value == null || $value==""){
            sendError("restricted char/string in input");
        }
        for($i=0; $i<$numRestricted; $i++){
            //php said to use ===
            if(strpos($value,$restrictedInputs[$i]) === true){
                sendError("restricted char/string in input");
            }
        }
    }
}

/**
 *If the player is not logged in, terminate session and tell them to log in again
 */
function _checkIfLoggedIn(){
    $function = $_POST['function'];
    if($function != 'register' && $function != 'login'){
        //check session
        if(!isset($_SESSION['playerID'])){
            session_destroy();
            sendError("Your session was lost. Please log in again.");
        }
        $loginRow = query("select loggedIn from playerinfo where ID=".prepVar($_SESSION['playerID']));
        //check login id
        if($loginRow['loggedIn'] != $_SESSION['loginID']){
            session_destroy();
            sendError("You were recently logged out. Please log in again.");
        }
    }
}
?>