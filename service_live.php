<?php
/**
 * 
 * @This script provide the service for android application 'motorbuddy'
 * developed by Arnold Machado and Suraj and projected by Seraj Lopese
 * 
 **/ 

/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
Mod#            Date            Who             Description
-------------------------------------------------------------------------------
0000000001      01-Sep-2018     Arnold M			Created
*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once 'config.php';
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");


/**
 * @here get all the json encoded inputs parameter.
 **/ 
$body = file_get_contents("php://input");
$body_params = json_decode($body,true);


/**
 * @here get all the request headers.
 **/ 
$headers = getallheaders();

if($headers['X-App-Key'] != X_App_Key ){
	header('Content-type: application/json; charset=utf-8');
	$result = defaultAction("Invalid X-App-Key");
	echo json_encode($result);
	exit;	
}

switch ($body_params['action']) {
	case 'dealerlist':
        $result = sendDealerList();
        break;

    case 'dealerdtls':
        $result = sendDealerDtls();
        break;

    case 'getbrandmodellst':
        $result = sendBrandModelLst();
        break;

    case 'userreg':
        $result = addNewUser();
        break;

    case 'login':
        $result = login();
        break;

    case 'otpverification':
        $result = otpVerification();
        break;

    case 'feedback':
        $result = saveFeedback();
		break;

	case 'resendtotp':
        $result = resendOTP();
		break;

	case 'contactus':
        $result = contactUs();
		break;

	case 'homepageimg':
        $result = homepageimg();
		break;

	case 'addbooking':
        $result = addbooking();
		break;

	case 'resendtBookingotp':
        $result = resendtBookingotp();
		break;

	case 'otpVerificationBooking':
        $result = otpVerificationBooking();
		break;

	case 'sendBookingServiceRepair':
        $result = sendBookingServiceRepair();
		break;

	case 'bookingList':
        $result = bookingList();
		break;

	case 'sendBookingPkg':
        $result = sendBookingPkg();
		break;
		
	case 'sendRecommedationPDF':
        $result = sendRecommedationPDF();
		break;

	case 'trkUserCallAction':
        $result = trkUserCallAction();
		break;

	case 'dealerUserReview':
        $result = dealerUserReview();
		break;

    default:
        $result = defaultAction("Invalid Action");
        break;

}

/**
 * @return the json encoded response to requsted client
 **/ 

header('Content-type: application/json; charset=utf-8');
echo json_encode($result);
exit;


/**
 * 
 * @return delaer list
 * 
 **/

function sendDealerList(){
	GLOBAl $DBI, $body_params;

	$lat = mysql_real_escape_string(trim($body_params['lat']));
	$long = mysql_real_escape_string(trim($body_params['long']));
	$brand = mysql_real_escape_string(trim($body_params['brand']));

	$select_dealer_id = "SELECT `dealer_id` FROM tbl_mb_delaer_brand_service WHERE `brand_name` = '".$brand."' ";
	$select_dealer_id_res = $DBI->query($select_dealer_id);
	$res_dealer_id_row = $DBI->get_result($select_dealer_id);

	$is_empty = $DBI->is_empty($select_dealer_id);

	if( $is_empty ){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found for selected brand";
		$final_result['result'] = $response;

		return $final_result;
	}


	$dealer_id_arr = array();
	foreach ($res_dealer_id_row as $key => $value) {
		$dealer_id_arr[] = $value['dealer_id'];
	}

	$dealer_id_str = implode(',', $dealer_id_arr);
	
	$select = "SELECT `id`, `dealer_name`, `dealer_name2`, `address`, `landmark`, `city`, `state`, `pincode`, `mobile_no`, `telephone_no`, `establishment_year`, `gstn`, `dealer_rating`, `img_1`, round(111.045 * DEGREES(ACOS(COS(RADIANS($lat)) * COS(RADIANS(`lat`)) * COS(RADIANS(`long`) - RADIANS($long)) + SIN(RADIANS($lat)) * SIN(RADIANS(`lat`))))) AS distance_in_km FROM tbl_mb_delaer_master WHERE status = 'Active' AND `id` IN (".$dealer_id_str.") ORDER BY distance_in_km ASC LIMIT 0,100";
	
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	$is_empty = $DBI->is_empty($select);
		
	if($is_empty){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found";
		$final_result['result'] = $response;
	} else {
		$final_result['success'] = true;
		$final_result['message'] = "Success";
		$final_result['result'] = $res_row;
	}
	
	return $final_result;
}

/**
 * 
 * @return all delaer details
 * 
 **/

