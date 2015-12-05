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
    if(isset($_GET["id"])){
        require_once("../backend/logic/descLogic.php");
        $info = DescLogic::getSceneDesc($_GET["id"]);
        ?>
	<div>
    	    <?=$info["Name"]?></br>
            <?=$info["Description"]?>
	</div>
<?php
    } else{
	echo "no scene specified";
    }
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
?>
<!---------------------------------->
<?php include("shared/footer1.inc");?>
<!--outside <body>
------------------------------------>
<!---------------------------------->
<?php include("shared/footer2.inc");?>
<!--inside <html>
------------------------------------>
