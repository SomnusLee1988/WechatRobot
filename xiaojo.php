<?php
/*-------------------------------------------------
|     xiaojo.php [ 智能聊天（xiaojo） ]
+--------------------------------------------------
|     Author: Somnus
+------------------------------------------------*/

function xiaojo($keyword) {
	
	$yourdb = "KobeBryantKid";
	$yourpw = "ghost19881108";
	$keyword = urlencode($keyword);
	$yourdb = urlencode($yourdb);
	$from = urlencode($from);
	$to = urlencode($to);
	$post = "chat=".$keyword."&db=".$yourdb."&pw=".$yourpw;
	$api = "http://www.xiaojo.com/api5.php";
	$replys = urldecode(curlpost($post, $api));
	//$reply = media(urldecode($replys));
	return $replys;
	
}

function curlpost($curlPost, $url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

?>