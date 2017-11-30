<?php
include("restapi.php");
$prango_url = "http://ioant.simuino.cm:8585";
$rest_url = "http://ioant.simuino.com:1881/v0.1/";

$place = $_GET['place'];
$mode = $_GET['mode'];

//$elpow_kil = getLatestValue($prango_url,$rest_url,"kil","kvv32","esp2",3,0);
//$elpow_kil = number_format($elpow_kil, 2, '.', '');
//$elpow_astenas = getLatestValue($prango_url,$rest_url,"astenas","nytomta","nixie2",3,0);
//$elpow_astenas = number_format($elpow_astenas, 2, '.', '');
//$temp_kil1 = getLatestValue($prango_url,$rest_url,"kil","kvv32","esp4",0,2);
//$temp_kil2 = getLatestValue($prango_url,$rest_url,"kil","kvv32","esp4",0,1);

//$result = array('kil' => $elpow_kil, 'astenas' => $elpow_astenas, 'temp1' => $temp_kil1, 'temp2' => $temp_kil2);
//echo json_encode($result);

//$mres = array();
$mres = getPlaceAllStreams($prango_url,$rest_url,$place,$mode);
//print_r($mres);
echo json_encode($mres);
?>
