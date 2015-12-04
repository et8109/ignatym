<!-----------------
--before anything--
------------------>
<?php
ob_start();
session_start();
if(!isset($_SESSION['playerID'])){
        header("Location: login.php");
    }
?>

<!---------------------------------->
<?php include("shared/header1.inc");?>
<!--inside <head>
------------------------------------>
<title>Ignatym</title>
<link rel="stylesheet" type="text/css" href="css/TextCombat.css" />
<audio id="anvil">
    <source src="sounds/anvil.wav" type="audio/wav"/>
    Your browser doesn't support wav sound
</audio>
<!---------------------------------->
<?php include("shared/header2.inc");?>
<!--inside <body>
------------------------------------>
<!--  main area, text on top, then input and buttons, then extra on bottom -->
<div class="textBox" id="textBox1">
</div>
<div class="textBox" id="textBox2">
</div>
<br/>
<!-- holds the input, buttons, and text area -->
<div id="hub">
<!-- shared error message -->
<img id="errorPoint" src="../images/errorPoint.png" style="visibility: hidden"><span id="error" style="color: black"></span></br>
<span id="wait"></span></br>
<input id="input" disabled="true" type="text" id="input" maxlength="100" onkeypress="textTyped(event)"></input></br>
<span id="logout" onclick="logout()">[log out]</span>
<span id="hubName" onclick="displayMyDesc()"><?php echo $_SESSION['playerName'];?></span>
<span id="alert" onclick="openMenu()">alerts[]</span>

<div id="extra">
    <textArea id="textArea" maxlength="1000"></textArea><br/>
    <span class="textAreaButton" onclick="textAreaSubmit()">Done</span>
    <span class="textAreaButton" onclick="closeTextArea(), endListening()">Cancel</span><br/>
</div>
</div>
<!-- The notifications popup -->
<div id="menuMain">
<div class="menuButton" id="alertsMenuButton" onclick="openAlerts()"></div>
<div class="menuButton" id="optionsMenuButton" onclick="openOptions()"></div>
<div id="menuMainInside">
</div>
<span id="menuMainClose" onclick="closeMenu()">Close</span>
<span id="menuMainMute" onclick="toggleMute()">Mute</span>
</div>

<!---------------------------------->
<?php include("shared/footer1.inc");?>
<!--outside <body>
------------------------------------>
<script src="js/TextCombat.js"></script>
<!---------------------------------->
<?php include("shared/footer2.inc");?>
<!--inside <html>
------------------------------------>
