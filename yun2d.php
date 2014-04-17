<?php
/*-------------------------------------------------
|     yun2d.php [ 智能聊天（yun_robot） ]
+--------------------------------------------------
|     Author: Somnus
+------------------------------------------------*/

function yun2d($keyword) {
	
	$robot = "360";
	$token = "648bfec03b31ed58e032c0c22c8929c1e25a7db2";
	$url_yun2d = "http://api.yun2d.com/robot/ask?api_robot=".$robot."&api_token=".$token."&question=".$keyword;
	
	$json = file_get_contents($url_yun2d);
	
	$result = json_decode($json, true);
	
	$response = $result['data'];
	
	if (!empty($response))
	{
		return $response[0]['text'];
	}
	else
	{
		return "";
	}
	
}

?>