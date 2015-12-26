<?php
/**
 Checks that the player is logged in.
 imports the constants page.
 */
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
require_once("../../constants.php");

class Response {
  private static $main = "";
  private static $desc = "";
  private static $log = [];
  private static $updates = [];

  static function addLog($txt){
    Response::$log[] = $txt;
  }

  static function send(){
    $data = [
      'main' => Response::$main,
      'desc' => Response::$desc,
      'log' => Response::$log,
      'upd' => Response::$updates
    ];
    echo json_encode($data);
  }

  static function updateNpc($npc){
    $update = [
	'type' => "npc",
        'id' => $npc->getId(),
        'classes' => $npc->getCssClasses()
    ];
    Response::$updates[] = $update;
  }

  static function updateUser($user){
    $update = [
        'type' => "user",
        'id' => $user->getId(),
        'classes' => $user->getCssClasses()
    ];
    Response::$updates[] = $update;
  }
}
?>
