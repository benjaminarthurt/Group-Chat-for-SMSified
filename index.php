<?php 
require("sms-config.php");
?>

<h1>SMSified Group Chat System</h1><small>Version 0.1 beta - not intended for production/public use</small><br /><br />
<br />
<h3>Instructions:</h3>
Send: "Join" to <?php echo $Phone; ?><br />
Follow SMS Instructions. Reply OUT at anytime to stop messges.<br />
<h2>5 Most Recent Posts</h2>

<?php
$con = mysql_connect("$Host","$DB_usr","$DB_pass");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("$Database", $con);
$result = mysql_query("SELECT * FROM  `$Database`.`sms` ORDER BY DateTime DESC LIMIT 5;");
while($row = mysql_fetch_array($result))
  {
  $from = substr($row['from'],-11);
  $names = mysql_query("SELECT * FROM  `$Database`.`contacts` WHERE `address`='" . $from . "' LIMIT 1;");
  while($senderName = mysql_fetch_array($names))
  {
  echo "From: <b>" . $senderName['name'] . "</b> Message: <b>".  $row['message']."</b>";
  echo "<br />";
  }
  }
?>

<br />
<h2>Users Signed IN</h2>

<?php
  $names = mysql_query("SELECT * FROM  `$Database`.`contacts` WHERE `status`='1';");
  while($senderName = mysql_fetch_array($names))
  {
  $lastthree =  substr($senderName['address'],-3);
  echo "<b>" . $senderName['name'] . "</b> ". $lastthree;
  echo "<br />";
  }
mysql_close($con);
?>

<br />
<small>SMSified Group Chat by Benjamin Townsend</small>