<?php 

header('Content-Type: text/html; charset=utf-8');
require_once("includes/init.php");
require_once("includes/functions.php");
$key = "hXMe4eCn27b6toY7NGQRL9Gy2G3t9AdK";

// Utsläpp g/km i stadstrafik
$car = $cars["MEDIUM"];
if (array_key_exists($_POST['car'], $cars)) {
	$car = $cars[$_POST['car']];
}
if (isset($_POST['go']) && $_POST['go'] == "go-earliest") {
	$date_original = strtotime($_POST["date"] . " " . $_POST["time"] . ":00");
	$date = date("d.m.Y", $date_original);
	$time = date("H:i", $date_original);
	$timesel = "depart";
} else if (isset($_POST['go']) && $_POST['go'] == "go-latest") {
	$date_original = strtotime($_POST["date"] . " " . $_POST["time"] . ":00");
	$date = date("d.m.Y", $date_original);
	$time = date("H:i", $date_original);
	$timesel = "arrive";
} else {
	$date = date("d.m.Y");
	$time = date("H:i");
	$timesel = "depart";
}

$fromField = stationOrCoordinate($_POST['from-val']);
$urlFrom = "";
switch (count($fromField)) {
case 1:
	$urlFrom = "S=" . $fromField[0];
	break;
case 2:
	$urlFrom = "SID=@Y=" . $fromField["latitude"] . "@X=" . $fromField["longitude"] . "@O=" . "din%20nuvarande%20plats";
	break;
default:
	$error[] = 1;
	break;
}

$toField = stationOrCoordinate($_POST['to-val']);
$urlTo = "";
switch (count($toField)) {
case 1:
	$urlTo = "Z=" . $toField[0];
	break;
default:
	$error[] = 1;
	break;
}

// Getfueltype from user
$fuelType = @$_POST['fuel'];
if (empty($fuelType)) {
	$fuelType = "BENSIN";
}
if (empty($_POST['fuelConsumption']) || $_POST['fuelConsumption'] == 0) {
	$fuelConsumption = 0.7;
} else {
	$fuelConsumption = floatval(preg_replace("/,/", ".", $_POST['fuelConsumption']));
}

// Car
$carInfo = new TripInfo();
if (isset($_POST['fuel']) && array_key_exists($_POST['fuel'], $fuel)) {
	$carInfo->initWithCar($fromField, $_POST['from'], $toField, $_POST['to']);
} else {
	$carInfo->initWithCar($fromField, $_POST['from'], $toField, $_POST['to'], $car);
}

// Walk
$walkInfo = new TripInfo();
$walkInfo->initWithWalk($fromField, $_POST['from'], $toField, $_POST['to']);

// Bike - Google Maps har ännu inte stöd för detta i Sverige
$bikeInfo = new TripInfo();
$bikeInfo->initWithWalk($fromField, $_POST['from'], $toField, $_POST['to']);
$bikeInfo->seconds *= 0.25;// Speed: 16-20km/h cykel 4km/h gång

$url = "https://api.trafiklab.se/sl/reseplanerare.JSON?" .
	   "key=" . $key . "&" .
	   $urlFrom . "&" .
	   $urlTo . "&" .
	   "Date=" . $date . "&" .
	   "Time=" . $time . "&" .
	   "Timesel=". $timesel;
if (!function_exists('curl_init')){ 
    die('CURL is not installed!');
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$tripsFormatted = array();
$trips = array();
$response = json_decode(utf8_encode($output), true);
if ($httpCode == 200 && !isset($response["HafasResponse"]["Error"])) {
	$trips = $response["HafasResponse"]["Trip"];
	$trips = formatResponse($trips);
} else {
	$error[] = 5;
	$trips = addResponseWalk($_POST['from'], $_POST['to'], date("Y-m-d H:i:s"), date("Y-m-d H:i:s", strtotime("+" . $walkInfo->seconds . " seconds"))); // TODO Tiderna
}

// SL
$slInfo = new TripInfo();
$slInfo->initWithSLTrip($trips[0]);
// Calculate trees for the same trip with car.
$denominator = ((15 * $carInfo->seconds/60) / 1314000); // d = t * 15 * 1/60 * 1/1314000
if ($denominator != 0)
	$tree = $carInfo->co2 / $denominator;  // antal = c * 5 256 000 / t
else 
	$tree = 0;
$tree = floor($tree);