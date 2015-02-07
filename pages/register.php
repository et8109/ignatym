<!-----------------
--before anything--
------------------>
<?php
ob_start();
session_start();
if(isset($_SESSION['playerID'])){
    header("Location: index.php");
}
?>

<!---------------------------------->
<?php include("shared/header1.inc");?>
<!--inside <head>
------------------------------------>
<title>Register</title>

<!---------------------------------->
<?php include("shared/header2.inc");?>
<!--inside <body>
------------------------------------>
<?php
require_once("../backend/interfaces/registerInterface.php");
require_once("../backend/interfaces/sharedInterface.php");
$backButton = "<a href='login.php'>Back to login</a>";
try{
    if(isset($_POST['uname'])){
	//check amount of players
        $numPlayers = RegisterInterface::getNumPlayers();
        if($numPlayers > 2){
            throw new Exception("Sorry, max amount of players reached. Check the updates for when we can let more in. $backButton");
        }
	
	$uname = $_POST['uname'];
        $pass1 = $_POST['pass1'];
	$pass2 = $_POST['pass2'];
	
        if($uname == "" || $pass1 == "" || $pass2 == ""){
            throw new Exception("please enter a valid username and password");
        }
	if($pass1 != $pass2){
	    throw new Exception("your passwords don't match");
	}
	
	//check players for name
        $sharedNameRow = SharedInterface::getPlayerID($_POST['uname']);
        if($sharedNameRow != false){
            throw new Exception("Someone already has that name");
        }
	
        RegisterInterface::registerPlayer($uname, $pass1);
        
        echo "Success!$backButton</br>";
    }
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
?>

<!-- login form -->
<form action="register.php" method="post">
    I am <input type="text" name="uname" maxlength=20><br>
    Password: <input type="password" name="pass1" maxlength=20><br>
    Password: <input type="password" name="pass2" maxlength=20><br>
    <input type="submit">
</form>

<?php
include("shared/footer.inc");
?>