function sendDealerDtls(){	
	GLOBAl $DBI, $body_params,$payment_method_bitwise;

	$id = mysql_real_escape_string(trim($body_params['id']));
	$lat = mysql_real_escape_string(trim($body_params['lat']));
	$long = mysql_real_escape_string(trim($body_params['long']));
	
	/* select basic info */
	$select_dealer_master = "SELECT  `id` ,  `dealer_name` , `dealer_name2` , `address` ,  `landmark` ,  `city` ,  `state` ,  `pincode` ,  `mobile_no` ,  `telephone_no` ,  `establishment_year` ,  `website` ,  `about_dealer` ,  `payment_mode` ,  `lat` ,  `long` ,  `gstn`, `dealer_rating` ,  `img_1` ,  `img_2` ,  `img_3`, round(111.045 * DEGREES(ACOS(COS(RADIANS($lat)) * COS(RADIANS(`lat`)) * COS(RADIANS(`long`) - RADIANS($long)) + SIN(RADIANS($lat)) * SIN(RADIANS(`lat`))))) AS distance_in_km FROM  `tbl_mb_delaer_master` WHERE  `status` =  'Active' AND `id` = $id ";
	
	$select_res_dealer_master 	= $DBI->query($select_dealer_master);
	$res_row_dealer_master 		= $DBI->get_result($select_dealer_master);
	$is_empty_dealer_master		= $DBI->is_empty($select_dealer_master);

	if($is_empty_dealer_master){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found";
		$final_result['result'] = $response;
	} else {

		$payment_method_str = "";
		if($res_row_dealer_master[0]['payment_mode'] != 0 ){
			foreach ($payment_method_bitwise as $payment_method => $value) {
					
				if($res_row_dealer_master[0]['payment_mode'] & $value){
					$payment_method_str .= $payment_method.", ";
				}

			}

			$payment_method_str = rtrim($payment_method_str,', ');
		}

		$res_row_dealer_master[0]['payment_mode'] = $payment_method_str;

		$response['basic_info'] = $res_row_dealer_master;

		/* select timing info */
		$select_shop_timing 	= "SELECT  `id` ,  `day` ,  `is_open` ,  `open_at` ,  `close_at` FROM  `tbl_mb_delaer_shop_timing` WHERE  `dealer_id` = $id ";
		$select_res_dealer_timing 	= $DBI->query($select_shop_timing);
		$res_row_dealer_timing 		= $DBI->get_result($select_shop_timing);
		
		$open_days = "";
		$timing = "";
		$close_days = "";

		foreach ($res_row_dealer_timing as $key => $value) {
			if( $value['is_open'] == 'Y' ){
				$open_days .= $value['day'].",";
				$timing = $value['open_at']." To ".$value['close_at'];
			} else if( $value['is_open'] == 'N' ){
				$close_days .= $value['day'].",";
			}
		}

		$res_row_dealer_timing1[0]['open_days'] 	= rtrim($open_days, ',');
		$res_row_dealer_timing1[0]['timing'] 		= $timing;
		$res_row_dealer_timing1[0]['close_days'] 	= rtrim($close_days,',');

		$response['timing_info'] 	= $res_row_dealer_timing1;

		/* select shop services info */
		$select_shop_services 	= "SELECT ssm.id, ssm.shop_service FROM tbl_mb_delaer_shop_service AS ss LEFT JOIN tbl_mb_shop_service_master AS ssm ON ss.shop_service_name = ssm.id WHERE ss.dealer_id = $id ";
		$select_res_dealer_shop_services 	= $DBI->query($select_shop_services);
		$res_row_dealer_shop_services 		= $DBI->get_result($select_shop_services);
		$response['shop_services_info'] 	= $res_row_dealer_shop_services;


		/* select insurance tie up info */
		$select_insurance_tie_up 	= "SELECT icm.id, icm.insurance_company FROM tbl_mb_delaer_insurance_tie_ups AS ic LEFT JOIN tbl_mb_insurance_company_master AS icm ON ic.insurance_company = icm.id WHERE ic.dealer_id = $id ";
		$select_res_dealer_insurance 		= $DBI->query($select_insurance_tie_up);
		$res_row_dealer_insurance 			= $DBI->get_result($select_insurance_tie_up);
		$response['insurance_tie_up_info'] 	= $res_row_dealer_insurance;


		/* select shop amenties info */
		$select_amenities 	= "SELECT icm.id, icm.shop_amenities FROM tbl_mb_delaer_amenities AS ic LEFT JOIN tbl_mb_shop_amenities_master AS icm 
		ON ic.amenities = icm.id WHERE ic.dealer_id = $id ";
		$select_res_dealer_amenities 		= $DBI->query($select_amenities);
		$res_row_dealer_amenities 			= $DBI->get_result($select_amenities);
		$response['amenities_info'] 		= $res_row_dealer_amenities;


		/* select brand services info */
		$select_brand_services 	= "SELECT icm.id, icm.brand_model_name FROM tbl_mb_delaer_brand_service AS ic LEFT JOIN tbl_mb_brand_model_master AS icm ON ic.brand_name = icm.id WHERE ic.dealer_id = $id ";
		$select_res_dealer_brand_services 		= $DBI->query($select_brand_services);
		$res_row_dealer_brand_services 			= $DBI->get_result($select_brand_services);
		$response['brand_services_info'] 		= $res_row_dealer_brand_services;


		$final_result['success'] = true;
		$final_result['message'] = "Success";
		$final_result['result'] = $response;
	}
	
	return $final_result;	
}

/**
 * 
 * @return all brand or model list
 * 
 **/

function sendBrandModelLst(){
	GLOBAl $DBI, $body_params;

	$brand_id = mysql_real_escape_string(trim($body_params['brand_id']));
	
	$select = "SELECT `id`, `brand_model_name` FROM tbl_mb_brand_model_master WHERE is_active = 'Y' AND brand_id = '".$brand_id."' ORDER BY brand_model_name ASC";
	
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	$is_empty = $DBI->is_empty($select);
		
	if($is_empty){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found";
		$final_result['result'] = $response;
	} else {
		$final_result['success'] = true;
		$final_result['message'] = "Success";
		$final_result['result'] = $res_row;
	}
	
	return $final_result;
}


/**
 * 
 * @add new user
 * 
 **/

function addNewUser(){
	GLOBAl $DBI , $body_params;

	$email 		=		mysql_real_escape_string(trim($body_params['email']));
	$password	= 		mysql_real_escape_string(trim($body_params['password']));
	$address	= 		mysql_real_escape_string(trim($body_params['address']));
	$pincode	= 		mysql_real_escape_string(trim($body_params['pincode']));
	$gender		= 		mysql_real_escape_string(trim($body_params['gender']));
	$fname		= 		mysql_real_escape_string(trim($body_params['fname']));
	$lname		= 		mysql_real_escape_string(trim($body_params['lname']));
	$mobile		= 		mysql_real_escape_string(trim($body_params['mobile']));
	$chkd_terms_and_condn	= 		'Y';
 
	/* input data validation */
	$error = array();

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

		$error[] = "Invalid email format"; 

	}

	if ( strlen($password) < 4 ) {

		$error[] = "password should be atleast 4 characters "; 

	}

	if ( $address == "" ) {

		$error[] = "invalid address"; 

	}

	if ( strlen($pincode) != 6 ) {

		$error[] = "invalid pin code"; 

	}

	if ( $gender == "" ) {

		$error[] = "invalid gender"; 

	}

	if ( $fname == "" ) {

		$error[] = "invalid first name"; 

	}

	if ( $lname == "" ) {

		$error[] = "invalid last name"; 

	}

	$mobileregex = "/^[6-9][0-9]{9}$/" ; 

	if ( $mobile == "" || preg_match($mobileregex, $mobile) !== 1) {

		$error[] = "invalid mobile no"; 

	}

	/* check email id and mobile no already exist or not */

	$select = "SELECT email, mobile FROM tbl_mb_register_users WHERE email = '".$email."' OR mobile = '".$mobile."' ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	$is_empty = $DBI->is_empty($select);
	
	if( ! $is_empty ){
		if( $res_row[0]['email'] == $email ){
			$error[] = "email id already exist"; 
		}
		if( $res_row[0]['mobile'] == $mobile ){
			$error[] = "mobile no already exist"; 
		}
	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}


	/*generate otp */

	$otp = otp();

	/* insert user */

	$table = "tbl_mb_register_users";

	$insert['email']			=		$email;  
	$insert['password']			=		$password;  
	$insert['address']			=		$address;  
	$insert['pin']				=		$pincode;  
	$insert['gender']			=		$gender;  
	$insert['last_login_date']	=		CURRENT_DATE_TIME;  
	$insert['created_date']		=		CURRENT_DATE_TIME;  
	$insert['updated_date']		=		CURRENT_DATE_TIME;  
	$insert['status']			=		'Inactive';  
	$insert['otp']				=		$otp;  
	$insert['otp_sent_count']	=		'1';  
	$insert['otp_sent_date']	=		CURRENT_DATE_TIME;
	$insert['fname']			=		$fname;
	$insert['lname']			=		$lname;
	$insert['mobile']			=		$mobile;
	$insert['chkd_terms_and_condn']			=		$chkd_terms_and_condn;


	$res = $DBI->insert_query($insert, $table);

	if( $res ){

		$param['email'] 	= $email;
		$param['subject'] 	= "Motorbuddy Registration OTP";
		$param['message'] 	= "Your Mottorbuddy Registration OTP is ".$otp;
		$param['mobile'] 	= $mobile;
		//sendOtp($param);
		sendOtpMobile($param);

		$msg = "User registered successfully";
		$success = true;
	} else {
		$msg = "Registration fail!!!";	
		$success = false;
	}

	$response = array();

	$final_result['success'] = $success;
	$final_result['message'] = $msg;
	$final_result['result'] = $response;

	return $final_result;
}

