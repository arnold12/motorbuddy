<?php
function sanitize($str) {
    return strip_tags(trim(($str)));
}

function isUserLoggedIn(){
	if(isset($_SESSION['username']) && $_SESSION['username'] != '' ){
		return true;
	}
	return false;
}

function convertDate($date){
	$date = explode('-',$date);	
	$date1 = $date[1].'/'.$date[2].'/'.$date[0];	
	return $date1;
}

function dmytoymd($date)
{
	$date1 = explode('-',$date);
	return $date1['2']."-".$date1['1']."-".$date1['0'];
}

function ymdtodmy($date)
{
	$date1 = explode('-',$date);
	return $date1['2']."-".$date1['1']."-".$date1['0'];
}

function ymdtodmy_withtime($date)
{
	$date1 = explode('-',$date);
	$dat1 = substr($date1['2'],0,2);
	$dat2 = substr($date1['2'],3);
	return $dat1."-".$date1['1']."-".$date1['0']." ".$dat2;
}

function bitwisesummation($bitwise_array , $data)
{
	// print_r($bitwise_array);
	// print_r($data);
	// exit();
	$sum = 0;
	foreach($bitwise_array AS $key => $value)
	{
		if(isset($data[$key])){
			if($data[$key])
			{
				$sum += $value;
			}	
		}
		
	}
	return $sum;
}

function fetchbitwisevalues($bitwisearr , $data , $str=0)
{
	$subsresult = array();
	foreach($bitwisearr AS $key=>$value)
	{
		if( $data & $value )
		{		
			$subsresult[$key] = 1;
		}
		else
		{
			$subsresult[$key] = 0;
		}
	}
	return $subsresult;
}

function randomString($length = 12) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function otp($length = 4) {
	$str = "";
	$characters = range('0','9');
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}


function generateDealerCode( $attempt = 1 ){

	$DBI = new Db();

	$arr_dealer_codes = array();
	for ($i=0; $i < 10; $i++) { 

		$temp_dealer_code = 'MB'.rand(1, 99999999);

		if(strlen($temp_dealer_code)<10){
			$temp_dealer_code = str_pad($temp_dealer_code, 10, "0", STR_PAD_RIGHT);
		}

		$arr_dealer_codes[] = $temp_dealer_code;
	}

	$dealer_code_str = "'".implode("','", $arr_dealer_codes)."'";
	
	$select = "SELECT distinct dealer_code FROM tbl_mb_delaer_master WHERE dealer_code IN (".$dealer_code_str.") ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);

	if(count($res_row)>0){
        $arr_dealer_codes_2 = array();
        foreach($res_row as $row){

            $arr_dealer_codes_2[] = $row['dealer_code'];
        }
        
        $arr_dealer_codes = array_diff($arr_dealer_codes, $arr_dealer_codes_2);
    }

    if(count($arr_dealer_codes))
    {
        return array_pop($arr_dealer_codes);
    } else {

    	$attempt++;
        if($attempt>3)
        {
            return false;
        }
        generateDealerCode($attempt);

    }
	
}

function generateBookingCode( $attempt = 1 ){

	$DBI = new Db();

	$arr_booking_codes = array();
	for ($i=0; $i < 10; $i++) { 

		$temp_booking_code = 'BK'.rand(1, 99999999);

		if(strlen($temp_booking_code)<10){
			$temp_booking_code = str_pad($temp_booking_code, 10, "0", STR_PAD_RIGHT);
		}

		$arr_booking_codes[] = $temp_booking_code;
	}

	$booking_code_str = "'".implode("','", $arr_booking_codes)."'";
	
	$select = "SELECT distinct appmt_code FROM tbl_mb_dealer_appointment WHERE appmt_code IN (".$booking_code_str.") ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);

	if(count($res_row)>0){
        $arr_booking_codes_2 = array();
        foreach($res_row as $row){

            $arr_booking_codes_2[] = $row['appmt_code'];
        }
        
        $arr_booking_codes = array_diff($arr_booking_codes, $arr_booking_codes_2);
    }

    if(count($arr_booking_codes))
    {
        return array_pop($arr_booking_codes);
    } else {

    	$attempt++;
        if($attempt>3)
        {
            return false;
        }
        generateBookingCode($attempt);

    }
	
}

