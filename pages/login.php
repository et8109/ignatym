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
<?
//logging in
if(isset($_POST['uname'])){
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    if($uname == "" || $pass == ""){
        throw new Exception("please enter a valid username and password");
    }
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
Guest account available,</br>username and password are "guest".</br>
<div id="info">
    <a href="guide.php" target="_newtab">Guide</a></br></br>
    <a href="http://ignatym.freeforums.net/" target="_newtab">Forums</a></br></br>
    Welcome to the alpha!</br></br></br>
    [ <a href="http://audiogame.ignatym.com">Audio Game</a> in progress. p2p chat not quite working yet! ]
</div>

<?php
include("shared/footer.inc");
?>