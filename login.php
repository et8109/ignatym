<?php
ob_start();
session_start();
if(isset($_SESSION['playerID'])){
    header("Location: scene.php");
}
require_once("shared/phpHelpers.php");
try{
if(isset($_POST['uname'])){
    require_once(getcwd()."/backend/objects/user.php");
    User::login($_POST['uname'], $_POST['pword']);
    header("Location: scene.php");
}
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}


include("shared/header.inc");?>

<title>Ignatym</title>
<link rel="stylesheet" type="text/css" href="css/login.css" />
</head>
<body>

<!-- login form -->
<form action="login.php" method="post">
    I am <input type="text" name="uname" maxlength=20><br>
    Password: <input type="password" name="pword" maxlength=20><br>
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
</body>
</html>
