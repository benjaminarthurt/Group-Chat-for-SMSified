<?php

// SMSified Library Version 0.1b

//Copyright 2011 Benjamin Townsend

function sendMessage($address,$sentMessage)

{

$sentMessage = urlencode($sentMessage);

$address = substr($address, -11);

 define('POSTURL', 'https://'.$Username.':'.$Password.'@api.smsified.com/v1/smsmessaging/outbound/'.$Phone.'/requests');

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

  echo $Rec_Data;

  curl_close($ch);

}







?>