<!-----------------
--before anything--
------------------>
<?php
ob_start();
session_start();
if(isset($_SESSION['playerID'])){
    header("Location: login.php");
}

include("shared/phpHelpers.php");
include("shared/header.inc");?>
<title>Register</title>
</head>

<?php
try{
    if(isset($_POST['uname'])){
        require_once(ROOT."/backend/objects/user.php");

	$uname = $_POST['uname'];
        $pword1 = $_POST['pass1'];
	$pword2 = $_POST['pass2'];
	
        if($uname == "" || $pword1 == "" || $pword2 == ""){
            throw new Exception("please enter a valid username and password");
        }
	if($pword1 != $pword2){
	    throw new Exception("your passwords don't match");
	}

	User::register($uname, $pword1);
        ?>
	Sucess!
	<a href="login.php">Back to login</a>
<?php
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
</body>
</html>
