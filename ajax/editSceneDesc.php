<?php
/**
 * Saves new scene desc.
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/scene.php");
try{
    Scene::shortcut_setDesc($_SESSION['currentScene'], $_POST['desc']);
    Response::addLog("set desc");
    Response::send();
} catch (Exception $e){
    Response::addLog($e->getMessage());
    Response::send();
}
?>
