<?php
// SMSified Library Version 0.1b
//By Benjamin Townsend

// load my library
require("sms-config.php");
require("library.php");
//Connect to Database
$con = mysql_connect("$host","$DB_usr","$DB_pass");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("$Database", $con);

// Get the JSON payload sumbitted from SMSified.
$json = file_get_contents("php://input");

		$notification = json_decode($json);
		$timeStamp = $notification->inboundSMSMessageNotification->inboundSMSMessage->dateTime;
		$destinationAddress = $notification->inboundSMSMessageNotification->inboundSMSMessage->destinationAddress;
		$message = $notification->inboundSMSMessageNotification->inboundSMSMessage->message;
		$messageId = $notification->inboundSMSMessageNotification->inboundSMSMessage->messageId;
		$senderAddress = $notification->inboundSMSMessageNotification->inboundSMSMessage->senderAddress;

$command = strtoupper($message);
$senderAddress = substr($senderAddress, -11);

//check if sender is in address book
$result = mysql_query("SELECT `name` FROM  `$Database`.`contacts` WHERE `address` =".$senderAddress.";");
$num_rows = mysql_num_rows($result);
IF ($num_rows < 1)
  {
  //address not in contacts send request asking for name and set name to unknown
    $myMessage = "Please reply with your first name";
    sendMessage($senderAddress,$myMessage);
    mysql_query("INSERT INTO  `$Database`.`contacts` (`address`) VALUES ('$senderAddress');");
  }ELSE
  {   
  $senderInfo = mysql_query("SELECT * FROM  `$Database`.`contacts` WHERE `address` =".$senderAddress." LIMIT 1;");
  
  while($row = mysql_fetch_array($senderInfo)) 
  {
	$senderName = $row['name'];
	$senderStatus = $row['status'];
	$senderBan = $row['ban'];
  } 
    // if name = unknown set name = message
    IF ($senderName == "unknown")
    {
    mysql_query("UPDATE `$Database`.`contacts` SET `name` = '".$message."', `status`='1' WHERE `address` = '".$senderAddress."';");
    $myMessage = "Welcome: ".$message."!";
    sendMessage($senderAddress,$myMessage);
    }
    //if name != unknown and name != banned and name is in addressbook and message = out set status = 0 reply with "messages stoped, send in to start again"  
    ELSEIF ($senderName != "unknown" and $command == "OUT")
    {
    $myMessage = "Messages Stopped. Reply IN to turn them back on";
    sendMessage($senderAddress,$myMessage);
    mysql_query("UPDATE `$Database`.`contacts` SET `status` = '0' WHERE `address` = '".$senderAddress."';");
    }  
    //else if name != unknown  and name != banned and name is in addressbook and message != out set status = 1 broadcast message to all users with status = 1 Add text to end saying "reply OUT to stop"
    ELSEIF ($senderName != "unknown" and $command == "IN")
    {
    mysql_query("UPDATE `$Database`.`contacts` SET `status` = '1' WHERE `address` = '".$senderAddress."';");
    $myMessage = $senderName . " Signed In";
    sendMessage($senderAddress,$myMessage);
    }
    ELSEIF ($senderName != "unknown" and $senderBan == 1 and $senderStatus == 1 and $command != "IN" and $command != "OUT")
    {
    // is banned, not signing in or out. muust be attempting to send a message
    $myMessage = $senderName . " You are not authorized to send messages to this group.";
    sendMessage($senderAddress,$myMessage);
    }
    ELSEIF ($senderName != "unknown" and $senderBan != 1 and $senderStatus == 1 and $command != "IN" and $command != "OUT")
    {
  $result = mysql_query("SELECT * FROM `$Database`.`contacts`;");
  $myMessage = $senderName . ": ".$message;
  while($row = mysql_fetch_array($result)) 
  {
	$Send = substr($row['address'], -11);
	sendMessage($Send,$myMessage);
	sleep(1);
  }
   mysql_query("INSERT INTO  `$Database`.`sms` (`from`,`message`) VALUES ('$senderAddress','$message');");
   }
    ELSE 
    {
    $myMessage = $senderName . ": An Error Occured. Are you authorized to send to this group? Are you signed IN?";
    sendMessage($senderAddress,$myMessage);
    } 
  }
mysql_close($con);
?>

