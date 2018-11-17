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
					$payment_method_str .= $payment_method.",";
				}

			}

			$payment_method_str = rtrim($payment_method_str,',');
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


/* invalid action */
function defaultAction($msg){

	$response = array();

	$final_result['success'] = false;
	$final_result['message'] = $msg;
	$final_result['result'] = $response;

	return $final_result;

}

?>