/* resend otp to user */
function resendOTP(){

	GLOBAl $DBI , $body_params;

	$email 		=		mysql_real_escape_string(trim($body_params['email']));

	/* input data validation */
	$error = array();

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

		$error[] = "Invalid email format"; 

	}

	$select = "SELECT email, otp_sent_count, is_otp_verify FROM tbl_mb_register_users WHERE email = '".$email."' ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	$is_empty = $DBI->is_empty($select);
	
	if( $is_empty ){
		$error[] = "Email not present"; 
	}

	if( ! $is_empty ){
		if( $res_row[0]['otp_sent_count'] >= 4 ){
			$error[] = "Allready 3 attempts you performed for resend otp.Contact to mottorbuddy";
		}
		if( $res_row[0]['is_otp_verify'] == 'Y' ){
			$error[] = "OTP Allready verfied for provide email id";
		}		
	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}


	/*generate otp */
	$otp 		= otp();

	$update = "UPDATE tbl_mb_register_users SET otp = '".$otp."', otp_sent_count = (otp_sent_count+1), otp_sent_date = '".CURRENT_DATE_TIME."' WHERE email = '".$email."'";

	$update_res = $DBI->query($update);

	if( $update_res ){

		$param['email'] = $email;
		$param['subject'] = "Motorbuddy Registration OTP";
		$param['message'] = "Your OTP is ".$otp;

		sendOtp($param);

		$msg = "OTP resend successfully";
		$success = true;
	} else {
		$msg = "Resend otp fail!!!";	
		$success = false;
	}

	$response = array();

	$final_result['success'] = $success;
	$final_result['message'] = $msg;
	$final_result['result'] = $response;

	return $final_result;
}

/* send otp to user on email*/
function sendOtp($data){
	$to = $data['email'];
	$subject = $data['subject'];
	$message = $data['message'];
	$headers = 'From: motorbuddy2016@gmail.com' . "\r\n" .
	'Reply-To: motorbuddy2016@gmail.com';

	mail($to, $subject, $message, $headers);
}

/* send otp to user on mobile*/
/*function sendOtpMobile($data){
	
	$country 	= "91";
	$sender 	= "MSGIND";
	$route 		= "4";
	$mobile 	= $data['mobile'];
	$message 	= $data['message'];
	$authkey	= "251171AM91SyD7jQ5c0e1bae";

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?country=".$country."&sender=".$sender."&route=".$route."&mobiles=".$mobile."&authkey=".$authkey."&message=".$message."",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_SSL_VERIFYHOST => 0,
	  CURLOPT_SSL_VERIFYPEER => 0,
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  return "cURL Error #:" . $err;
	} else {
	  return $response;
	}

}*/


/* verify user OTP */
function otpVerification(){

	GLOBAl $DBI , $body_params;

	$email 		=		mysql_real_escape_string(trim($body_params['email']));
	$otp		= 		mysql_real_escape_string(trim($body_params['otp']));

	/* validations */
	$error = array();

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

		$error[] = "Invalid emailid"; 

	}

	if ( strlen($otp) != 4 ) {

		$error[] = "Invalid otp"; 

	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}


	$select = "SELECT otp, otp_sent_date, is_otp_verify, status FROM tbl_mb_register_users WHERE email = '".$email."' ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	$is_empty = $DBI->is_empty($select);
	
	if( $is_empty ){
		$error[] = "emailid not registered"; 
	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}


	if( ! $is_empty ){

		if( $res_row[0]['is_otp_verify'] == 'Y' ){
			$error[] = "otp already verfied"; 		
		}

		if( $res_row[0]['status'] == 'Active' ){
			$error[] = "member already active. Contact to motorbuddy team"; 		
		}

		if( $res_row[0]['otp'] != $otp ){
			$error[] = "invalid otp"; 		
		}

		if( count($error) ){
			$response = array();

			$final_result['success'] = false;
			$final_result['message'] = implode("||", $error);
			$final_result['result'] = $response;

			return $final_result;
		}

		$update = "UPDATE tbl_mb_register_users SET is_otp_verify = 'Y', otp_verification_date = '".CURRENT_DATE_TIME."', status = 'Active', updated_date = '".CURRENT_DATE_TIME."' WHERE email = '".$email."'";

		$update_res = $DBI->query($update);

		$response = array();

		$final_result['success'] = true;
		$final_result['message'] = "otp verified successfully";
		$final_result['result'] = $response;

		return $final_result;
	}
}

