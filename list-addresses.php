<?php
// SMSified Library Version 0.1b
//By Benjamin Townsend

// load my library
require("sms-config.php");
require("library.php");

$con = mysql_connect("$Host","$DB_usr","$DB_pass");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("$Database", $con);

$result = mysql_query("SELECT * FROM `$Database`.`contacts`;");
  $myMessage = $senderName . ": ".$message;
  while($row = mysql_fetch_array($result)) 
  {
	ECHO substr($row['address'], -11);
	echo "<br />";
	}
	mysql_close($con);
  ?>