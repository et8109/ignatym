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
    require_once("../backend/objects/user.php");
    if(isset($_POST['desc'])){
	User::shortcut_setDesc($_SESSION['playerID'], $_POST['desc']);
    } else{
        $desc = User::shortcut_getDesc($_SESSION['playerID']); ?>

	editing desc
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