/* user login */
function login(){

	GLOBAl $DBI , $body_params;

	$email 		=		mysql_real_escape_string(trim($body_params['email']));
	$password	= 		mysql_real_escape_string(trim($body_params['password']));

	/* validations */
	$error = array();

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

		$error[] = "Invalid emailid"; 

	}

	if ( strlen($password) < 4 ) {

		$error[] = "Invalid password"; 

	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}

	/* check user login */
	$select = "SELECT id, email, fname, lname, mobile, gender, is_otp_verify, status, address, pin FROM tbl_mb_register_users WHERE email = '".$email."' AND password = '".$password."' ";
	$select_res = $DBI->query($select);
	$is_empty = $DBI->is_empty($select);
	$res_row = $DBI->get_result($select);
	
	if( $is_empty ){
		$error[] = "invalid email or password"; 
	}

	if( !$is_empty ){
		if( $res_row[0]['is_otp_verify'] == 'N' ){
			$error[] = "OTP verfication pending"; 		
		}
	}

	if( count($error) ){
		$response = $res_row;

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}

	if( ! $is_empty ){

		$access_token = md5(randomString());

		$access_token_expire_on = date('Y-m-d H:i:s', strtotime('+'.ACCESS_TOKEN_EXPIRY_LIMIT.' months'));

		$update = "UPDATE tbl_mb_register_users SET login_count = login_count + 1, last_login_date = '".CURRENT_DATE_TIME."', access_token = '".$access_token."', access_token_expire_on = '".$access_token_expire_on."', updated_date = '".CURRENT_DATE_TIME."' WHERE email = '".$email."'";

		$update_res = $DBI->query($update);

		$res_row[0]['access_token'] = $access_token;

		$response = $res_row;
		
		$final_result['success'] = true;
		$final_result['message'] = "Loged in successfully";
		$final_result['result'] = $response;

		return $final_result;
	} 

}

/*
** save feedback submitted by user
*/

function saveFeedback(){

	GLOBAl $DBI , $body_params;

	$email 			=		mysql_real_escape_string(trim($body_params['email']));
	$feedback		= 		mysql_real_escape_string(trim($body_params['feedback']));
	$access_token 	= 		mysql_real_escape_string(trim($body_params['access_token']));

	/*
	** input validation
	*/
	$error = array();

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

		$error[] = "Invalid emailid"; 

	}

	$select = "SELECT id, access_token FROM tbl_mb_register_users WHERE email = '".$email."' AND  access_token = '".$access_token."' ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	$is_empty = $DBI->is_empty($select);	

	if ( $access_token == "" || $is_empty ) {

		$error[] = "Invalid access token"; 

	}

	if ( $feedback == ""  ) {

		$error[] = "Feedback should not be empty"; 

	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}

	/*
	** submit user feedback data
	*/

	$table = "tbl_mb_feedback";

	$insert['feedback']			=		$feedback;  
	$insert['userid']			=		$res_row[0]['id'];  
	$insert['created_date']		=		CURRENT_DATE_TIME;  
	
	$res = $DBI->insert_query($insert, $table);

	if( $res ){

		$msg = "feedback submitted successfully";
		$success = true;
	} else {
		$msg = "feedback submission fail!!!";	
		$success = false;
	}

	$response = array();

	$final_result['success'] = $success;
	$final_result['message'] = $msg;
	$final_result['result'] = $response;

	return $final_result;

}

/*
** save contacct us data submitted by user
*/
function contactUs(){

	GLOBAl $DBI , $body_params;

	$email 			=		mysql_real_escape_string(trim($body_params['email']));
	$mobile			=		mysql_real_escape_string(trim($body_params['mobile']));
	$name			=		mysql_real_escape_string(trim($body_params['name']));
	$contact_text	= 		mysql_real_escape_string(trim($body_params['contact_text']));
	$access_token 	= 		mysql_real_escape_string(trim($body_params['access_token']));

	/*
	** input validation
	*/
	$error = array();

	if ( $name == "" ) {

		$error[] = "Invalid Name"; 

	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

		$error[] = "Invalid emailid"; 

	}

	$mobileregex = "/^[6-9][0-9]{9}$/" ; 

	if ( $mobile == "" || preg_match($mobileregex, $mobile) !== 1) {

		$error[] = "invalid mobile no"; 

	}

	if ( $contact_text == ""  ) {

		$error[] = "Contact us should not be empty"; 

	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}

	/*
	** submit user contactus data
	*/

	$table = "tbl_mb_contact_us";

	$insert['contact_text']		=		$contact_text;  
	$insert['mobile']			=		$mobile;  
	$insert['emailid']			=		$email;  
	$insert['name']				=		$name;  
	$insert['access_token']		=		$access_token;  
	$insert['created_date']		=		CURRENT_DATE_TIME;  
	
	$res = $DBI->insert_query($insert, $table);

	if( $res ){

		$msg = "contact us submitted successfully";
		$success = true;
	} else {
		$msg = "contact us submission fail!!!";	
		$success = false;
	}

	$response = array();

	$final_result['success'] = $success;
	$final_result['message'] = $msg;
	$final_result['result'] = $response;

	return $final_result;

}

/**
 * 
 * @return all home page banner images
 * 
 **/

function homepageimg(){
	GLOBAl $DBI;
	
	$select = "SELECT `id`, `description`, `img_url`, `order` FROM tbl_mb_home_page_banner ORDER BY `order` ASC";
	
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	$is_empty = $DBI->is_empty($select);
		
	if($is_empty){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found";
		$final_result['result'] = $response;
	} else {
		$final_result['success'] = true;
		$final_result['message'] = "Success";
		$final_result['result'] = $res_row;
	}
	
	return $final_result;
}

/**
 * 
 * @add new user
 * 
 **/

