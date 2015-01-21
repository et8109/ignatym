<!-----------------
--before anything--
------------------>

<!---------------------------------->
<?php include("shared/header1.inc");?>
<!--inside <head>
------------------------------------>
<title>Ignatym Admin</title>

<!---------------------------------->
<?php include("shared/header2.inc");?>
<!--inside <body>
------------------------------------>
<?
$msg = "";
//resetting db
if(isset($_POST['reset'])){
    if($_POST['reset'] == "RESET"){
        include("../backend/interfaces/adminInterface.php");
        AdminInterface::resetDatabase();
        $msg = "db reset success";
    } else{
        throw new Exception("incorrect");
    }
}
echo $msg;
?>
<form action="admin.php" method="post">
    type RESET to reset db: <input type="text" name="reset" maxlength=5><br>
    <input type="submit">
</form>

<?php
include("shared/footer.inc");
?>