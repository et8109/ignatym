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
<!---------------------------------->
<?php include("shared/header2.inc");?>
<!--inside <body>
------------------------------------>
<?php
try{
    require_once("../backend/logic/descLogic.php");
    if(isset($_POST['desc'])){
        DescLogic::setSceneDesc($_SESSION['currentScene'], $_POST['desc']);
    ?>
<?php
    } else{
        $info = DescLogic::getSceneDesc($_SESSION['currentScene']);
	$desc = $info["Description"];
	$name = $info["Name"];
?>
	editing <?=$name?>
        <form method="post">
            <textArea name="desc" id="textArea" maxlength="1000"><?=$desc?></textArea><br/>
            <input type="submit" value="update">
        </form>
<?php
    }
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
?>
<a href="scene.php">Back to scene</a>
<!---------------------------------->
<?php include("shared/footer1.inc");?>
<!--outside <body>
------------------------------------>
<!---------------------------------->
<?php include("shared/footer2.inc");?>
<!--inside <html>
------------------------------------>