function addbooking(){
	GLOBAl $DBI , $body_params;

	$dealer_id 				=		mysql_real_escape_string(trim($body_params['dealer_id']));
	$user_id				= 		mysql_real_escape_string(trim($body_params['user_id']));
	$brand_id				= 		mysql_real_escape_string(trim($body_params['brand_id']));
	$model_id				= 		mysql_real_escape_string(trim($body_params['model_id']));
	$fuel_type				= 		mysql_real_escape_string(trim($body_params['fuel_type']));
	$appmt_date				= 		mysql_real_escape_string(trim($body_params['appmt_date']));
	$appmt_time				= 		mysql_real_escape_string(trim($body_params['appmt_time']));
	//$appmt_category_type	= 		mysql_real_escape_string(trim($body_params['appmt_category_type']));
	//$appmt_service_type	= 		mysql_real_escape_string(trim($body_params['appmt_service_type']));
	//$appmt_repair_type	= 		mysql_real_escape_string(trim($body_params['appmt_repair_type']));
	$appmt_service_pkg		= 		mysql_real_escape_string(trim($body_params['appmt_service_pkg']));
	$appmt_repair_concern	= 		$body_params['appmt_repair_concern'];
	$pickup_drop			= 		mysql_real_escape_string(trim($body_params['pickup_drop']));
	$pickup_location		= 		mysql_real_escape_string(trim($body_params['pickup_location']));
	$pickup_pincode			= 		mysql_real_escape_string(trim($body_params['pickup_pincode']));
	$description			= 		mysql_real_escape_string(trim($body_params['description']));
	$access_token 			= 		mysql_real_escape_string(trim($body_params['access_token']));
	$pickup_pincode 		= 		mysql_real_escape_string(trim($body_params['pickup_pincode']));
	$chkd_terms_and_condn	= 		'Y';
 
	/* input data validation */
	$error = array();

	$select_dealer_dtls = "SELECT * FROM tbl_mb_delaer_master WHERE id = ".$dealer_id." AND status = 'Active' ";
	$select_dealer_res = $DBI->query($select_dealer_dtls);
	$dealer_res_row = $DBI->get_result($select_dealer_dtls);
	$is_empty_dealer = $DBI->is_empty($select_dealer_dtls);

	if ($is_empty_dealer) {

		$error[] = "Invalid dealer ID"; 

	} else {
		if( $pickup_drop == 1 ){
			$service_location_arr = explode(',', $dealer_res_row[0]['service_location']);
			if(!in_array($pickup_pincode, $service_location_arr)){
				
				$error[] = "Pickup and drop service not provided for your pincode";
			}
		}
	}

	$select_user_dtls = "SELECT * FROM tbl_mb_register_users WHERE id = ".$user_id."  AND  access_token = '".$access_token."' AND status = 'Active'";
	$select_user_res = $DBI->query($select_user_dtls);
	$user_res_row = $DBI->get_result($select_user_dtls);
	$is_empty_user = $DBI->is_empty($select_user_dtls);

	if ( $is_empty_user ) {

		$error[] = "Invalid User ID"; 

	}

	$select_brand_dtls = "SELECT brand_model_name FROM tbl_mb_brand_model_master WHERE id = ".$brand_id."  AND  is_active = 'Y' ";
	$select_brand_res = $DBI->query($select_brand_dtls);
	$brand_res_row = $DBI->get_result($select_brand_dtls);
	$is_empty_brand = $DBI->is_empty($select_brand_dtls);

	if ( $is_empty_brand ) {

		$error[] = "Invalid Brand ID"; 

	}

	$select_model_dtls = "SELECT brand_model_name FROM tbl_mb_brand_model_master WHERE id = ".$model_id."  AND  is_active = 'Y' ";
	$select_model_res = $DBI->query($select_model_dtls);
	$model_res_row = $DBI->get_result($select_model_dtls);
	$is_empty_model = $DBI->is_empty($select_model_dtls);

	if ( $is_empty_model ) {

		$error[] = "Invalid Model ID"; 

	}

	if ( $fuel_type == "" ) {

		$error[] = "Invalid Fuel Type"; 

	}

	if ( $appmt_date == "" ) {

		$error[] = "Invalid Date"; 

	}

	if ( $appmt_time == "" ) {

		$error[] = "Invalid Time"; 

	}

	/*if ( $appmt_category_type == "" ) {

		$error[] = "Invalid Category Type"; 

	}*/
    
    if( $pickup_drop == 1 ){
    	if ( empty($appmt_service_pkg)) {
    
    		$error[] = "Please select either service package or repair concern."; 
    
    	}
    }

	/*if ( !is_array($appmt_repair_concern) ) {

		$error[] = "Invalid Repair Concern"; 

	}*/

	if ( $pickup_drop == "" ) {

		$error[] = "Select Delivery Option"; 

	}

	if ( $pickup_drop == "1" && $pickup_location == "" ) {

		$error[] = "Invalid Pickup Location"; 

	}

	if ( $description == "" ) {

		$error[] = "Invalid Description"; 

	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}

	/* generate booking code */	
	$appmt_code = generateBookingCode();

	/*generate otp */

	$otp = otp();

	/* insert user */

	$table = "tbl_mb_dealer_appointment";

	$insert['appmt_code']				=		$appmt_code;  
	$insert['dealer_id']				=		$dealer_id;  
	$insert['user_id']					=		$user_id;  
	$insert['brand_id']					=		$brand_id;  
	$insert['model_id']					=		$model_id;  
	$insert['fuel_type']				=		$fuel_type;  
	$insert['appmt_date']				=		$appmt_date;  
	$insert['appmt_time']				=		$appmt_time;  
	/*$insert['appmt_category_type']		=		$appmt_category_type;  
	$insert['appmt_service_type']		=		$appmt_service_type;  
	$insert['appmt_repair_type']		=		$appmt_repair_type;  */
	$insert['appmt_service_pkg']		=		$appmt_service_pkg; 
	$insert['appmt_repair_concern']		=		implode(",", $appmt_repair_concern);
	$insert['pickup_drop']				=		$pickup_drop;
	$insert['pickup_location']			=		$pickup_location;
	$insert['pickup_pincode']			=		$pickup_pincode;
	$insert['description']				=		$description;
	$insert['terms_n_condition']		=		'1';
	$insert['otp']						=		$otp;  
	$insert['otp_sent_count']			=		'1';  
	$insert['otp_sent_date']			=		CURRENT_DATE_TIME;
	$insert['appmt_status']				=		'pending';
	$insert['appmt_status_change_time']	=		CURRENT_DATE_TIME;
	$insert['appmt_booked_by']			=		$user_id;
	$insert['appmt_booking_time']		=		CURRENT_DATE_TIME;
	

	$res = $DBI->insert_query($insert, $table);

	$booking_id = $DBI->get_insert_id();

	if( $res ){
		$param['email'] 	= $user_res_row[0]['email'];
		$param['subject'] 	= "Motorbuddy Appointment Booking OTP";
		$param['message'] 	= "Your Motorbuddy Appointment Booking OTP is ".$otp;
		$param['mobile'] 	= $user_res_row[0]['mobile'];
		//sendOtp($param);
		sendOtpMobile($param);

		$msg = "Verify OTP for booking confirmation.";
		$success = true;
	} else {
		$msg = "Booking fail!!!";	
		$success = false;
	}

	$response = array("booking_id"=>$booking_id);

	$final_result['success'] = $success;
	$final_result['message'] = $msg;
	$final_result['result'] = $response;

	return $final_result;
}

