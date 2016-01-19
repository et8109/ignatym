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

include("shared/phpHelpers.php");
include("shared/header.inc");

<title>Description</title>
</head>

<?php
try{
    require_once(ROOT."/backend/objects/user.php");
    if(isset($_POST['desc'])){
	User::shortcut_setDesc($_SESSION['playerID'], $_POST['desc']);
    } else{
        $desc = User::shortcut_getDesc($_SESSION['playerID'], False); ?>

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
</body>
</html>
