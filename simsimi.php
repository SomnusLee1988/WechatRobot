<?php
/*-------------------------------------------------
|     simsimi.php [ 智能聊天（simsimi） ]
+--------------------------------------------------
|     Author: Somnus 
+------------------------------------------------*/

function SimSimi($keyword) {

	/**
	//----------- 获取COOKIE ----------//
	$url = "http://www.simsimi.com/";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$content = curl_exec($ch);
	list($header, $body) = explode("\r\n\r\n", $content);
	preg_match("/set\-cookie:([^\r\n]*);/iU", $header, $matches);
	$cookie = $matches[1];
	curl_close($ch);

	//----------- 抓 取 回 复 ----------//
	//$url = "http://www.simsimi.com/func/req?lc=ch&msg=$keyword";
	$url = "http://api.yun2d.com/robot/ask?api_robot=360&api_token=648bfec03b31ed58e032c0c22c8929c1e25a7db2&question=$keyword";
	$ch = curl_init($url);
	//curl_setopt($ch, CURLOPT_REFERER, "http://www.simsimi.com/talk.htm?lc=ch");
	curl_setopt($ch, CURLOPT_REFERER, "http://yun2d.com/products/robots");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	$content = json_decode(curl_exec($ch),1);
	curl_close($ch);
	*/
	
	$key = "42765d82-2d65-4cee-9e5d-6b575901b99f";
	$url_simsimi = "http://sandbox.api.simsimi.com/request.p?key=".$key."&lc=ch&ft=0.0&text=".$keyword;
	
	$json = file_get_contents($url_simsimi);
	
	$result = json_decode($json, true);
	
	$response = $result['response'];
	
	if (!empty($response))
	{
		return $response;
	}
	else
	{
		return "";
	}
	
  /**
	if($content['result']=='100') {
		$content['response'];
		return $content['response'];
	} else {
		return '我还不会回答这个问题...';
	}
	*/
}

?>