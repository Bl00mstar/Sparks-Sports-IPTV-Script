<?php

require_once("includes/config.php");
require_once("includes/utils.php");

//Set comments here in PROD
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');

function stringToBinary($string)
{
    $characters = str_split($string);
 
    $binary = [];
    foreach ($characters as $character) {
        $data = unpack('H*', $character);
        $binary[] = base_convert($data[1], 16, 2);
    }
 
    return implode(' ', $binary);    
}

function binaryToJsonChallenge($split)
{
	$count = count($split);
	$conv = array();
	for($i=0;$i<$count;$i++)
	{
	array_push($conv,bindec(stringToBinary($split[$i])));
	}

 return json_encode($conv);
}

if (!isset($_GET["id"]))
{
	header("Content-Type: text/plain");
	echo "Bad request";
	exit();
}

if (!isset($_GET["mpd"]))
{
	header("Content-Type: text/plain");
	echo "Bad request";
	exit();
}

$id = $_GET["id"]; //"67d48a06-73c9-4aff-83ba-24f9b06b2af5"
$mpdUrl = base64_decode($_GET["mpd"]); //"https://spark.akamaized.net/dash/live/2014359/spark/trackside1_spark/master.mpd"

$ch = curl_init();

$postdata = file_get_contents("php://input");

if(base64_encode($postdata) == 'CAQ=')
{
	$split = str_split($postdata);
}
else
{
	$split = str_split($postdata);
}

curl_setopt($ch, CURLOPT_URL, $SPARK_DRM_WIDEVINE_PROXY);
curl_setopt($ch, CURLOPT_USERAGENT, $SPARK_DEFAULT_DEVICE_USERAGENT);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Origin: https://www.sparksport.co.nz',
	'Host: widevine.license.istreamplanet.com',
	'Referer: https://www.sparksport.co.nz/epg/a9240df1-c6c7-4d2a-96a6-9ff28da0fd07',
	'User-Agent: ' . $SPARK_DEFAULT_DEVICE_USERAGENT,
	'X-ISP-TOKEN: ' . GetEntitlementToken($id,$mpdUrl),
	'Origin: https://www.sparksport.co.nz',
	'Accept-Language: es-419,es;q=0.9,en;q=0.8',
	'Accept-Encoding: gzip, deflate, br'
));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$result = curl_exec($ch);
if (curl_errno($ch))
{
    echo 'CURL ERROR: ' . curl_error($ch);
}

curl_close($ch);

file_put_contents('challenge.bin', base64_encode($postdata));
file_put_contents('response.bin', base64_encode($result));

echo $result;