function generatePkgGroupCode( $attempt = 1 ){

	$DBI = new Db();

	$arr_pkg_codes = array();
	for ($i=0; $i < 10; $i++) { 

		$temp_pkg_code = 'PKG_MB_'.rand(1, 99999999);
		
		if(strlen($temp_pkg_code)<15){
			$temp_pkg_code = str_pad($temp_pkg_code, 10, "0", STR_PAD_RIGHT);
		}

		$arr_pkg_codes[] = $temp_pkg_code;
	}

	$pkg_code_str = "'".implode("','", $arr_pkg_codes)."'";
	
	$select = "SELECT distinct pkg_group_name FROM tbl_mb_pkg_master WHERE pkg_group_name IN (".$pkg_code_str.") ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);

	if(count($res_row)>0){
        $arr_pkg_codes_2 = array();
        foreach($res_row as $row){

            $arr_pkg_codes_2[] = $row['pkg_group_name'];
        }
        
        $arr_pkg_codes = array_diff($arr_pkg_codes, $arr_pkg_codes_2);
    }

    if(count($arr_pkg_codes))
    {
        return array_pop($arr_pkg_codes);
    } else {

    	$attempt++;
        if($attempt>3)
        {
            return false;
        }
        generatePkgGroupCode($attempt);

    }
	
}

function date_wording( $date ){
	$output_date = '-';
    if(!empty($date)){
      $output_date = date('d F, Y', strtotime($date));
    }
    return $output_date;
}

function date_time_wording( $date ){
	$output_date = '-';
    if(!empty($date)){
      $output_date = date('d F, Y H:i:s', strtotime($date));
    }
    return $output_date;
}

/* send otp to user on mobile*/
function sendOtpMobile($data){
	
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

}

function getDealerDtls($dealer_id){
	$DBI = new Db();

	$select = "SELECT dealer_name, dealer_name2, mobile_no FROM tbl_mb_delaer_master WHERE id = ".$dealer_id." ";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	
	return $res_row;

}

function get_pickup_persons($dealer_id){
	$DBI = new Db();

	$select = "SELECT id, person_full_name, mobile_no FROM tbl_mb_pickup_persons WHERE dealer_id = ".$dealer_id." AND is_active = 1";
	$select_res = $DBI->query($select);
	$res_row = $DBI->get_result($select);
	return $res_row;

}

function calculate_avg_dealer_ratings($params){
	$DBI = new Db();

	$select_ratings = "SELECT  `total_ratings`, `total_user`
    FROM `tbl_mb_dealer_ratings`
    WHERE `dealer_id` = '".$params['dealer_id']."' AND status = 'Active' ORDER BY id DESC LIMIT 1";

    $result_ratings = $DBI->query($select_ratings);

    if(mysql_num_rows($result_ratings) == 0){
    	$return_data['total_ratings'] 	= $params['ratings'];
    	$return_data['total_user'] 		= 1;
    	$return_data['avg_ratings'] 	= $params['ratings'];
    } else {
    	$rows_ratings = $DBI->get_result($select_ratings);

    	$new_total_ratings  = $rows_ratings[0]['total_ratings'] + $params['ratings'];
    	$new_total_users    = $rows_ratings[0]['total_user'] + 1;

    	$return_data['total_ratings'] 	= $new_total_ratings;
    	$return_data['total_user'] 		= $new_total_users;
    	$return_data['avg_ratings'] 	= number_format(($new_total_ratings / $new_total_users), 2);


    }
    
    return $return_data;
    


}

?>