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
require_once("../backend/logic/descLogic.php");
$desc = DescLogic::getUserDesc($_SESSION['playerID']);
?>
Editing description
<form>
    <textArea id="textArea" maxlength="1000"><?=$desc?></textArea><br/>
    <input type="submit" value="update">
</form>
<!---------------------------------->
<?php include("shared/footer1.inc");?>
<!--outside <body>
------------------------------------>
<!---------------------------------->
<?php include("shared/footer2.inc");?>
<!--inside <html>
------------------------------------>
