<?php
/*-------------------------------------------------
|     index.php [ 微信公众平台接口 ]
+--------------------------------------------------
|     Author: Somnus
+------------------------------------------------*/

define("TOKEN","somnus");
$index = 0;
$wechatObj = new wechat();

if (isset($_GET["echostr"]))
{
	$wechatObj->valid();
	exit();
}

$wechatObj->responseMsg();

class wechat {
	
	public static $index = 0;
	
	public function __construct($i)
	{
		self::$index = $i;
	}
	
	public function valid()
	{
		$echoStr = $_GET["echostr"];
		
		//valid signature, option
		if ($this->checkSignature())
		{
			echo $echoStr;
			exit;
		}
	}
	
	public function responseMsg() {

		//---------- 接 收 数 据 ---------- //

		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; //获取POST数据

		//用SimpleXML解析POST过来的XML数据
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

		$fromUsername = $postObj->FromUserName; //获取发送方帐号（OpenID）
		$toUsername = $postObj->ToUserName; //获取接收方账号
		$msgType = trim($postObj->MsgType); //获取消息类型
		$keyword = trim($postObj->Content); //获取消息内容
		$time = time(); //获取当前时间戳

		//---------- 返 回 数 据 ---------- //

		//返回消息模板
		$textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		<FuncFlag>0</FuncFlag>
		</xml>";
		
		switch ($msgType)
		{
			case "text":
			{
				//QQ表情回复
				if (preg_match("/^\/\:/",$keyword))
				{
					$contentStr = $keyword;
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					break;
				}
				
				//调用机器人回复
				include('xiaojo.php');
				//include('simsimi.php');
				//include('yun2d.php');
				
				//$contentStr = SimSimi($keyword); //返回消息内容
				$contentStr = $fromUsername;
				/**
				if ("" == $contentStr)
				{
					$contentStr = yun2d($keyword);
				}
		
				$contentStr = str_replace("小黄鸡","L",$contentStr);
				$contentStr = str_replace("simi","L",$contentStr);
				$contentStr = str_replace("贱鸡","贱L",$contentStr);
				*/
				
				//mysql: root/admin123 wechatdb 表名为wexin_users
				
				if ($contentStr == "")
				{
					$contentStr = "亲，我真么不知道肿么回答这个问题了。。。";
				}
				
				//格式化消息模板
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
				echo $resultStr; //输出结果
				break;
			}
			
			case "event":
			{
				$event = trim($postObj->Event);
				if ($event == "subscribe")
				{
					$contentStr = "主人，您好！我是风流倜傥，玉树临风的L。以后您无聊时可以尽情调戏我~请告诉我应该怎么称呼主人您？（查看使用说明请输入help）"; 

					//格式化消息模板
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
				}
				
				break;
			}
			
			default:
				break;
		}
		
	}
	
	private function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		
		if ($tmpStr == $signature)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
?>