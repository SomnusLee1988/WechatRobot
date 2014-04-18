<?php
/*-------------------------------------------------
|     index.php [ 微信公众平台接口 ]
+--------------------------------------------------
|     Author: Somnus
+------------------------------------------------*/

define("TOKEN","somnus");
define("AUTHOR","孙碧清");
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
				//include('xiaojo.php');
				include('simsimi.php');
				//include('yun2d.php');
				
				$contentStr = SimSimi($keyword); //返回消息内容
				/**
				if ("" == $contentStr)
				{
					$contentStr = yun2d($keyword);
				}
				*/
		
				$contentStr = str_replace("小黄鸡","L",$contentStr);
				$contentStr = str_replace("小鸡鸡","L",$contentStr);
				$contentStr = str_replace("simi","L",$contentStr);
				$contentStr = str_replace("贱鸡","贱L",$contentStr);
				
				
				$sql = "select msgcount from users where userid = '".$fromUsername."'";
				$result = mysql_fetch_array($this->dboption($sql));
				$msgcount = $result[0];
				
				$msgcount++;
				if ($msgcount == 1)
				{
					//回复
					$contentStr = "Hi, ".$keyword.",现在请随便问我点问题试试看吧~比如输入\"讲个笑话\"。";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					$sql = "update users set username = '".$keyword."',msgcount = ".$msgcount." where userid = '".$fromUsername."'";
					$this->dboption($sql);
					
					//记录消息内容
					$sql = "insert into msgs (userid,send,receive,recordid) values ('".$fromUsername."','".$keyword."','".$contentStr."','".$msgcount."')";
					$this->dboption($sql);
				}
				else if ($msgcount == 4)
				{
					$sql = "select username from users where userid = '".$fromUsername."'";
					$result = mysql_fetch_array($this->dboption($sql));
					$username = $result[0];
					if (empty($username) || $username == "")
					{
						$username = "主人";
					}
					$contentStr = $username.", 你问的问题弱爆了,我懒得回答了。要不我用".AUTHOR."的一个秘密跟你交换一个秘密吧?[坏笑]";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					//更新消息数量
					$sql = "update users set msgcount = ".$msgcount." where userid = '".$fromUsername."'";
					$this->dboption($sql);
					//记录消息内容
					$sql = "insert into msgs (userid,send,receive,recordid) values ('".$fromUsername."','".$keyword."','".$contentStr."','".$msgcount."')";
					$this->dboption($sql);
				}
				else if ($msgcount == 5)
				{
					$contentStr = "偷偷告诉你，".AUTHOR."曾经跟我打赌打输了，裸奔从我们四楼走到了一楼。[大笑]";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					//更新消息数量
					$sql = "update users set msgcount = ".$msgcount." where userid = '".$fromUsername."'";
					$this->dboption($sql);
					//记录消息内容
					$sql = "insert into msgs (userid,send,receive,recordid) values ('".$fromUsername."','".$keyword."','".$contentStr."','".$msgcount."')";
					$this->dboption($sql);
				}
				else if ($msgcount == 6)
				{
					$contentStr = "不管你信不信,反正我是信了！好了，现在轮到你告诉我一个你自己的秘密了~[阴险]";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					//更新消息数量
					$sql = "update users set msgcount = ".$msgcount." where userid = '".$fromUsername."'";
					$this->dboption($sql);
					//记录消息内容
					$sql = "insert into msgs (userid,send,receive,recordid) values ('".$fromUsername."','".$keyword."','".$contentStr."','".$msgcount."')";
					$this->dboption($sql);
				}
				else if ($msgcount == 7)
				{
					$sql = "select username from users where userid = '".$fromUsername."'";
					$result = mysql_fetch_array($this->dboption($sql));
					$username = $result[0];
					if (empty($username) || $username == "")
					{
						$username = "主人";
					}
					$contentStr = $username.", 耍赖可不好哦！本来还想再分享一个".AUTHOR."的更劲爆的秘密呢！不想听就算了，我去找小伙伴玩了~";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					//更新消息数量
					$sql = "update users set msgcount = ".$msgcount." where userid = '".$fromUsername."'";
					$this->dboption($sql);
					//记录消息内容
					$sql = "insert into msgs (userid,send,receive,recordid) values ('".$fromUsername."','".$keyword."','".$contentStr."','".$msgcount."')";
					$this->dboption($sql);
				}
				else if ($msgcount == 8)
				{
					$contentStr = "真想听？那这次可不许忽悠我！等我说完，你也要说一个你自己的秘密！";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					//更新消息数量
					$sql = "update users set msgcount = ".$msgcount." where userid = '".$fromUsername."'";
					$this->dboption($sql);
					//记录消息内容
					$sql = "insert into msgs (userid,send,receive,recordid) values ('".$fromUsername."','".$keyword."','".$contentStr."','".$msgcount."')";
					$this->dboption($sql);
				}
				else if ($msgcount == 9)
				{
					$contentStr = AUTHOR."他对一个妹子一见钟情了";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					//更新消息数量
					$sql = "update users set msgcount = ".$msgcount." where userid = '".$fromUsername."'";
					$this->dboption($sql);
					//记录消息内容
					$sql = "insert into msgs (userid,send,receive,recordid) values ('".$fromUsername."','".$keyword."','".$contentStr."','".$msgcount."')";
					$this->dboption($sql);
				}

				else
				{
					if ($contentStr == "")
					{
						$contentStr = "亲，我真的不知道肿么回答这个问题了。。。";
					}
				
					//格式化消息模板
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					//更新消息数量
					$sql = "update users set msgcount = ".$msgcount." where userid = '".$fromUsername."'";
					$this->dboption($sql);
					
					//记录消息内容
					$sql = "insert into msgs (userid,send,receive,recordid) values ('".$fromUsername."','".$keyword."','".$contentStr."','".$msgcount."')";
					$this->dboption($sql);
				}
				
				break;
			}
			
			case "event":
			{
				$event = trim($postObj->Event);
				if ($event == "subscribe")
				{
					$contentStr = "主人，您好！我是风流倜傥，玉树临风的L。以后您无聊时可以尽情调戏我~请告诉我应该怎么称呼主人您？"; 

					//格式化消息模板
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $contentStr);
					echo $resultStr; //输出结果
					
					//记录用户
					$sql = "insert into users (userid,msgcount) values ('".$fromUsername."',0)";
					$this->dboption($sql);
				}
				else if ($event == "unsubscribe")
				{
					//删除用户
					$sql = "delete from users where userid = '".$fromUsername."'";
					$this->dboption($sql);
				}
				
				break;
			}
			
			default:
				break;
		}
		
	}
	
	public function dboption($sql)
	{
		//mysql: root/admin123 wechatdb 表名为users
				
		$servername = "localhost";
		$username = "root";
		$password = "admin123";
				
		$con = mysql_connect($servername,$username,$password);
		$program_char = "utf8";
				
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
				
		mysql_select_db("wechatdb", $con);
		mysql_set_charset($program_char,$con);
		$charset = mysql_client_encoding($con);
		$result = mysql_query($sql);
				
		mysql_close($con);
		
		return $result;
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