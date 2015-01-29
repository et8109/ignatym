<?php

final class constants {
    const numScenes = 5;
    const maxPlayerItems = 4;
    const maxSceneItems = 4;
    const dbhostName = "localhost";
    const dbusername = "ignatymc_admin";
    const dbpassword = "1Gn4tym";
    const dbname = "ignatymc_game";
    const startSceneID = 101;
    const maxHealth = 5;
    const errorSymbol = "<<-<<";//repeated in js code: main and login
    const numAlertsDivider = "<<<";//repeated in js code
    const initDesc = "I am new, so be nice to me!";
}
/**
 *the maximum text size in the db
 */
final class maxLength {
    const playerDesc = 1000;
    const sceneDesc = 1000;
    const itemDesc = 500;
    const keywordDesc = 255;
    const alertDesc = 100;
    const maxSpanLength = 110;
    const maxEmailLength = 35;
    const username = 20;
    const password = 20;
}

/**
 *the possible actions that are visible in chat.
 *duplicated in js
 */
final class actionTypes {
    const WALKING = 0;
    const ATTACK = 1;
    const MESSAGE = 2;
}

/**
 *The types of spans that you can click for a description
 */
final class spanTypes {
    const ITEM = 0;
    const PLAYER = 1;
    const SCENE = 2;
    const ACTION = 3;
    const KEYWORD = 4;
    const NPC = 5;
    const PATH = 6;
}
/**
 *the numbers corresponding to keyword types
 */
final class keywordTypes {
    const CONTAINER = 0;
    const MATERIAL = 1;
    const QUALITY = 2;
    const SCENE_ACTION = 3;
    const APPSHP = 4;
    const MANAGER = 5;
    const LORD = 6;
    const MONARCH = 7;
    const SPELLBOOK = 8;
    const SPELL = 9;
    const ECT = 10;
}

final class npcTypes {
    const CREATURE = 0;
    const BOSS = 1;
}


/**
 *the names asocciated with each keyword type
 */
$keywordTypeNames = array(
  0 => "container",
  1 => "material",
  2 => "quality",
  3 => "sceneAction",
  4 => "apprenticeship",
  5 => "manager",
  6 => "lord",
  7 => "monarch",
  8 => "spellbook",
  9 => "spell"
);
?>