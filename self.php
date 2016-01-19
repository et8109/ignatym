<!-----------------
--before anything--
------------------>
<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }

include("shared/phpHelpers.php");
include("shared/header.inc");?>

<title>Self</title>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>

<?php
try{
    require_once(ROOT."/backend/objects/user.php");
    $user = User::fromId($_SESSION['playerID']);
    $uname = $user->getUname();
    $desc = $user->getDesc();
    $health = $user->getHealth();
    ?>
        Profile page</br>
        <?=$uname?></br>
        <?=$desc?></br>
        <a href="editDesc.php">Edit my description</a></br>
        Health: <?=$health?>
<?php
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
?>
</br></br>
<a href="scene.php">Back to scene</a></br></br>
<a href="logout.php">Logout</a>
</body>
</html>
