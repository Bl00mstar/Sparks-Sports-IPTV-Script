<?php

require_once("includes/config.php");
require_once("includes/utils.php");

header("Content-Type: text/plain");

if (!isset($_GET["id"]))
{
	echo "Bad request";
	exit();
}

$id = $_GET["id"];

$channelMpd = GetChannelMPD($id);

echo $channelMpd;