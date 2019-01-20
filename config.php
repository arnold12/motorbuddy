<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$timezone = "Asia/Kolkata";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);

require_once 'db.class.php';
require_once 'funcs.general.php';

session_start();

define("SITE_TITLE",'Motorbuddy');

if( $_SERVER['HTTP_HOST'] == "dev.motorbuddy.com" ){
	define("ROOT_DIR",'/dev.motorbuddy.com');

	define("SITE_URL",'http://dev.motorbuddy.com');

	define("DB_HOST",'localhost');

	define("DB_USER",'root');

	define("DB_PASSWD",'root');

	define("DB_NAME",'db_motorbuddy');

	define("USE_PCONNECT",'false');

} else {
	define("ROOT_DIR",'/motorbuddy');

	define("SITE_URL",'http://www.vasaibirds.com/motorbuddy');

	define("DB_HOST",'localhost');

	define("DB_USER",'dbarnold1');

	define("DB_PASSWD",'P@ssw0rd');

	define("DB_NAME",'db_vvbirds1');

	define("USE_PCONNECT",'false');
}

define("X_App_Key",'95d7e28234ce4318ac6a732a38bf659f1f431e865ed7c789d35854b9b2468321');

define("GUEST_TOKEN",'95d7e28234ce4318ac6a732a38bf659f1f431e865ed7c789d35854b9b246873b');

define("ACCESS_TOKEN_EXPIRY_LIMIT",'4');

$payment_method_bitwise = array('Cash'=>1, 'Cheque'=>2, 'Credit Card'=>4, 'Debit Card'=>8);

$shopes_hours_arry = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');

$insurance_arry = array('ICICI Lombard','L&T Insurance','HDFC Insurance','Kotak Insurance','New India Insurance');

$shopes_services_arry = array('Brake','Drivetrain','Engine','Electrical','Air conditioning','Periodic Maintenance','PUC','Steering and Suspension','Wheel Alignment and Balancing','Quick service','Extended Warranty','AMC\'s','Accessories','Battery','Tyre','Washing','Car Detailing','Motor Insurance','24 hr Breakdown service','Body Shop','Accidental repairs');

$shopes_amenities_arry = array('Owner Lounge','Wi-Fi','Courtesy Shuttle','Rental / Loaner Car','Towing Services','Loyalty Program','Pick up and drop','Coffee / Tea','Driver lounge','Waiting Room','Walk In Welcome','Discounts available','Early drop off ( key drop / mail box)');

$brand_arry = array('Maruti','Mahindra','Tata','Hyundai','Volkwagen','Skoda','Nissan','Renault','Fiat','Chevrolet','Ford','Ashok Leyland','Honda','Toyota','Isuzu','Mitsubishi','Volvo','BMW','Mercedes Benz','Audi','Jaguar','Land Rover','Mini','Rolls Royce');

$pkg_type_arry = array(1=>'BASIC', 2=>'STANDARD', 3=>'COMPREHENSIVE');

?>
