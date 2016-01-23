<?php
require_once("ajaxSetup.php");
require_once(ROOT."/backend/tables/keywordTable.php");
echo KeywordTable::getKeywordDesc($_GET["id"]);
?>
