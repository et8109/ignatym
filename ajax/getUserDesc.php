<?php
require_once("ajaxSetup.php");
require_once(ROOT."/backend/tables/userTable.php");

try{
if($_GET['id'] == $_SESSION['playerID']){
  require_once(ROOT."/backend/objects/user.php");
  $user = User::fromId($_SESSION['playerID']);
  $uname = $user->getUname();
  $desc = $user->getDesc();
  $health = $user->getHealth();
  ?>
      <?=$desc?></br>
      Health: <?=$health?>
      <form id="descForm" onsubmit= "editDesc(); return false;">
        <textArea name="desc" id="inputDesc" maxlength="1000"><?php echo strip_tags($desc); ?></textArea><br/>
        <input type="submit" value="update">
      </form>
<?php
} else{
  echo UserTable::getDesc($_GET["id"])["Description"];
}

} catch(Exception $e){
    include("shared/errorHandler.php");
    ErrorHandler::handle($e);
}

?>
