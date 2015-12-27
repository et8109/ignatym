<?php
/**
 * Sets players current scene.
 * Returns new scene desc and other info.
 */
require_once("ajaxSetup.php");
if(isset($_GET['sid'])){
    require_once(ROOT."/backend/objects/user.php");
    require_once(ROOT."/backend/objects/scene.php");
    require_once(ROOT."/backend/objects/npc.php");

    //move user
    User::shortcut_walk($_SESSION['playerID'], $_SESSION['currentScene'], $_GET['sid'], $_SESSION['playerName']);
    $_SESSION['currentScene'] = $_GET['sid'];

    //send scene data
    $scene = Scene::fromId($_SESSION['currentScene']);
    $desc = $scene->getDesc();
    $name = $scene->getName();
    $players = User::usersInScene($scene->getId());
    $npcs = Npc::npcsInScene($scene->getId());
?>
        <?=$name?></br>
        <?=$desc?>
<?php
    foreach($npcs as $n){
	$html = $n->getHtml();
        echo "</br>$html ";
    }
    echo "</br>";
    foreach($players as $p){
        $html = $p->getHtml();
        echo "$html ";
    }
}
?>
