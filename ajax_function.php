<?php
require_once 'config.php';
$DBI = new Db();

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$action	= $_POST['action'];
switch ($action){
    case "delete_brand_model":
        delete_brand_model();
        break;

    case "delete_dealer_info":
        delete_dealer_info();
        break;

    case "delete_dealer_img":
        delete_dealer_img();
        break;

    case "delete_payment_method":
        delete_payment_method();
        break;

    case "delete_insurance_company":
        delete_insurance_company();
        break;

    case "delete_shop_service":
        delete_shop_service();
        break;
        
    case "delete_shop_amenities":
        delete_shop_amenities();
        break;

    case "delete_service_repair":
        delete_service_repair();
        break;

    case "delete_review_rating":
        delete_review_rating();
        break;

    case "read_feedback":
        read_feedback();
        break;

    case "read_contact_us":
        read_contact_us();
        break;

    case "appointment_action":
        appointment_action();
        break;

    case "send_pickeup_otp":
        send_pickeup_otp();
        break;    

    case "delete_pkg":
        delete_pkg();
        break;    

    case "delete_recom_pdf":
        delete_recom_pdf();
        break;

    case "searchEmail":
        searchEmail();
        break;    

    case "delete_dealer_rating":
        delete_dealer_rating();
        break;    
}
function searchEmail(){
	global $DBI;

	$select_pp = "SELECT id, email, fname, lname FROM tbl_mb_register_users WHERE `email` LIKE '%".addslashes($_POST['inputVal'])."%'  OR  `fname` LIKE '%".addslashes($_POST['inputVal'])."%'  OR `lname` LIKE '%".addslashes($_POST['inputVal'])."%'";
	$pp_result = $DBI->query($select_pp);
	$pp_row = $DBI->get_result($select_pp);
	if(!empty($pp_row)){
        foreach ($pp_row as $key => $value) {
            echo "<p val=".$value['id'].">" . $value['email'] . " - " . $value['fname'] . " " . $value['lname'] . "</p>";
        }
    }else{
        echo "<p>No matches found</p>";
    }
	exit();
}

    
function delete_brand_model(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_brand_model_master` SET `is_active` = '".mysql_real_escape_string($_POST['status'])."' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' OR brand_id = '".mysql_real_escape_string($_POST['id'])."'"; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record Updated successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_dealer_rating(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_dealer_ratings` SET `status` = 'Inactive' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);


	$params['dealer_id']    = mysql_real_escape_string($_POST['dealer_id']);
	$avg_rating_data = calculate_avg_dealer_ratings_delete($params);

	if( $avg_rating_data['avg_ratings'] > 0 ){

		$update = "UPDATE tbl_mb_dealer_ratings SET total_ratings = '".$avg_rating_data['total_ratings']."', total_user = '".$avg_rating_data['total_user']."' WHERE id = '".$avg_rating_data['last_id']."' ";

    	$res_update = $DBI->query($update);
	}

    $update = "UPDATE tbl_mb_delaer_master SET dealer_rating = '".$avg_rating_data['avg_ratings']."' WHERE id = '".mysql_real_escape_string($_POST['dealer_id'])."' ";

    $res_update = $DBI->query($update);

	if($res_at_delete){
		echo "Record Deleted successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_payment_method(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_payment_method_master` SET `is_active` = 'N' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_insurance_company(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_insurance_company_master` SET `is_active` = 'N' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_shop_amenities(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_shop_amenities_master` SET `is_active` = 'N' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_shop_service(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_shop_service_master` SET `is_active` = 'N' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_review_rating(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_review_rating` SET `is_active` = 'N' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_service_repair(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_booking_service_repair_master` SET `is_active` = 'N' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_dealer_info(){
	global $DBI;

	/*$delete_at_row_first = "DELETE FROM tbl_mb_delaer_shop_timing WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_first = $DBI->query($delete_at_row_first);

	$delete_at_row_secand = "DELETE FROM tbl_mb_delaer_shop_service WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_seacand = $DBI->query($delete_at_row_secand);

	$delete_at_row_third = "DELETE FROM tbl_mb_delaer_insurance_tie_ups WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_third = $DBI->query($delete_at_row_third);

	$delete_at_row_fourth = "DELETE FROM tbl_mb_delaer_amenities WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_fourth = $DBI->query($delete_at_row_fourth);

	$delete_at_row_fifth = "DELETE FROM tbl_mb_delaer_brand_service WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_fifth = $DBI->query($delete_at_row_fifth);*/

	$delete_at_row = "UPDATE `tbl_mb_delaer_master` SET `status` = '".mysql_real_escape_string($_POST['status'])."' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."'"; //Soft Delete
	
	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record ".mysql_real_escape_string($_POST['status'])." successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}
}

