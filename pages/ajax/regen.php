<?php
/**
 * Heals the user to full health;
 */
require_once("ajaxSetup.php");
require_once(ROOT."/backend/objects/user.php");
User::shortcut_regen($_SESSION['playerID']);
echo "</br>Fuly healed";
?>
