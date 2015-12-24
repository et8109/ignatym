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
    $health = $npc->getHealth();
    $color = "";
    if($health == 0){
      $color = "black";
    } else{
      $color = "90".(floor(($health/NPC::MAX_HEALTH)*99))."00";
    }
    $update = [
	'type' => "npc",
        'id' => $npc->getId(),
        'color' => $color
    ];
    Response::$updates[] = $update;
  }
}
?>