/* resend booking otp to user */
function resendtBookingotp(){

	GLOBAl $DBI , $body_params;

	$booking_id 		=		mysql_real_escape_string(trim($body_params['booking_id']));
	$user_id 			=		mysql_real_escape_string(trim($body_params['user_id']));
	$access_token 		=		mysql_real_escape_string(trim($body_params['access_token']));

	/* input data validation */
	$error = array();

	$select_user_dtls = "SELECT * FROM tbl_mb_register_users WHERE id = ".$user_id."  AND  access_token = '".$access_token."' AND status = 'Active'";
	$select_user_res = $DBI->query($select_user_dtls);
	$user_res_row = $DBI->get_result($select_user_dtls);
	$is_empty_user = $DBI->is_empty($select_user_dtls);

	if ( $is_empty_user ) {

		$error[] = "Invalid User ID"; 

	}

	$select = "SELECT otp_sent_count, is_otp_verify FROM tbl_mb_dealer_appointment WHERE id = '".$booking_id."' ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);

	$is_empty = $DBI->is_empty($select);
	
	if( $is_empty ){
		$error[] = "Invalid Booking ID"; 
	}

	if( ! $is_empty ){
		if( $res_row[0]['otp_sent_count'] >= 4 ){
			$error[] = "Allready 3 attempts you performed for resend boking otp.Contact to mottorbuddy";
		}
		if( $res_row[0]['is_otp_verify'] == 'Y' ){
			$error[] = "OTP Allready verfied for your booking";
		}		
	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}


	/*generate otp */
	$otp 		= otp();

	$update = "UPDATE tbl_mb_dealer_appointment SET otp = '".$otp."', otp_sent_count = (otp_sent_count+1), otp_sent_date = '".CURRENT_DATE_TIME."' WHERE id = '".$booking_id."'";

	$update_res = $DBI->query($update);

	if( $update_res ){

		$param['email'] 	= $user_res_row[0]['email'];
		$param['subject'] 	= "Motorbuddy Appointment Booking OTP";
		$param['message'] 	= "Your Motorbuddy Appointment Booking OTP is ".$otp;
		$param['mobile'] 	= $user_res_row[0]['mobile'];

		//sendOtp($param);
		sendOtpMobile($param);

		$msg = "Booking OTP resend successfully";
		$success = true;
	} else {
		$msg = "Resend otp fail!!!";	
		$success = false;
	}

	$response = array("booking_id"=>$booking_id);

	$final_result['success'] = $success;
	$final_result['message'] = $msg;
	$final_result['result'] = $response;

	return $final_result;
}

/* verify booking OTP */
function otpVerificationBooking(){

	GLOBAl $DBI , $body_params;

	$user_id 		=	mysql_real_escape_string(trim($body_params['user_id']));
	$access_token 	=	mysql_real_escape_string(trim($body_params['access_token']));
	$otp			=	mysql_real_escape_string(trim($body_params['otp']));
	$booking_id		=	mysql_real_escape_string(trim($body_params['booking_id']));
	$dealer_id		=	mysql_real_escape_string(trim($body_params['dealer_id']));

	/* validations */
	$error = array();

	$select_dealer_dtls = "SELECT * FROM tbl_mb_delaer_master WHERE id = ".$dealer_id." AND status = 'Active' ";
	$select_dealer_res = $DBI->query($select_dealer_dtls);
	$dealer_res_row = $DBI->get_result($select_dealer_dtls);
	$is_empty_dealer = $DBI->is_empty($select_dealer_dtls);

	if ($is_empty_dealer) {

		$error[] = "Invalid dealer ID"; 

	}

	$select_user_dtls = "SELECT * FROM tbl_mb_register_users WHERE id = ".$user_id."  AND  access_token = '".$access_token."' AND status = 'Active'";
	$select_user_res = $DBI->query($select_user_dtls);
	$user_res_row = $DBI->get_result($select_user_dtls);
	$is_empty_user = $DBI->is_empty($select_user_dtls);
	
	if ( $is_empty_user ) {

		$error[] = "Invalid User ID"; 

	}

	$select = "SELECT * FROM tbl_mb_dealer_appointment WHERE id = '".$booking_id."' ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	$is_empty = $DBI->is_empty($select);
	
	if( $is_empty ){
		$error[] = "Invalid Booking ID"; 
	}

	if( !$is_empty ){
		if( $res_row[0]['appmt_status'] == 'confirmed' ){
			$error[] = "Booking allready confirmed."; 
		}

		if( $res_row[0]['appmt_status'] == 'cancelled' ){
			$error[] = "Your booking is cancelled."; 
		}

		if( $res_row[0]['appmt_status'] == 'verified' ){
			$error[] = "Your booking is allready verified."; 
		}

		if( $res_row[0]['appmt_status'] == 'rejected' ){
			$error[] = "Your booking is allready rejected."; 
		}


		if( $res_row[0]['is_otp_verify'] == 'Y' ){
			$error[] = "Booking OTP allready verified."; 
		}

		if( $res_row[0]['otp'] != $otp ){
			$error[] = "Invalid OTP"; 
		}
	}


	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}
	
	if( ! $is_empty ){

		$update = "UPDATE tbl_mb_dealer_appointment SET is_otp_verify = 'Y', otp_verification_date = '".CURRENT_DATE_TIME."', appmt_status = 'verified', appmt_status_change_time = '".CURRENT_DATE_TIME."' WHERE id = '".$booking_id."'";

		$update_res = $DBI->query($update);

		if( $update_res ){

			/* send mail or sms to user*/
			$param['email'] = $user_res_row[0]['email'];
			$param['subject'] = "Motorbuddy Appointment Booking Details";
			$mailMsg = "Your booking is verified. Your Booking Reference Number is ".$res_row[0]['appmt_code'];
			$param['message'] = $mailMsg;

			sendOtp($param);

			/* send mail or sms to dealer*/
			$param['email'] = $dealer_res_row[0]['mobile_no'];
			$param['subject'] = "Motorbuddy Appointment Booking Details";
			$mailMsg = "Appointment Number : ".$res_row[0]['appmt_code'].PHP_EOL."Date & Time : ".$res_row[0]['appmt_date']." :: ".$res_row[0]['appmt_time'].PHP_EOL."User Contact Details : ".$user_res_row[0]['mobile'];
			$param['message'] = $mailMsg;

			sendOtp($param);

			$msg = "Your Booking is verified successfully. Motorbuddy will confirm your booking.";
			$success = true;
		} else {
			$msg = "Booking OTP verification fail!!! Contact Mottorbuddy";	
			$success = false;
		}

		$response = array();

		$final_result['success'] = $success;
		$final_result['message'] = $msg;
		$final_result['result'] = $response;

		return $final_result;
	}
}

/**
 * 
 * @return all brand or model list
 * 
 **/

function sendBookingServiceRepair(){
	GLOBAl $DBI, $body_params;

	$type = mysql_real_escape_string(trim($body_params['type']));
	
	$select = "SELECT `id`, `name` FROM tbl_mb_booking_service_repair_master WHERE is_active = 'Y' AND type = '".$type."' ORDER BY name ASC";
	
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	$is_empty = $DBI->is_empty($select);
		
	if($is_empty){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found";
		$final_result['result'] = $response;
	} else {
		$final_result['success'] = true;
		$final_result['message'] = "Success";
		$final_result['result'] = $res_row;
	}
	
	return $final_result;
}


