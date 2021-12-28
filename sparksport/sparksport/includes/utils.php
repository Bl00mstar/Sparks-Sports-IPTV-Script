<?php


function GetLoginToken()
{
    global $SPARK_USER_TOKEN_API;
    global $SPARK_ACCOUNT_USERNAME;
    global $SPARK_ACCOUNT_PASSWORD;
    global $SPARK_ACCOUNT_DEVICE_ID;

    global $SPARK_DEFAULT_TOKEN;
    global $SPARK_DEFAULT_USERAGENT;
    
    if (file_exists("login.token"))
    {
		$currentTime = time();
		$tt = explode('.',file_get_contents("login.token"))[1];
		$json = json_decode(base64_decode($tt));
		$expTime = $json->exp;
		
		if ($currentTime < $expTime)
		{
			return file_get_contents("login.token");
		}
		else
		{
			$ch = curl_init($SPARK_USER_TOKEN_API);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'User-Agent: ' . $SPARK_DEFAULT_USERAGENT,
				'Authorization: Bearer ' . $SPARK_DEFAULT_TOKEN,
				'X-Forwarded-For: 202.89.4.222'
			));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,'{"username":"' . $SPARK_ACCOUNT_USERNAME . '", "password":"' . $SPARK_ACCOUNT_PASSWORD . '", "deviceID": "' . $SPARK_ACCOUNT_DEVICE_ID . '"}');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$content = curl_exec($ch);
			curl_close($ch);
			$json =  json_decode($content);
			file_put_contents("login.token",$json->sessionToken);
			return $json->sessionToken;
		}
    }
    else
    {
        $ch = curl_init($SPARK_USER_TOKEN_API);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: ' . $SPARK_DEFAULT_USERAGENT,
            'Authorization: Bearer ' . $SPARK_DEFAULT_TOKEN,
            'X-Forwarded-For: 202.89.4.222'
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"username":"' . $SPARK_ACCOUNT_USERNAME . '", "password":"' . $SPARK_ACCOUNT_PASSWORD . '", "deviceID": "' . $SPARK_ACCOUNT_DEVICE_ID . '"}');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $content = curl_exec($ch);
        curl_close($ch);
        $json =  json_decode($content);
        file_put_contents("login.token",$json->sessionToken);
        return $json->sessionToken;
    }
}

function PutOffLastChannel()
{
    global $SPARK_STOP_STREAM_API;
    global $SPARK_DEFAULT_USERAGENT;

    if (file_exists("last_channel.id"))
    {
        $lastChannelID = file_get_contents("last_channel.id");
        $ch = curl_init($SPARK_STOP_STREAM_API . $lastChannelID . "/stopped");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: ' . $SPARK_DEFAULT_USERAGENT,
            'Authorization: Bearer ' . GetLoginToken(),
            'X-Forwarded-For: 202.89.4.222'
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $content = curl_exec($ch);
        curl_close($ch);
        //
        unlink("last_channel.id");
    }
}

function GetChannelAssetID($id)
{
    global $SPARK_CHANNELS_API;
    global $SPARK_DEFAULT_USERAGENT;

    $ch = curl_init($SPARK_CHANNELS_API);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: ' . $SPARK_DEFAULT_USERAGENT,
            'Authorization: Bearer ' . GetLoginToken(),
            'X-Forwarded-For: 202.89.4.222'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $content = curl_exec($ch);
        curl_close($ch);
        $json =  json_decode($content,1);
        
        foreach($json["epg/stations"] as $stations)
        {
            if ($stations["id"] == $id)
            {
                return $stations["assetIDs"][0];
            }
        }

        return "not-found";
}

function GetChannelMPD($id)
{
    global $SPARK_MPD_API;
    global $SPARK_DEFAULT_USERAGENT;

    $channelAssetID = GetChannelAssetID($id);

    $ch = curl_init($SPARK_MPD_API . $channelAssetID);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: ' . $SPARK_DEFAULT_USERAGENT,
            'Authorization: Bearer ' . GetLoginToken(),
            'X-Forwarded-For: 202.89.4.222'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $content = curl_exec($ch);
        curl_close($ch);
        $json =  json_decode($content,1);

        return $json["assets"][0]["liveURLs"]["dash"]["primary"];
}

function GetEntitlementToken($id,$mpdUrl)
{
    global $SPARK_ENTITLEMENT_TOKEN;
    global $SPARK_ACCOUNT_DEVICE_ID;
    global $SPARK_DEFAULT_USERAGENT;

    $channelAssetID = GetChannelAssetID($id);
    
    $ch = curl_init($SPARK_ENTITLEMENT_TOKEN);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: ' . $SPARK_DEFAULT_USERAGENT,
            'Authorization: Bearer ' . GetLoginToken(),
            'X-Forwarded-For: 202.89.4.222'
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'{"assetID":"' . $id . '", "playbackUrl":"' . $mpdUrl . '", "deviceID": "' . $SPARK_ACCOUNT_DEVICE_ID . '"}');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $content = curl_exec($ch);
        curl_close($ch);

        $json =  json_decode($content);

        return $json->entitlementToken;
}