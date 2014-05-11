<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

/**
 * A function that takes the parameter $that and adds one or two zeroes to make it a
 * two-character long representation of the string. If the string is longer than 1
 * character, the string itself will be returned.
 */
function pad($that) {
	if (strlen("" . $that) == 0) {
		return "00";
	} else if (strlen("" . $that) == 1) {
		return "0" . $that;
	} else {
		return $that;
	}
}

function verb($type, $name, $towards) {
	$type = strtoupper($type);
	switch ($type) {
	case "WALK" : 
		return "gå";
	}
	return "ta " . $name . " (mot " . $towards . ")";
}

/**
 * Returns a string representation on the format HH:ii of the seconds given as parameter.
 * Ex: 122 seconds will yield 00:02.
 */
function secondsToMinutes($secs) {
	$hours = intval($secs / 3600);
	$secs = $secs % 3600;
	$minutes = intval($secs / 60);
	return pad($hours) . ":" . pad($minutes);
}

/**
 * Sort the response from SL out.
 */
function formatResponse($trips) {
	$tripsFormatted = array();
	if (!array_key_exists(0, $trips)) {
		$temp = array();
		$temp[0] = $trips;
		$trips = $temp;
	}
	foreach ($trips as $trip) {
		$subtrips = $trip["SubTrip"];
		if (!array_key_exists(0, $subtrips)) {
			$temp = array();
			$temp[0] = $subtrips;
			$subtrips = $temp;
		}
		$subtripsFormatted = array();
		foreach ($subtrips as $subtrip) {
			$subtripFormatted = array();
			$subtripFormatted["originText"] = $subtrip["Origin"]["#text"];
			$subtripFormatted["destinationText"] = $subtrip["Destination"]["#text"];
			$subtripFormatted["departureTime"] = date("Y-m-d", strtotime($subtrip["DepartureDate"])) . " " . $subtrip["DepartureTime"]["#text"] . ":00";
			$subtripFormatted["arrivalTime"] = date("Y-m-d", strtotime($subtrip["ArrivalDate"])) . " " . $subtrip["ArrivalTime"]["#text"] . ":00";
			$subtripFormatted["type"] = @$subtrip["Transport"]["Type"]; // MET
			$subtripFormatted["transport"] = @$subtrip["Transport"]["Name"]; // tunnelbanans röda linje 13
			$subtripFormatted["line"] = @$subtrip["Transport"]["Line"]; // 13
			$subtripFormatted["towards"] = @$subtrip["Transport"]["Towards"]; // 13
			$remarks = array();
			if (isset($subtrip["Remarks"])) {
				foreach ($subtrip["Remarks"] as $remark) {
					$remarks[] = $remark;
				}
			}
			$subtripFormatted["remarks"] = $remarks;
			
			$subtripsFormatted[] = $subtripFormatted;
		}
		$tripFormatted["originText"] = $trip["Summary"]["Origin"]["#text"];
		$tripFormatted["destinationText"] = $trip["Summary"]["Destination"]["#text"];
		$tripFormatted["departureTime"] = date("Y-m-d", strtotime($trip["Summary"]["DepartureDate"])) . " " . $trip["Summary"]["DepartureTime"]["#text"] . ":00";
		$tripFormatted["arrivalTime"] = date("Y-m-d", strtotime($trip["Summary"]["ArrivalDate"])) . " " . $trip["Summary"]["ArrivalTime"]["#text"] . ":00";
		$tripFormatted["duration"] = $trip["Summary"]["Duration"];
		$tripFormatted["cost"] = 12.5 + 12.5 * strlen($trip["Summary"]["PriceInfo"]["TariffZones"]);
		if (@!array_key_exists("TariffZones", $trip["Summary"]["PriceInfo"])) {
			$tripFormatted["cost"] = 100;
		}
		$tripFormatted["co2"] = $trip["Summary"]["CO2"];
		$tripFormatted["subtrips"] = $subtripsFormatted;
		
		$tripsFormatted[] = $tripFormatted;
	}
	return $tripsFormatted;
}

function addResponseWalk($from, $to, $departure, $arrival) {
	$tripsFormatted = array();
	$subtripsFormatted = array();
	
	$subtripFormatted = array();
	$subtripFormatted["originText"] = $from;
	$subtripFormatted["destinationText"] = $to;
	$subtripFormatted["departureTime"] = $departure;
	$subtripFormatted["arrivalTime"] = $arrival;
	$subtripFormatted["type"] = "Walk";
	$subtripFormatted["transport"] = "Walk";
	$subtripFormatted["line"] = "Walk"; 
	$subtripFormatted["towards"] = ""; 
	$subtripFormatted["remarks"] = array();
	
	$subtripsFormatted[] = $subtripFormatted;
		
	$tripFormatted["originText"] = $from;
	$tripFormatted["destinationText"] = $to;
	$tripFormatted["departureTime"] = $departure;
	$tripFormatted["arrivalTime"] = $arrival;
	$tripFormatted["duration"] = secondsToMinutes(strtotime($arrival) - strtotime($departure));
	$tripFormatted["cost"] = 0;
	$tripFormatted["co2"] = 0;
	$tripFormatted["subtrips"] = $subtripsFormatted;
	
	$tripsFormatted[] = $tripFormatted;

	return $tripsFormatted;
}

