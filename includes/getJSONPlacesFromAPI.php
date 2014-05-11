<?php

$text = "";
header("Content-type:application/json");

if (!empty($_GET['term']) && is_string($_GET['term']) && strlen($_GET['term']) > 0) {
	$text = $_GET['term'];
} else {
	exit("Ett fel uppstod.");
}

$url = "https://api.trafiklab.se/sl/realtid/GetSite.json?stationSearch=" . 
	   $text . 
	   "&key=hXMe4eCn27b6toY7NGQRL9Gy2G3t9AdK";
$contents = @file_get_contents($url);
$c = json_decode($contents);
$a = $c->Hafas->Sites->Site;
if (is_object($a)) {
	$d[] = array(
		"id" => $a->Number, 
		"value" => $a->Name, 
		"label" => $a->Name,
	);
} else {
	foreach($a as $val) {
		$d[] = array(
			"id" => $val->Number, 
			"value" => $val->Name, 
			"label" => $val->Name,
		);
	}
}
echo json_encode($d);