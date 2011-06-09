<?php

// SMSified Library Version 0.1b
//Copyright 2011 Benjamin Townsend

function sendMessage($address,$sentMessage)
{
$sentMessage = urlencode($sentMessage);
$address = substr($address, -11);
 define('POSTVARS', 'address='.$address.'&message='.$sentMessage);  // POST VARIABLES TO BE SENT
 $ch='';
 $Rec_Data='';
 $ch = curl_init(POSTURL);
 curl_setopt($ch, CURLOPT_POST      ,1);
 curl_setopt($ch, CURLOPT_POSTFIELDS    ,POSTVARS);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1); 
 curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL

 $Rec_Data = curl_exec($ch);

$log = mysql_connect("$Host","$DB_usr","$DB_pass");
if (!$log)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("$townscom_sms", $log);
mysql_query("INSERT INTO `$Database`.`send_log` (`address`,`message`,`response`) VALUES ('$address','$sentMessage','$Rec_Data');");
 mysql_close($con);
curl_close($ch);

}

?>