/**
 * Returns the given string but without the parentheses.
 */
function unbracket($input) {
	return trim(preg_replace("/\(.*?\)/", "", $input));
}

/**
 * Returns an array. If $val is on type NNNN (integer in string), then the first and only
 * value in the array will be NNNN. If $val is on form GPS:LATITUDE:LONGITUDE, where lat
 * and long are float values, the array will contain two indexes "latitude" and "longitude",
 * with the correct values * 1 000 000.
 */
function stationOrCoordinate($val) {
	$from = explode(":", $val);
	$ret = array();
	if (count($from) == 1 && intval($from[0]) > 0) {
		$ret[0] = intval($from[0]);
	} else if (count($from) == 3 && $from[0] == "GPS") {
		if (floatval($from[1]) != 0) {
			$ret["latitude"] = round(floatval($from[1]) * 1000000);
		}
		if (floatval($from[2]) != 0) {
			$ret["longitude"] = round(floatval($from[2]) * 1000000);
		}
	}
	
	return $ret;
}

function googleMapsPlace($place, $name = "") {
	if (!is_array($place))
		return "";
		
	$fromGMaps = "";
	switch (count($place)) {
	case 1:
		$fromGMaps = $name;
		break;
	case 2:
		$fromGMaps = $place["latitude"]/1000000 . "," . ($place["longitude"]/1000000);
		break;
	default:
		$error[] = 12;
		break;
	}
	return $fromGMaps;
}

Class TripInfo {
	var $co2, $seconds, $meters, $price;
	public function __construct($co2 = NULL, $seconds = NULL, $meters = NULL, $price = NULL) {
		$this->co2 = $co2;
		$this->meters = $meters;
		$this->seconds = $seconds;
		$this->price = $price;
	}
	
	public function getMinutes() {
		return secondsToMinutes($this->seconds);
	}
	
	public function initWithSLTrip($trip) {
		$this->co2 = @$trip["co2"];
		$this->meters = NULL;
		$this->seconds = @strtotime($trip["arrivalTime"]) - @strtotime($trip["departureTime"]);
		$this->price = $trip["cost"];
	}
	
	public function initWithCar($from, $fromName, $to, $toName, $emissionsPerKilometer = NULL) {
		global $fuel, $fuelType, $fuelConsumption, $price;
		
		$from = googleMapsPlace($from, $fromName);
		$to = googleMapsPlace($to, $toName);
		
		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?";
		$url .= "origins=" . urlencode($from) . "&destinations=" . urlencode($to);
		$url .= "&mode=driving&units=metric&sensor=false&key=AIzaSyCtFsQoEenf_m97o_z7hwNuh9VfI3IgTgk";
		$contents = @file_get_contents($url);
		$response = json_decode($contents);
		$this->meters = $response->rows[0]->elements[0]->distance->value;
		$this->seconds = $response->rows[0]->elements[0]->duration->value + 300; // 300 for 5 min parking time
		if ($emissionsPerKilometer == NULL) 
			$this->co2 = ceil($fuel[$fuelType] * $fuelConsumption * $this->meters / 10000 * 10) / 10;
		else
			$this->co2 = ceil($emissionsPerKilometer / 1000 * $this->meters / 1000 * 10) / 10;
		$this->price = $price[$fuelType] * $this->meters * ($fuelConsumption / 10000); //kr/l * m * l/mil /10000
		$this->price += 20; // Parkering
	}
	
	public function initWithWalk($from, $fromName, $to, $toName) {
		global $fuel, $fuelType, $fuelConsumption;
		
		$from = googleMapsPlace($from, $fromName);
		$to = googleMapsPlace($to, $toName);
		
		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?";
		$url .= "origins=" . urlencode($from) . "&destinations=" . urlencode($to);
		$url .= "&mode=walking&units=metric&sensor=false&key=AIzaSyCtFsQoEenf_m97o_z7hwNuh9VfI3IgTgk";
		$contents = @file_get_contents($url);
		$response = json_decode($contents);
		$this->meters = $response->rows[0]->elements[0]->distance->value;
		$this->seconds = $response->rows[0]->elements[0]->duration->value; // 300 for 5 min parking time
		$this->co2 = 0;
	}
}