/**
 * 
 * @return all user bookings
 * 
 **/
function bookingList(){

	GLOBAl $DBI, $body_params, $pkg_type_arry;

	$user_id 		= mysql_real_escape_string(trim($body_params['user_id']));
	$access_token 	= mysql_real_escape_string(trim($body_params['access_token']));

	$select_user_dtls = "SELECT * FROM tbl_mb_register_users WHERE id = ".$user_id."  AND  access_token = '".$access_token."' AND status = 'Active'";
	$select_user_res = $DBI->query($select_user_dtls);
	$user_res_row = $DBI->get_result($select_user_dtls);
	$is_empty_user = $DBI->is_empty($select_user_dtls);
	
	$error = array();

	if ( $is_empty_user ) {

		$error[] = "Invalid User ID"; 

	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}

	$select = "SELECT 
	    da.id,da.appmt_code,da.brand_id,da.model_id,da.fuel_type,da.appmt_date,appmt_time,if(da.pickup_drop = 1 , 'Pickup and Drop', 'Self Delivered') as pickup_drop, da.pickup_location,IFNULL(da.pickup_pincode, '') as pickup_pincode,da.description,da.appmt_status,da.appmt_booking_time,da.appmt_service_pkg,da.appmt_repair_concern,da.dealer_id,
	    dm.dealer_code,dm.dealer_name,dm.dealer_name2,dm.mobile_no,bmm.brand_model_name as brand_name, bmm1.brand_model_name as model_name 
	FROM
	    tbl_mb_dealer_appointment AS da
	        LEFT JOIN
	    tbl_mb_delaer_master AS dm ON da.dealer_id = dm.id
	        LEFT JOIN
	    tbl_mb_register_users AS ru ON da.user_id = ru.id
	    	LEFT JOIN
	    tbl_mb_brand_model_master AS bmm ON da.brand_id = bmm.id
	    	LEFT JOIN
	    tbl_mb_brand_model_master AS bmm1 ON da.model_id = bmm1.id
	where da.user_id = '".$user_id."' ORDER BY da.id DESC";

	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	$is_empty = $DBI->is_empty($select);
		
	if($is_empty){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found";
		$final_result['result'] = $response;
	} else {

		$temp_res_row = array();
		foreach ($res_row as $key => $value) {
			
			$select_recommedation_pdf = "SELECT p.file_url FROM tbl_mb_recomedation_pdf_model_mapping AS m LEFT JOIN tbl_mb_recomendation_pdf AS p
			on m.recomedation_pdf_id = p.id WHERE m.model_id = '".$value['model_id']."' AND p.status = 'Active' ";
			/*$select_recommedation_pdf = "SELECT p.file_url FROM tbl_mb_recomedation_pdf_model_mapping AS m LEFT JOIN tbl_mb_recomendation_pdf AS p
			on m.recomedation_pdf_id = p.id LIMIT 1 ";*/

			$select_recommedation_pdf_res = $DBI->query($select_recommedation_pdf);
			$recommedation_pdf_res_row = $DBI->get_result($select_recommedation_pdf);
			if( empty($recommedation_pdf_res_row)){
				$value['file_url'] = '';
			} else {
				$value['file_url'] = $recommedation_pdf_res_row[0]['file_url'];
			}

			if( $value['appmt_service_pkg'] != "" ){
				$select_pkg_info = "SELECT pkg_type_id as pkg_type , pkg_price FROM tbl_mb_pkg_master WHERE id = '".$value['appmt_service_pkg']."'";
				$pkg_info_res = $DBI->query($select_pkg_info);
				$pkg_info_row = $DBI->get_result($select_pkg_info);

				if(!empty($pkg_info_row)){
					$pkg_info_row[0]['pkg_type'] = $pkg_type_arry[$pkg_info_row[0]['pkg_type']];
					$value['pkg_info'] = $pkg_info_row[0];
				} else {
					$value['pkg_info'] =  (object)(array());
				}

			} else {
				$value['pkg_info'] = (object)(array());
			}
			
			

			if( $value['appmt_repair_concern'] != "" ){

				$select_service_repair = "SELECT name FROM tbl_mb_booking_service_repair_master WHERE id IN (".$value['appmt_repair_concern'].")";
				$service_repair_res = $DBI->query($select_service_repair);
				$service_repair_row = $DBI->get_result($select_service_repair);

				if(!empty($service_repair_row)){
					$value['pkg_service_repair'] = $service_repair_row;
					/*foreach ($service_repair_row as $key1 => $value1) {
						$value['pkg_service_repair'][] = $value1['name'];	
					}*/
				} else {
					$value['pkg_service_repair'] = array();
				}
			} else {
				$value['pkg_service_repair'] = array();
			}


			$temp_res_row[] = $value;
		}

		$final_result['success'] = true;
		$final_result['message'] = "Success";
		$final_result['result'] = $temp_res_row;
	}
	
	return $final_result;

}