function delete_dealer_img(){

	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_delaer_master` SET `".mysql_real_escape_string($_POST['col_name'])."` = '' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}

}

function read_feedback(){
	global $DBI;

	$update = "UPDATE `tbl_mb_feedback` SET `is_read` = 'Y' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' ";

	$res_update = $DBI->query($update);

	if($res_update){
		echo "Record Updated successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}	
}

function read_contact_us(){
	global $DBI;

	$update = "UPDATE `tbl_mb_contact_us` SET `is_read` = 'Y' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' ";

	$res_update = $DBI->query($update);

	if($res_update){
		echo "Record Updated successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}	
}

function appointment_action(){

	global $DBI;

	$booking_status = mysql_real_escape_string($_POST['booking_status']);

	$update = "UPDATE tbl_mb_dealer_appointment SET appmt_status = '".$booking_status."', appmt_status_change_time = '".CURRENT_DATE_TIME."' WHERE id = '".mysql_real_escape_string($_POST['id'])."'";

	$res_update = $DBI->query($update);

	if( $booking_status != "closed" ){
		$booking_sql = "SELECT appmt_code, appmt_date, appmt_time, dealer_id FROM tbl_mb_dealer_appointment WHERE id = '".mysql_real_escape_string($_POST['id'])."' ";
		$booking_result = $DBI->query($booking_sql);
		$booking_row = $DBI->get_result($booking_sql);

		$dealer_sql = "SELECT dealer_name, dealer_name2 FROM tbl_mb_delaer_master WHERE id = '".mysql_real_escape_string($booking_row[0]['dealer_id'])."' ";
		$dealer_result = $DBI->query($dealer_sql);
		$dealer_row = $DBI->get_result($dealer_sql);

		$user_sql = "SELECT mobile FROM tbl_mb_register_users WHERE id = '".mysql_real_escape_string($_POST['user_id'])."' ";
		$user_result = $DBI->query($user_sql);
		$user_row = $DBI->get_result($user_sql);
		
		$data['mobile'] 	= $user_row[0]['mobile'];
		$data['message'] 	= "Your Booking Number ".$booking_row[0]['appmt_code']." is ".$booking_status." with ".$dealer_row[0]['dealer_name']." ".$dealer_row[0]['dealer_name2']." on date ".$booking_row[0]['appmt_date']." ".$booking_row[0]['appmt_time'];

		$resp = sendOtpMobile($data);
	}

	echo "Record Updated successfully";
	//echo $data['message'];
	exit;

}

function send_pickeup_otp(){

	global $DBI;

	$booking_id 	= mysql_real_escape_string($_POST['booking_id']);
	$user_id 		= mysql_real_escape_string($_POST['user_id']);
	$pickeup_person = mysql_real_escape_string($_POST['pickeup_person']);
	
	$pickup_otp = otp();

	$update = "UPDATE tbl_mb_dealer_appointment SET pickup_otp = '".$pickup_otp."', pickup_otp_sent_time = '".CURRENT_DATE_TIME."', pickup_person = '".$pickeup_person."', pickup_otp_sent_count = (pickup_otp_sent_count+1) WHERE id = '".$booking_id."'";

	$res_update = $DBI->query($update);

	$select_pp = "SELECT mobile_no, person_full_name FROM tbl_mb_pickup_persons WHERE id = '".$pickeup_person."' AND is_active = 1";
	$pp_result = $DBI->query($select_pp);
	$pp_row = $DBI->get_result($select_pp);

	$user_sql = "SELECT mobile FROM tbl_mb_register_users WHERE id = '".$user_id."' ";
	$user_result = $DBI->query($user_sql);
	$user_row = $DBI->get_result($user_sql);

	//send pickup otp to user
	$data['mobile'] 	= $user_row[0]['mobile'];
	$data['message'] 	= "Your Pickup OTP for mottorbuddy ". $pickup_otp." Pickup person ".$pp_row[0]['person_full_name']." confirm this OTP with you";
	$resp = sendOtpMobile($data);

	//send pickup otp to pickup person
	$data['mobile'] 	= $pp_row[0]['mobile_no'];
	$data['message'] 	= "Your Pickup OTP for mottorbuddy ". $pickup_otp. "You need to confirm this OTP with user";
	$resp = sendOtpMobile($data);

	echo "Pickup OTP send Successfully";

	exit;
	
}

function delete_pkg(){

	global $DBI;

	$delete_pkg = "UPDATE `tbl_mb_pkg_master` SET `status` = '".mysql_real_escape_string($_POST['status'])."', updated_by = '".$_SESSION['id']."', updated_on = '".CURRENT_DATE_TIME."' WHERE `pkg_group_name` = '".mysql_real_escape_string($_POST['pkg_group_name'])."' "; //Soft Delete;

	$res_pkg_delete = $DBI->query($delete_pkg);

	$delete_pkg_services = "UPDATE `tbl_mb_pkg_service_details` SET `status` = 'Inactive' WHERE `pkg_group_name` = '".mysql_real_escape_string($_POST['pkg_group_name'])."' "; //Soft Delete

	$res_pkg_services_delete = $DBI->query($delete_pkg_services);

	if($res_pkg_services_delete){
		echo "Record updated successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}

}

function delete_recom_pdf(){

	global $DBI;

	$delete_recom_pdf = "UPDATE `tbl_mb_recomendation_pdf` SET `status` = '".mysql_real_escape_string($_POST['status'])."', updated_by = '".$_SESSION['id']."', updated_on = '".CURRENT_DATE_TIME."' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' "; //Soft Delete;

	$res_recom_pdf = $DBI->query($delete_recom_pdf);
	

	if($res_recom_pdf){
		echo "Record updated successfully";exit;
	} else {
		echo "SQL Error!!! Please Try again";exit;
	}

}

?>