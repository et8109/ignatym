<!-----------------
--before anything--
------------------>
<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
?>

<!---------------------------------->
<?php include("shared/header1.inc");?>
<!--inside <head>
------------------------------------>
<title>Description</title>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/scene.js"></script>

<!---------------------------------->
<?php include("shared/header2.inc");?>
<!--inside <body>
------------------------------------>
<?php
try{
    require_once("../backend/objects/scene.php");
    $scene = Scene::fromId($_SESSION['currentScene']);
    $desc = $scene->getDesc();
    $name = $scene->getName();
    $players = $scene->getPlayers();
    ?>
    <div>
        <?=$name?></br>
        <?=$desc?>
    </div>
    <div id="desc">
    </div>
    </br>
<?php
    foreach($players as $p){
	echo "$p ";
    }
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
?>
</br></br>
<a href="editDesc.php">Edit my description</a></br>
<a href="editScene.php">Edit scene</a></br>
<a href="logout.php">Logout</a>
<!---------------------------------->
<?php include("shared/footer1.inc");?>
<!--outside <body>
------------------------------------>
<!---------------------------------->
<?php include("shared/footer2.inc");?>
<!--inside <html>
------------------------------------>
