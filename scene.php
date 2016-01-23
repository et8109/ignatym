<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }

include("shared/phpHelpers.php");
include("shared/header.inc");?>

<title>Description</title>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/scene.js"></script>
</head>

<?php
try{
    require_once(ROOT."/backend/objects/scene.php");
    require_once(ROOT."/backend/objects/user.php");
    require_once(ROOT."/backend/objects/npc.php");
    ?>
    <div id="log">
    </div>
    <div id="dlist">
    <div class="desc" id="0">
    <span class="action" onclick=walk(<?=$_SESSION['currentScene']?>)>Awaken</span>
    </div>
    </div>
<?php
} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}
?>
</br></br>
<a href="logout.php">Logout</a>
</body>
</html>
