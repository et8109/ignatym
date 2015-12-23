<?php
/**
 * Sets players current scene.
 * Returns new scene desc and other info.
 */
require_once("ajaxSetup.php");
if(isset($_GET['sid'])){
    require_once(ROOT."/backend/objects/user.php");
    require_once(ROOT."/backend/objects/scene.php");

    //move user
    User::shortcut_walk($_SESSION['playerID'], $_SESSION['currentScene'], $_GET['sid'], $_SESSION['playerName']);
    $_SESSION['currentScene'] = $_GET['sid'];

    //send scene data
    $scene = Scene::fromId($_SESSION['currentScene']);
    $desc = $scene->getDesc();
    $name = $scene->getName();
    $players = $scene->getPlayers();
    $npcs = $scene->getNpcs();
?>
    <div id="main">
        <?=$name?></br>
        <?=$desc?>
<?php
    foreach($npcs as $n){
        echo "</br>$n ";
    }
    echo "</br>";
    foreach($players as $p){
        echo "$p ";
    }
?>
    </div>
<?php
    }
?>
