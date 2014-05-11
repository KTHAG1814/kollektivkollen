<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

header("Content-Type: text/html; charset=utf-8");
session_start();

define("TITLE", "Kollektivkollen");
define("CHARSET", "utf-8");
define("HTML_ROOT", ".");

date_default_timezone_set("Europe/Stockholm");

if (isset($_GET['logout'])) {
	unset($_SESSION['user_id']);
	header("location:index.php");
}

$fuel = array(
	"BENSIN" => 2.71,
	"ETANOL" => 0.63,
	"DIESEL" => 2.97,
	"EL" => 0.083,
	"FORDONSGAS" => 1.47,
	"NATURGAS" => 2.43,
	"BIOGAS" => 0.80,
	"HVO" => 0.44,
);

// co2 utsläpp g/km i stadstrafik
$cars = array(
	"SMALL" => 158, //Volkswagen Polo 1.2 TSI 
	"MEDIUM" => 190, // Audi A4 
	"BIG" => 230,	// Volvo v70
	"JEEP" => 270, // BMW X5
);

// Bränslepris
$price = array(
	"BENSIN" => 14.0,
	"DIESEL" => 14.0,
	"ETANOL" => 9.0,
	"FORDONSGAS" => 17.0, 
	"BIOGAS" => 14.0,
	"EL" => 0,
	"HVO" => 0,
	"NATURGAS" => 0,

);

$db = new PDO("mysql:host=localhost;dbname=kollektivkollen", 'kollektivkollen', 'hemligt');