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
<title>logout</title>
<!---------------------------------->
<?php include("shared/header2.inc");?>
<!--inside <body>
------------------------------------>
<?php
try{
    require_once("../backend/logic/userLogic.php");
    UserLogic::logoutUser($_SESSION['playerID']);
    session_destroy();
    header("Location: index.php");
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
?>

<?php
include("shared/footer.inc");
?>