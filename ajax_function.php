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
}
    
function delete_brand_model(){
	global $DBI;

	$delete_at_row = "UPDATE `tbl_mb_brand_model_master` SET `is_active` = 'N' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."' OR brand_id = '".mysql_real_escape_string($_POST['id'])."'"; //Soft Delete

	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
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

function delete_dealer_info(){
	global $DBI;

	$delete_at_row_first = "DELETE FROM tbl_mb_delaer_shop_timing WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_first = $DBI->query($delete_at_row_first);

	$delete_at_row_secand = "DELETE FROM tbl_mb_delaer_shop_service WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_seacand = $DBI->query($delete_at_row_secand);

	$delete_at_row_third = "DELETE FROM tbl_mb_delaer_insurance_tie_ups WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_third = $DBI->query($delete_at_row_third);

	$delete_at_row_fourth = "DELETE FROM tbl_mb_delaer_amenities WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_fourth = $DBI->query($delete_at_row_fourth);

	$delete_at_row_fifth = "DELETE FROM tbl_mb_delaer_brand_service WHERE dealer_id = '".$_POST['id']."' ";
	$res_at_delete_fifth = $DBI->query($delete_at_row_fifth);

	$delete_at_row = "UPDATE `tbl_mb_delaer_master` SET `status` = 'Inactive' WHERE `id` = '".mysql_real_escape_string($_POST['id'])."'"; //Soft Delete
	
	$res_at_delete = $DBI->query($delete_at_row);

	if($res_at_delete){
		echo "Record deleted successfully";exit;
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

?>