<?php

// SMSified Library Version 0.1b
//Copyright 2011 Benjamin Townsend
require("sms-config.php");
$address = "15853562479";
$sentMessage = date("d/m/y : H:i:s", time());

$sentMessage = urlencode($sentMessage);
$address = substr($address, -11);
  define('POSTURL', 'https://benjaminarthurt:benben@api.smsified.com/v1/smsmessaging/outbound/13153149701/requests');
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
 
 $con = mysql_connect("$Host","$DB_usr","$DB_pass");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("$Database", $con);

mysql_query("INSERT INTO `$Database`.`send_log` (`address`,`message`,`response`) VALUES ('$address','$sentMessage','$Rec_Data');"); 
  echo $Rec_Data;
  
  mysql_close($con);
  curl_close($ch);

?>