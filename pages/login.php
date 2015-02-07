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
<title>Ignatym</title>
<link rel="stylesheet" type="text/css" href="login.css" />

<!---------------------------------->
<?php include("shared/header2.inc");?>
<!--inside <body>
------------------------------------>
<?php
try{
    if(isset($_POST['uname'])){
        //sanitize
        $uname = $_POST['uname'];
        $pass = $_POST['pass'];
        if($uname == null || $uname == ""){
            throw new Exception("Enter a valid username");
        }
        if($pass == null || $pass == ""){
            throw new Exception("Enter a valid password");
        }
        //get username, password
        require_once("../backend/interfaces/loginInterface.php");
        $playerRow = LoginInterface::getLogin($uname, $pass);
        if($playerRow == false){
            throw new Exception("Incorrect username or password");
        }
        if($playerRow['loggedIn'] == false){
            require_once("../backend/interfaces/generalInterface.php");
            GeneralInterface::changePlayerScene($playerRow['ID'], $playerRow['Scene'], $playerRow['Name']);

        }
        //find next login id
        $lastLogin = intval($playerRow['loggedIn']);
        $nextLogin = $lastLogin < 9 ? $lastLogin+1 : 1;
        LoginInterface::setLoggedIn($playerRow['ID'], $nextLogin);
        //select needed info from playerinfo
        $_SESSION['playerID'] = $playerRow['ID'];
        $_SESSION['playerName'] = $playerRow['Name'];
        $_SESSION['currentScene'] = $playerRow['Scene'];
        $_SESSION['loginID'] = $nextLogin;
        //updateChatTime();
        $_SESSION['lastChatTime'] = date_timestamp_get(new DateTime());
        header("Location: index.php");
    }
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
?>

<!-- login form -->
<form action="login.php" method="post">
    I am <input type="text" name="uname" maxlength=20><br>
    Password: <input type="password" name="pass" maxlength=20><br>
    <input type="submit">
</form>

<!-- additional links -->
<a href="register.php">Need to register?</a></br>
Guest account available,</br>username and password are "guest".</br></br>
<div id="info">
    <a href="guide.php" target="_newtab">Guide</a></br></br>
    <a href="http://ignatym.freeforums.net/" target="_newtab">Forums</a></br></br>
    Welcome to the alpha!</br></br></br>
</div>

<?php
include("shared/footer.inc");
?>