<?php
/**
  * 微信公众帐号服务器访问接口
  * wenhuaqiang@gmail.com
  */

//define your token
define("TOKEN", "daonile");
$wechatObj = new daonileCallbackapi();
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $wechatObj->responseMsg();
}else if($_SERVER['REQUEST_METHOD'] == "GET"){
    $wechatObj->valid();
}

class daonileCallbackapi
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "[到你了]多亏朋友们的支持，目前已经有近400粉丝，当我们的粉丝数到达500时，就可以申请认证了。我们的开发工作现在也在顺利地进行中，您现在收到的这条消息是从我们的后台自动发出的，如果您有什么问题或建议，请发邮件到wenhuaqiang@gmail.com或访问http://113.107.233.169/dokuwiki/，再次感谢您的支持";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
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
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>
