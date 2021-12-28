<?php

/*
================================
LICENSE SERVER

Script configuration
================================
*/

$SPARK_ACCOUNT_USERNAME = "bryan@simpsonsinnott.co.nz";
$SPARK_ACCOUNT_PASSWORD = "thomas1512";
//For get the Account Device ID run this command in python (future improvement: do this auto)
// deviceid = str(uuid.uuid3(uuid.UUID("124f1611-0232-4336-be43-e054c8ecd0d5"), str("roger@hockeyinfo.org.nz")))
// print(deviceid)
// copy the result and insert in the $SPARK_ACCOUNT_DEVICE_ID variable
$SPARK_ACCOUNT_DEVICE_ID = "c4a5f4fc-d582-398c-9f4b-be67b4faee39";

$SPARK_DEFAULT_TOKEN = "eyJhbGciOiJFUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiYXBpIiwidWlkIjoiIiwiYW5vbiI6ZmFsc2UsInBlcm1pc3Npb25zIjpudWxsLCJhcGlLZXkiOiIzODgyZGQ0My1lZGMwLTQ1YzktYTk5My05YWU0MzRhMWJlNjAiLCJvcmlnaW5hbFRlbmFudCI6IiIsImV4cCI6MzExNTY0NTQ0MiwiaWF0IjoxNTM4ODQ1NDQyLCJpc3MiOiJPcmJpcy1PQU0tVjEiLCJzdWIiOiIzODgyZGQ0My1lZGMwLTQ1YzktYTk5My05YWU0MzRhMWJlNjAifQ.E5Kos46Qp6YPh5-t6cqLf854i2IAEQEZ_MNDQDBcEKzQpGXY3RGAjG1-pe9qeQZOaqHq8OoyVIXiHyYg0tGllw";
$SPARK_DEFAULT_USERAGENT = "Spark Sport/0.1.1-0 (Linux;Android 8.1.0) ExoPlayerLib/2.9.2";
$SPARK_DEFAULT_DEVICE_USERAGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36";
$SPARK_USER_TOKEN_API = "https://platform.prod.dtc.istreamplanet.net/oam/v1/user/tokens";
$SPARK_STOP_STREAM_API = "https://platform.prod.dtc.istreamplanet.net/oxm/v1/streams/";
$SPARK_CHANNELS_API = "https://platform.prod.dtc.istreamplanet.net/ocm/v2/epg/stations";
$SPARK_MPD_API = "https://platform.prod.dtc.istreamplanet.net/ocm/v2/assets/";
$SPARK_ENTITLEMENT_TOKEN = "https://platform.prod.dtc.istreamplanet.net/oem/v2/entitlement?tokentype=isp-atlas";

$SPARK_DRM_WIDEVINE_PROXY = "https://widevine.license.istreamplanet.com/widevine/api/license/0f6160eb-bbd3-4c70-8e4d-0d485e7cb055";