function sendBookingPkg(){
	GLOBAl $DBI, $body_params, $pkg_type_arry;

	$user_id 		= mysql_real_escape_string(trim($body_params['user_id']));
	$access_token 	= mysql_real_escape_string(trim($body_params['access_token']));
	$brand_id 		= mysql_real_escape_string(trim($body_params['brand_id']));
	$model_id 		= mysql_real_escape_string(trim($body_params['model_id']));

	$select_user_dtls = "SELECT * FROM tbl_mb_register_users WHERE id = ".$user_id."  AND  access_token = '".$access_token."' AND status = 'Active'";
	$select_user_res = $DBI->query($select_user_dtls);
	$user_res_row = $DBI->get_result($select_user_dtls);
	$is_empty_user = $DBI->is_empty($select_user_dtls);
	
	$error = array();

	if ( $is_empty_user ) {

		$error[] = "Invalid User ID"; 

	}


	$select_brand_mapping = "SELECT pkg_group_name FROM tbl_mb_pkg_brand_mapping WHERE brand_model_id IN (".$brand_id.",".$model_id.") LIMIT 1";
	$select_mapping_res = $DBI->query($select_brand_mapping);
	$mapping_res_row = $DBI->get_result($select_brand_mapping);
	$is_empty_mapping = $DBI->is_empty($select_brand_mapping);

	if( $is_empty_mapping ){
		$error[] = "No Service pakages found for selected brand and model. please contact to mottorbuddy team"; 
	}


	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}

	$select_pkg_details = "SELECT id, pkg_type_id, pkg_price, pkg_description, mb_tip, IFNULL(includes, '') as includes FROM tbl_mb_pkg_master WHERE pkg_group_name = '".$mapping_res_row[0]['pkg_group_name']."' AND status = 'Active' ORDER BY pkg_type_id ASC";
	$select_pkg_res = $DBI->query($select_pkg_details);
	$pkg_res_row = $DBI->get_result($select_pkg_details);

	$pkg_details_array = array();
	foreach ($pkg_res_row as $key => $value) {
		
		$value['pkg_type_id'] = $pkg_type_arry[$value['pkg_type_id']];

		$select_pkg_service_details = "SELECT id, service_name, service_action FROM tbl_mb_pkg_service_details WHERE pkg_group_name = '".$mapping_res_row[0]['pkg_group_name']."' AND pkg_master_id = '".$value['id']."' AND status = 'Active' ORDER BY id ASC";
		$select_pkg_service_res = $DBI->query($select_pkg_service_details);
		$pkg_service_res_row = $DBI->get_result($select_pkg_service_details);
		$pkg_details_array[$key]['pkg_details'] = $value;
		$pkg_details_array[$key]['pkg_services'] = $pkg_service_res_row;

	}

	$select_recommedation_pdf = "SELECT p.file_url FROM tbl_mb_recomedation_pdf_model_mapping AS m LEFT JOIN tbl_mb_recomendation_pdf AS p
	on m.recomedation_pdf_id = p.id WHERE m.model_id = '".$model_id."' AND p.status = 'Active' ";
	/*$select_recommedation_pdf = "SELECT p.file_url FROM tbl_mb_recomedation_pdf_model_mapping AS m LEFT JOIN tbl_mb_recomendation_pdf AS p
	on m.recomedation_pdf_id = p.id LIMIT 1 ";*/

	$select_recommedation_pdf_res = $DBI->query($select_recommedation_pdf);
	$recommedation_pdf_res_row = $DBI->get_result($select_recommedation_pdf);

	if( !empty($pkg_details_array) ){
		$final_result['success'] = true;
		$final_result['message'] = "Success";
		if( empty($recommedation_pdf_res_row)){
			$final_result['recommedation_pdf'] = (object)(array());
		} else {
			$final_result['recommedation_pdf'] = $recommedation_pdf_res_row[0];	
		}
		
		$final_result['result'] = $pkg_details_array;
	} else {

		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Package Details Found.";
		$final_result['result'] = $response;

	}

	return $final_result;
	
}

function sendRecommedationPDF(){
    
    GLOBAl $DBI, $body_params;

	$user_id 		= mysql_real_escape_string(trim($body_params['user_id']));
	$access_token 	= mysql_real_escape_string(trim($body_params['access_token']));
	$model_id       = mysql_real_escape_string(trim($body_params['model_id']));

	$select_user_dtls = "SELECT * FROM tbl_mb_register_users WHERE id = ".$user_id."  AND  access_token = '".$access_token."' AND status = 'Active'";
	$select_user_res = $DBI->query($select_user_dtls);
	$user_res_row = $DBI->get_result($select_user_dtls);
	$is_empty_user = $DBI->is_empty($select_user_dtls);
	
	$error = array();

	if ( $is_empty_user ) {

		$error[] = "Invalid User ID"; 

	}
	
	if( $model_id == "" ){
	    $error[] = "Invalid Model ID";
	}

	if( count($error) ){
		$response = array();

		$final_result['success'] = false;
		$final_result['message'] = implode("||", $error);
		$final_result['result'] = $response;

		return $final_result;
	}

	$select = "SELECT id, file_url FROM tbl_mb_recomendation_pdf WHERE model_id = '".$model_id."' AND status = 'Active' ";

	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	$is_empty = $DBI->is_empty($select);
		
	if($is_empty){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found";
		$final_result['result'] = $response;
	} else {
		
		$final_result['success'] = true;
		$final_result['message'] = "Success";
		$final_result['result'] = $res_row;
	}
	
	return $final_result;
}

function trkUserCallAction(){

	GLOBAl $DBI, $body_params;

	$user_id 		= mysql_real_escape_string(trim($body_params['user_id']));
	$dealer_id 		= mysql_real_escape_string(trim($body_params['dealer_id']));
	$brand_id       = mysql_real_escape_string(trim($body_params['brand_id']));
	$model_id       = mysql_real_escape_string(trim($body_params['model_id']));
	$fuel_type      = mysql_real_escape_string(trim($body_params['fuel_type']));

	if( !empty($user_id) && !empty($dealer_id) ){

		$table = "tbl_mb_track_call";

		$insert['user_id']		=		$user_id; 
		$insert['dealer_id']	=		$dealer_id;
		$insert['brand_id']		=		$brand_id;
		$insert['model_id']		=		$model_id;
		$insert['fuel_type']	=		$fuel_type;
		$insert['created_at']	=		CURRENT_DATE_TIME;

		$res = $DBI->insert_query($insert, $table);

		$response = array();
		$final_result['success'] = true;
		$final_result['message'] = "Track data successfully";
		$final_result['result'] = $response;

	} else {

		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "Invalid user id or dealer id";
		$final_result['result'] = $response;

	}

	return $final_result;

}

function dealerUserReview(){

	GLOBAl $DBI, $body_params;

	$dealer_id 		= mysql_real_escape_string(trim($body_params['dealer_id']));

	$select = "SELECT dr.id, dr.user_name_manually, dr.ratings, dr.comment, dr.created_on FROM tbl_mb_dealer_ratings as dr
			LEFT JOIN
	    tbl_mb_brand_model_master AS bmm ON dr.brand_id = bmm.id
	    	LEFT JOIN
	    tbl_mb_brand_model_master AS bmm1 ON dr.model_id = bmm1.id 
	    	WHERE dealer_id = '".$dealer_id."' AND status = 'Active' ORDER BY created_on DESC";

	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	$is_empty = $DBI->is_empty($select);
		
	if($is_empty){
		$response = array();
		$final_result['success'] = false;
		$final_result['message'] = "No Record found";
		$final_result['result'] = $response;
	} else {
		
		$final_result['success'] = true;
		$final_result['message'] = "Success";
		$final_result['result'] = $res_row;
	}
	
	return $final_result;



}


/* invalid action */
function defaultAction($msg){

	$response = array();

	$final_result['success'] = false;
	$final_result['message'] = $msg;
	$final_result['result'] = $response;

	return $final_result;

}

?>