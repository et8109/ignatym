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
try{
    //logging in
    if(isset($_POST['uname'])){
        $uname = $_POST['uname'];
        $pass1 = $_POST['pass1'];
	$pass2 = $_POST['pass2'];
        if($uname == "" || $pass1 == "" || $pass2 == ""){
            throw new Exception("please enter a valid username and password");
        }
	if($pass1 != $pass2){
	    throw new Exception("your passwords don't match");
	}
        include("../backend/interfaces/registerInterface.php");
        RegisterInterface::registerPlayer($uname, $pass1);
        
        echo "Success! <a href='login.php'>Back to login</a></br>";
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