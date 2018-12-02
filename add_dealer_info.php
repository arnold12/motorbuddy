<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$payment_all_checked = "";
$shop_all_checked = "";
$service_all_checked = "";
$insurance_all_checked = "";
$amenities_all_checked = "";
$brand_all_checked = "";


if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

// fetch brand from master
$select_brand_master = "SELECT `id`, `brand_model_name`
FROM `tbl_mb_brand_model_master`
WHERE `is_active` = 'Y' AND `brand_id` = 0 ";
$result_brand_master = $DBI->get_result($select_brand_master);
$result_brand_master_arry = [];
if(!empty($result_brand_master)){
    foreach ($result_brand_master as $key => $value) {
        $result_brand_master_arry[$value['id']] =  $value['brand_model_name'];
    }
}


// fetch insurance company from master
$select_insurance_company = "SELECT `id`, `insurance_company`
FROM `tbl_mb_insurance_company_master`
WHERE `is_active` = 'Y' ";
$result_insurance_company = $DBI->get_result($select_insurance_company);
$result_insurance_company_arry = [];
if(!empty($result_insurance_company)){
    foreach ($result_insurance_company as $key => $value) {
        $result_insurance_company_arry[$value['id']] =  $value['insurance_company'];
    }
}

//fetch shop_amenities from master
$select_shop_amenities = "SELECT  `id`, `shop_amenities`
FROM `tbl_mb_shop_amenities_master`
WHERE `is_active` = 'Y' ";
$result_shop_amenities = $DBI->get_result($select_shop_amenities);
$result_shop_amenities_arry = [];
if(!empty($result_shop_amenities)){
    foreach ($result_shop_amenities as $key => $value) {
        $result_shop_amenities_arry[$value['id']] =  $value['shop_amenities'];   
    }
}


//fetch shop_service from master
$select_shop_service = "SELECT `id`, `shop_service`
FROM `tbl_mb_shop_service_master`
WHERE `is_active` = 'Y' ";
$result_shop_service = $DBI->get_result($select_shop_service);
$result_shop_service_arry = [];
if(!empty($result_shop_service)){
    foreach ($result_shop_service as $key => $value) {
        $result_shop_service_arry[$value['id']] = $value['shop_service'];
    }
}

if(isset($_GET['id']) && $_GET['id'] != "" && !isset($_POST['frm'])){
    
    // fetch delaer data
    $select_info = "SELECT `dealer_code`,`dealer_name`,`dealer_name2`, `landmark`, `city`, `state`, `pincode`, `mobile_no`, `telephone_no`, `establishment_year`, `address`, `website`, `about_dealer`, `payment_mode`, `lat`, `long`, `gstn`, `dealer_rating`, `img_1`, `img_2`, `img_3`
    FROM `tbl_mb_delaer_master`
    WHERE `id` = '".$_GET['id']."'
    AND `status` = 'Active'
    LIMIT 0 , 1";

    $result_info = $DBI->query($select_info);
    
    if(mysql_num_rows($result_info) == 0){
        header('Location: index.php');die();
    }
    //$row_info = mysql_fetch_array($result_info);
    $rows_info = array_shift($DBI->get_result($select_info));

    // fetch services
    $select_services = "SELECT `shop_service_name`
    FROM `tbl_mb_delaer_shop_service`
    WHERE `dealer_id` = '".$_GET['id']."'";
    $result_services = $DBI->get_result($select_services);
    $result_services_arry = [];
    if(!empty($result_services)){
        $result_services_arry = array_column($result_services, 'shop_service_name');
    }

    // fetch insurance
    $select_insurance = "SELECT `insurance_company`
    FROM `tbl_mb_delaer_insurance_tie_ups`
    WHERE `dealer_id` = '".$_GET['id']."'";
    $result_insurance = $DBI->get_result($select_insurance);
    $result_insurance_arry = [];
    if(!empty($result_insurance)){
        $result_insurance_arry = array_column($result_insurance, 'insurance_company');
    }

    // fetch amenities
    $select_amenities = "SELECT `amenities`
    FROM `tbl_mb_delaer_amenities`
    WHERE `dealer_id` = '".$_GET['id']."'";
    $result_amenities = $DBI->get_result($select_amenities);
    $result_amenities_arry = [];
    if(!empty($result_amenities)){
        $result_amenities_arry = array_column($result_amenities, 'amenities');
    }

    // fetch brand
    $select_brand = "SELECT `brand_name`
    FROM `tbl_mb_delaer_brand_service`
    WHERE `dealer_id` = '".$_GET['id']."'";
    $result_brand = $DBI->get_result($select_brand);
    $result_brand_arry = [];
    if(!empty($result_brand)){
        $result_brand_arry = array_column($result_brand, 'brand_name');
    }

    // fetch timing
    $select_time = "SELECT `day`,`is_open`,`open_at`,`close_at`
    FROM `tbl_mb_delaer_shop_timing`
    WHERE `dealer_id` = '".$_GET['id']."'";
    $result_time = $DBI->get_result($select_time);

    // Check all button should be checked when all filed are check

    // Payment method checked
    $bitwise_count = 0;
    foreach ($payment_method_bitwise as $key_bitwise => $value_bitwise) {
        $bitwise_count += $value_bitwise;
    }
    if($bitwise_count == $rows_info['payment_mode']){
        $payment_all_checked = "checked";
    }

    // Time checked
    $result_time_count = count($result_time);
    $shopes_hours_arry_count = count($shopes_hours_arry);
    if($result_time_count == $shopes_hours_arry_count){
        $shop_all_checked = "checked";
    }

    // Shop service checked
    $result_services_count = count($result_services_arry);
    $shopes_services_arry_count = count($shopes_services_arry);
    if($result_services_count == $shopes_services_arry_count){
        $service_all_checked = "checked";
    }

    // Insurance tie ups checked
    $result_insurance_count = count($result_insurance_arry);
    $insurance_arry_count = count($insurance_arry);
    if($insurance_arry_count == $result_insurance_count){
        $insurance_all_checked = "checked";
    }

    // Shop amenities checked
    $result_amenities_count = count($result_amenities_arry);
    $amenities_arry_count = count($shopes_amenities_arry);
    if($amenities_arry_count == $result_amenities_count){
        $amenities_all_checked = "checked";
    }

    // Shop amenities checked
    $result_brand_count = count($result_brand_master_arry);
    $brand_arry_count = count($result_brand_arry);
    
    if($brand_arry_count == $result_brand_count){
        $brand_all_checked = "checked";
    }
    
    // print_r($result_time);
    // exit();
       
}

if(isset($_POST['frm']) && $_POST['frm'] == '1' ){
    
    
    $dealer_name = mysql_real_escape_string($_POST['dealer_name']);
    $dealer_name2 = mysql_real_escape_string($_POST['dealer_name2']);
    $address = mysql_real_escape_string(trim($_POST['address']));
    $landmark = mysql_real_escape_string(trim($_POST['landmark']));
    $city = mysql_real_escape_string(trim($_POST['city']));
    $state = mysql_real_escape_string(trim($_POST['state']));
    $pincode = mysql_real_escape_string(trim($_POST['pincode']));
    $mobile_no = mysql_real_escape_string(trim($_POST['mobile_no']));
    $telephone_no = mysql_real_escape_string(trim($_POST['telephone_no']));
    $establishment_year = mysql_real_escape_string(trim($_POST['establishment_year']));
    $website = mysql_real_escape_string(trim($_POST['website']));
    $about_dealer = mysql_real_escape_string(trim($_POST['about_dealer']));
    $lat = mysql_real_escape_string(trim($_POST['lat']));
    $long = mysql_real_escape_string(trim($_POST['long']));
    $gstn = mysql_real_escape_string(trim($_POST['gstn']));
    $dealer_rating = mysql_real_escape_string(trim($_POST['dealer_rating']));
    $payment_bitwise_sum = bitwisesummation($payment_method_bitwise,$_POST);
    $shop_day = $_POST['shop_day'];
    $open_time = $_POST['open_time'];
    $close_time = $_POST['close_time'];
    if(empty($establishment_year)){
        $establishment_year = 0;
    }
    $target_dir_img = "images/dealer/";
    
    if($_POST['mode'] == 'edit'){ // Edit mode
        
        $delaer_id = $_POST['id'];


        $update_dealer_imges = "";

        for( $i=1; $i<=3; $i++){

        	if($_FILES["dealer_image".$i]["name"] !== ''){
            
	            $fileFormatExtArr = explode('.', basename($_FILES["dealer_image".$i]["name"]));
	            $file_name = $fileFormatExtArr[0] . '_' . date('YmdHis') . '.' . $fileFormatExtArr[1];

                $target_file_img = $target_dir_img . $file_name;
				$img_url = SITE_URL."/".$target_dir_img . $file_name;
				move_uploaded_file($_FILES["dealer_image".$i]["tmp_name"], $target_file_img);
				$update_dealer_imges .= " ,`img_".$i."`='".$img_url."' ";
			}

        }
		

        // Update data for delaer
        $update = "UPDATE `tbl_mb_delaer_master` SET `dealer_name`='".$dealer_name."',`dealer_name2`='".$dealer_name2."',`address`='".$address."',`landmark`='".$landmark."', `city`='".$city."', `state`='".$state."', `pincode`='".$pincode."',`mobile_no`='".$mobile_no."',`telephone_no`='".$telephone_no."',`establishment_year`='".$establishment_year."', `payment_mode`='".$payment_bitwise_sum."',`lat`='".$lat."',`long`='".$long."',`gstn`='".$gstn."', `dealer_rating`='".$dealer_rating."', `website`='".$website."', `about_dealer`='".$about_dealer."', `updated_by`='".$_SESSION['id']."', `updated_on` = now() ".$update_dealer_imges." WHERE id = '".$_POST['id']."' ";
        $res_update = $DBI->query($update);

        // Update data for shop timing 
        $week_day_count = count($shopes_hours_arry);
        for($i = 0 ; $i < $week_day_count ; $i++){
            $is_open_value = 'N';
            if(isset($_POST['is_open_'.$i])){
                $is_open_value = 'Y';
            }
            $update_shop_time = "UPDATE `tbl_mb_delaer_shop_timing` SET `open_at`='".$open_time[$i]."',`close_at`='".$close_time[$i]."',`is_open`='".$is_open_value."', `updated_by`='".$_SESSION['id']."', `updated_on` = now() WHERE dealer_id = '".$_POST['id']."' and day = '".$shop_day[$i]."' ";
            $res_shop_time = $DBI->query($update_shop_time);
        }

        // Update data for services 
        $delete_services = "DELETE FROM tbl_mb_delaer_shop_service WHERE dealer_id = '".$_POST['id']."' ";
        $res_delete_services = $DBI->query($delete_services);
        $services_count = count($shopes_services_arry);
        for($p = 0 ; $p < $services_count ; $p++){
            if(isset($_POST['service_'.$p])){
                $services_name = $_POST['service_'.$p];
                if (strpos($services_name, "'") !== FALSE){
                    $final_services_name = addslashes($services_name);
                }else{
                    $final_services_name = $services_name;
                }
                $insert_services = "INSERT INTO `tbl_mb_delaer_shop_service` (`dealer_id`, `shop_service_name`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$final_services_name."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
                $res_services = $DBI->query($insert_services);
            }
        }

        // Update data for insurance 
        $delete_insurance = "DELETE FROM tbl_mb_delaer_insurance_tie_ups WHERE dealer_id = '".$_POST['id']."' ";
        $res_delete_insurance = $DBI->query($delete_insurance);
        $insurance_count = count($insurance_arry);
        for($z = 0 ; $z < $insurance_count ; $z++){
            if(isset($_POST['insurance_'.$z])){
                $insurance_name = $_POST['insurance_'.$z];
                $insert_insurance = "INSERT INTO `tbl_mb_delaer_insurance_tie_ups` (`dealer_id`, `insurance_company`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$insurance_name."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
                $res_insurance = $DBI->query($insert_insurance);
            }
        }

        // Update data for amenities 
        $delete_amenities = "DELETE FROM tbl_mb_delaer_amenities WHERE dealer_id = '".$_POST['id']."' ";
        $res_delete_amenities = $DBI->query($delete_amenities);
        $amenities_count = count($shopes_amenities_arry);
        for($t = 0 ; $t < $amenities_count ; $t++){
            if(isset($_POST['amenities_'.$t])){
                $amenities_name = $_POST['amenities_'.$t];
                $amenities_insurance = "INSERT INTO `tbl_mb_delaer_amenities` (`dealer_id`, `amenities`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$amenities_name."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
                $res_amenities = $DBI->query($amenities_insurance);
            }
        }

        // Update data for brand 
        $delete_brand = "DELETE FROM tbl_mb_delaer_brand_service WHERE dealer_id = '".$_POST['id']."' ";
        $res_delete_brand = $DBI->query($delete_brand);
        $brand_count = count($brand_arry);
        for($q = 0 ; $q < $brand_count ; $q++){
            if(isset($_POST['brand_'.$q])){
                $brand_name = $_POST['brand_'.$q];
                $brand_insurance = "INSERT INTO `tbl_mb_delaer_brand_service` (`dealer_id`, `brand_name`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$brand_name."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
                $res_brand = $DBI->query($brand_insurance);
            }
        }
        
        
    } else { // Add mode

    	$add_dealer_imges_cols = "";
    	$add_dealer_imges_names = "";

        for( $i=1; $i<=3; $i++){
            
        	if($_FILES["dealer_image".$i]["name"] !== ''){
            
	            $fileFormatExtArr = explode('.', basename($_FILES["dealer_image".$i]["name"]));
	            $file_name = $fileFormatExtArr[0] . '_' . date('YmdHis') . '.' . $fileFormatExtArr[1];

				$target_file_img = $target_dir_img . $file_name;
                $img_url = SITE_URL."/".$target_dir_img . $file_name;
				move_uploaded_file($_FILES["dealer_image".$i]["tmp_name"], $target_file_img);
				$add_dealer_imges_cols .= " ,`img_".$i."` ";
				$add_dealer_imges_names .= ", '".$img_url."'";
			} else {
                $add_dealer_imges_cols .= " ,`img_".$i."` ";
                $add_dealer_imges_names .= ", ''";
            } 

        }

       $dealer_code = generateDealerCode(); 

       // Insert data for delaer 
       $insert = "INSERT INTO `tbl_mb_delaer_master` (`dealer_code`,`dealer_name`,`dealer_name2`, `address`, `landmark`, `city`, `state`, `pincode`, `mobile_no`, `telephone_no`, `establishment_year`, `lat`, `long`, `gstn`, `website`, `about_dealer`, `payment_mode`, `dealer_rating`, `status`, `created_on`, `updated_on`, `created_by` ".$add_dealer_imges_cols.") VALUES ('".$dealer_code."','".$dealer_name."','".$dealer_name2."', '".$address."', '".$landmark."', '".$city."', '".$state."', '".$pincode."', '".$mobile_no."', '".$telephone_no."', '".$establishment_year."', '".$lat."', '".$long."', '".$gstn."', '".$website."', '".$about_dealer."', '".$payment_bitwise_sum."', '".$dealer_rating."', 'Active', now(), now(), '".$_SESSION['id']."' ".$add_dealer_imges_names.")";
       $res_insert = $DBI->query($insert);
       $delaer_id = mysql_insert_id();

        // Insert data for shop timing 
        $week_day_count = count($shopes_hours_arry);
        for($i = 0 ; $i < $week_day_count ; $i++){
            $is_open_value = 'N';
            if(isset($_POST['is_open_'.$i])){
                $is_open_value = 'Y';
            }
            $insert_shop_time = "INSERT INTO `tbl_mb_delaer_shop_timing` (`dealer_id`, `day`, `open_at`, `close_at`, `is_open`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$shop_day[$i]."', '".$open_time[$i]."', '".$close_time[$i]."', '".$is_open_value."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
            $res_shop_time = $DBI->query($insert_shop_time);
        }

        // Insert data for services 
        $services_count = count($shopes_services_arry);
        for($p = 0 ; $p < $services_count ; $p++){
            if(isset($_POST['service_'.$p])){
                $services_name = $_POST['service_'.$p];
                if (strpos($services_name, "'") !== FALSE){
                    $final_services_name = addslashes($services_name);
                }else{
                    $final_services_name = $services_name;
                }
                $insert_services = "INSERT INTO `tbl_mb_delaer_shop_service` (`dealer_id`, `shop_service_name`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$final_services_name."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
                $res_services = $DBI->query($insert_services);
            }
        }

        // Insert data for insurance 
        $insurance_count = count($insurance_arry);
        for($z = 0 ; $z < $insurance_count ; $z++){
            if(isset($_POST['insurance_'.$z])){
                $insurance_name = $_POST['insurance_'.$z];
                $insert_insurance = "INSERT INTO `tbl_mb_delaer_insurance_tie_ups` (`dealer_id`, `insurance_company`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$insurance_name."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
                $res_insurance = $DBI->query($insert_insurance);
            }
        }

        // Insert data for amenities 
        $amenities_count = count($shopes_amenities_arry);
        for($t = 0 ; $t < $amenities_count ; $t++){
            if(isset($_POST['amenities_'.$t])){
                $amenities_name = $_POST['amenities_'.$t];
                $amenities_insurance = "INSERT INTO `tbl_mb_delaer_amenities` (`dealer_id`, `amenities`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$amenities_name."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
                $res_amenities = $DBI->query($amenities_insurance);
            }
        }

        // Insert data for brand 
        $brand_count = count($brand_arry);
        for($q = 0 ; $q < $brand_count ; $q++){
            if(isset($_POST['brand_'.$q])){
                $brand_name = $_POST['brand_'.$q];
                $brand_insurance = "INSERT INTO `tbl_mb_delaer_brand_service` (`dealer_id`, `brand_name`, `updated_by`, `created_on`, `updated_on`, `created_by`) VALUES ('".$delaer_id."', '".$brand_name."', '".$_SESSION['id']."', now(), now(), '".$_SESSION['id']."')";
                $res_brand = $DBI->query($brand_insurance);
            }
        }
       
    }   
    header('Location: index.php');
        
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=SITE_TITLE?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
         <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <!-- datepicker -->
        <link rel="stylesheet" href="dist/css/jquery-ui.css">
        <style>
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 73%;
                }

                td, th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }

                tr:nth-child(even) {
                    background-color: #dddddd;
                }
        </style>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
         <!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <!-- Select2 -->
        <script src="plugins/select2/select2.full.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/app.min.js"></script>
        <script src="dist/js/jquery-ui.js"></script>
        <script src="dist/js/common.js"></script>
        <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript">
            $(function () {
                //Timepicker
                $(".timepicker").timepicker({
                  showInputs: false
                });
            });
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php include_once("header.php") ?>
            <?php include_once("sidebar.php") ?>
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Add Dealer Information
                        <!--<small>Preview</small>-->
                    </h1>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="index.php">View Dealer Information</a></div>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_dealer_info.php">Add Dealer Information</a></div>
                    <br>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" enctype="multipart/form-data" action="add_dealer_info.php">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>

                                <?php
                                    if(isset($rows_info['dealer_code'])){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Delaer Code</label>
                                    <div class="col-sm-4 col-md-3">
                                        <?php echo $rows_info['dealer_code']?>
                                    </div>
                                </div>
                                <?php }?>

                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Delaer Name 1</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="dealer_name" name="dealer_name" value="<?=isset($rows_info['dealer_name']) ? $rows_info['dealer_name'] : '';?>">
                                        <label id="err_msg_dealer_name" for="dealer_name" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Delaer Name 2</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="dealer_name2" name="dealer_name2" value="<?=isset($rows_info['dealer_name2']) ? $rows_info['dealer_name2'] : '';?>">
                                        <label id="err_msg_dealer_name2" for="dealer_name2" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Address</label>
                                    <div class="col-sm-4 col-md-4">
                                         <textarea rows="4" cols="50" class="form-control input-sm" id="address" name="address"><?=isset($rows_info['address']) ? $rows_info['address'] : '';?></textarea> 
                                        <label id="err_msg_address" for="address" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Landmark</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="landmark" name="landmark" value="<?=isset($rows_info['landmark']) ? $rows_info['landmark'] : '';?>">
                                        <label id="err_msg_landmark" for="landmark" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">City</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="city" name="city" value="<?=isset($rows_info['city']) ? $rows_info['city'] : '';?>">
                                        <label id="err_msg_city" for="city" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">State</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="state" name="state" value="<?=isset($rows_info['state']) ? $rows_info['state'] : '';?>">
                                        <label id="err_msg_state" for="state" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Pincode</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="pincode" name="pincode" value="<?=isset($rows_info['pincode']) ? $rows_info['pincode'] : '';?>">
                                        <label id="err_msg_pincode" for="pincode" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Mobile Number</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="mobile_no" name="mobile_no" value="<?=isset($rows_info['mobile_no']) ? $rows_info['mobile_no'] : '';?>">
                                        <label id="err_msg_mobile_no" for="mobile_no" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Telephone Number</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="telephone_no" name="telephone_no" value="<?=isset($rows_info['telephone_no']) ? $rows_info['telephone_no'] : '';?>">
                                        <label id="err_msg_telephone_no" for="telephone_no" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Year of establishment</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="number" min="0" class="form-control input-sm" id="establishment_year" name="establishment_year" value="<?=isset($rows_info['establishment_year']) ? $rows_info['establishment_year'] : '';?>">
                                        <label id="err_msg_establishment_year" for="establishment_year" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Website</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="website" name="website" value="<?=isset($rows_info['website']) ? $rows_info['website'] : '';?>">
                                        <label id="err_msg_website" for="website" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Latitude</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" required class="form-control input-sm" id="lat" name="lat" value="<?=isset($rows_info['lat']) ? $rows_info['lat'] : '';?>">
                                        <label id="err_msg_lat" for="lat" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Longitude</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" required class="form-control input-sm" id="long" name="long" value="<?=isset($rows_info['long']) ? $rows_info['long'] : '';?>">
                                        <label id="err_msg_long" for="long" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">GST Number</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" required class="form-control input-sm" id="gstn" name="gstn" value="<?=isset($rows_info['gstn']) ? $rows_info['gstn'] : '';?>">
                                        <label id="err_msg_gstn" for="gstn" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">About Us</label>
                                    <div class="col-sm-4 col-md-4">
                                         <textarea rows="4" cols="50" class="form-control input-sm" id="about_dealer" name="about_dealer"><?=isset($rows_info['about_dealer']) ? $rows_info['about_dealer'] : '';?></textarea> 
                                        <label id="err_msg_about_dealer" for="about_dealer" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Dealer Rating</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" required class="form-control input-sm" id="dealer_rating" name="dealer_rating" value="<?=isset($rows_info['dealer_rating']) ? $rows_info['dealer_rating'] : '';?>">
                                        <label id="err_msg_dealer_rating" for="dealer_rating" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Dealer Image 1</label>
                                    <div class="col-sm-4 col-md-3">
										<input type="file" id="dealer_image1" name="dealer_image1" value="">
                                        [jpg, jpeg]		
										<label id="err_msg_dealer_image1" for="dealer_image1" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                    <?php if(isset($_GET['id']) && $_GET['id'] != "" && $rows_info['img_1'] != "" ){?>
									<div class="col-sm-4 col-md-3">
										<img src="<?=$rows_info['img_1']?>" alt="" height="42" width="42"> &nbsp;<a href="#" onclick="delete_dealer_img(<?=$_GET['id']?>, 'img_1');">Delete</a>
									</div>
									<?php }?>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Dealer Image 2</label>
                                    <div class="col-sm-4 col-md-3">
										<input type="file" id="dealer_image2" name="dealer_image2" value="">
                                        [jpg, jpeg]		
										<label id="err_msg_dealer_image2" for="dealer_image2" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                    <?php if(isset($_GET['id']) && $_GET['id'] != "" && $rows_info['img_2'] != ""){?>
									<div class="col-sm-4 col-md-3">
										<img src="<?=$rows_info['img_2']?>" alt="" height="42" width="42"> &nbsp;<a href="#" onclick="delete_dealer_img(<?=$_GET['id']?>, 'img_2');">Delete</a>
									</div>
									<?php }?>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Dealer Image 3</label>
                                    <div class="col-sm-4 col-md-3">
										<input type="file" id="dealer_image3" name="dealer_image3" value="">
                                        [jpg, jpeg]		
										<label id="err_msg_dealer_image3" for="dealer_image3" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                    <?php if(isset($_GET['id']) && $_GET['id'] != "" && $rows_info['img_3'] != ""){?>
									<div class="col-sm-4 col-md-3">
										<img src="<?=$rows_info['img_3']?>" alt="" height="42" width="42"> &nbsp;<a href="#" onclick="delete_dealer_img(<?=$_GET['id']?>, 'img_3');">Delete</a>
									</div>
									<?php }?>
                                </div>  
                                <br><br>
                                <hr>
                                <hr>
                                <h4><font color="red">Shope Timings</font></h4>
                                    <div class="row" style="margin-left: 4px;margin-bottom: 13px;">
                                        <span style="font-size: 16px;">Check All <input type="checkbox" id="shop_all" <?= $shop_all_checked;?>></span>
                                    </div>
                                    <label id="err_msg_shop_time" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;padding-bottom: 20px;"></label>
                                    <table>
                                        <tr>
                                        <th>Shopes Hours</th>
                                        <th>Is Open</th>
                                        <th>Open timings</th>
                                        <th>Close timings</th>
                                        </tr>
                                        <?php
                                        
                                        if(!isset($_GET['id'])){
                                        $k = 0;
                                        foreach($shopes_hours_arry as $shop_arry_key => $shop_arry_value){
                                        ?>
                                        <tr>
                                        <td><input type="text" readonly class="form-control input-sm" name="shop_day[]" value="<?= $shop_arry_value?>"></td>
                                        <td><input type="checkbox" class="shop_time" name="is_open_<?= $k?>" value="1"></td>
                                        <td><div class="bootstrap-timepicker"><div class="input-group"><input type="text" name="open_time[]" class="form-control timepicker"><div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>
                                        <td><div class="bootstrap-timepicker"><div class="input-group"><input type="text" name="close_time[]" class="form-control timepicker"><div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>
                                        </tr>
                                        <?php
                                         $k++;
                                         }
                                        }
                                        else{
                                            $k = 0;
                                            foreach($result_time as $time_key => $time_value){
                                        ?>
                                        <tr>
                                        <td><input type="text" readonly class="form-control input-sm" name="shop_day[]" value="<?= $time_value['day']?>"></td>
                                        <td><input type="checkbox" class="shop_time" name="is_open_<?= $k?>" <?=  $time_value['is_open'] == 'Y' ? 'checked' : '' ; ?> value="1"></td>
                                        <td><div class="bootstrap-timepicker"><div class="input-group"><input type="text" name="open_time[]" class="form-control timepicker" value="<?= $time_value['open_at']?>"><div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>
                                        <td><div class="bootstrap-timepicker"><div class="input-group"><input type="text" name="close_time[]" class="form-control timepicker" value="<?= $time_value['close_at']?>"><div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>
                                        </tr>
                                        <?php
                                            $k++;
                                            }
                                        }
                                        ?>
                                    </table>
                                    <br><br>
                                <h4><font color="red">Payment Method</font></h4>
                                    <div class="row" style="margin-left: 4px;margin-bottom: 13px;">
                                        <span style="font-size: 16px;">Check All <input type="checkbox" id="payment_all" <?= $payment_all_checked;?>></span>
                                    </div>
                                    <label id="err_msg_payment_method" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;padding-bottom: 20px;"></label>
                                    <div class="row" style="margin-left: 4px;">
                                        <?php
                                        $is_checked = '';
                                        foreach($payment_method_bitwise as $payment_method => $value){
                                            
                                            if(isset($rows_info['payment_mode']) && $rows_info['payment_mode'] > '0'){
                                                $color_checked = $rows_info['payment_mode'] & $value;
                                                
                                                $is_checked = ( $color_checked == '0' ? '' : 'checked');
                                                
                                            }
                                        ?>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                        <label><?=ucfirst($payment_method)?></label> <input type="checkbox" <?=$is_checked?> value="1" id="<?=$payment_method?>" name="<?=$payment_method?>" class="payment_method">
                                        </div></div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <hr>
                                    <hr>
                                <h4><font color="red">Shop Service</font></h4>
                                    <div class="row" style="margin-left: 4px;margin-bottom: 13px;">
                                        <span style="font-size: 16px;">Check All <input type="checkbox" id="service_all" <?= $service_all_checked;?>></span>
                                    </div>
                                    <label id="err_msg_shop_service" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;padding-bottom: 20px;"></label>
                                    <div class="row" style="margin-left: 4px;">
                                        <?php
                                        $p = 0;
                                        foreach($result_shop_service_arry as $services_arry_key => $services_arry_value){
                                        ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?= $services_arry_value?></label>
                                                <input <?= !empty($result_services_arry) ? in_array($services_arry_key, $result_services_arry) ? 'checked' : '' : ''; ?> type="checkbox" name="service_<?= $p?>" class="shop_service" value="<?= $services_arry_key?>">
                                            </div>
                                        </div>
                                        <?php
                                         $p++;
                                         }
                                        ?>
                                    </div>
                                    <hr>
                                    <hr>
                                <h4><font color="red">Insurance Tie ups</font></h4>
                                    <div class="row" style="margin-left: 4px;margin-bottom: 13px;">
                                        <span style="font-size: 16px;">Check All <input type="checkbox" id="insurance_all" <?= $insurance_all_checked;?>></span>
                                    </div>
                                    <label id="err_msg_shop_insurance" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;padding-bottom: 20px;"></label>
                                    <div class="row" style="margin-left: 4px;">
                                        <?php
                                        $z = 0;
                                        foreach($result_insurance_company_arry as $insurance_arry_key => $insurance_arry_value){
                                        ?>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label><?= $insurance_arry_value?></label>
                                                <input <?= !empty($result_insurance_arry) ? in_array($insurance_arry_key, $result_insurance_arry) ? 'checked' : '' : ''; ?> type="checkbox" name="insurance_<?= $z?>" class="insurance" value="<?= $insurance_arry_key?>">
                                            </div>
                                        </div>
                                        <?php
                                         $z++;
                                         }
                                        ?>
                                    </div>
                                    <hr>
                                    <hr>
                                <h4><font color="red">Shop Amenities</font></h4>
                                    <div class="row" style="margin-left: 4px;margin-bottom: 13px;">
                                        <span style="font-size: 16px;">Check All <input type="checkbox" id="amenities_all" <?= $amenities_all_checked;?>></span>
                                    </div>
                                    <label id="err_msg_shop_amenities" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;padding-bottom: 20px;"></label>
                                    <div class="row" style="margin-left: 4px;">
                                        <?php
                                        $t = 0;
                                        foreach($result_shop_amenities_arry as $amenities_arry_key => $amenities_arry_value){
                                        ?>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label><?= $amenities_arry_value?></label>
                                                <input type="checkbox" <?= !empty($result_amenities_arry) ? in_array($amenities_arry_key, $result_amenities_arry) ? 'checked' : '' : ''; ?> class="amenities" name="amenities_<?= $t?>" value="<?= $amenities_arry_key?>">
                                            </div>
                                        </div>
                                         <?php
                                         $t++;
                                         }
                                        ?>
                                    </div>
                                    <hr>
                                    <hr>
                                <h4><font color="red">Multi Brand</font></h4>
                                    <div class="row" style="margin-left: 4px;margin-bottom: 13px;">
                                        <span style="font-size: 16px;">Check All <input type="checkbox" id="brand_all" <?= $brand_all_checked;?>></span>
                                    </div>
                                    <label id="err_msg_brand" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;padding-bottom: 20px;"></label>
                                    <div class="row" style="margin-left: 4px;">
                                        <?php
                                        $q = 0;
                                        foreach($result_brand_master_arry as $brand_arry_key => $brand_arry_value){
                                        ?>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label><?= $brand_arry_value?></label>
                                                <input <?= !empty($result_brand_arry) ? in_array($brand_arry_key, $result_brand_arry) ? 'checked' : '' : ''; ?> type="checkbox" class="brand" name="brand_<?= $q?>" value="<?= $brand_arry_key?>">
                                            </div>
                                        </div>
                                        <?php
                                         $q++;
                                         }
                                        ?>
                                    </div>
                                    
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="frm" value="1">
                                <input type="hidden" name="mode" id="mode" value="<?php echo (isset($_GET['id']) ? 'edit' : 'add' )?>">
                                <?php
                                    if(isset($_GET['id'])){
                                ?>
                                    <input type="hidden" name="id" id="id" value="<?= $_GET['id'] ?>">
                                <?php
                                    }
                                ?>
                                <button type="submit" class="btn btn-primary" onclick="return validate_dealer_info()" id="submit"><?php echo (isset($_GET['id']) ? 'Update' : 'Add' )?></button>
                                <a href="index.php" class="btn bg-maroon margin">cancel</a>
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;"></label>                              
                            </div>
                        </form>
                    </div><!-- /.box -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
           <?php include_once 'footer.php';?>
           <script>
            /*window.onload = function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else { 
                    x.innerHTML = "Geolocation is not supported by this browser.";
                }
            };
            function showPosition(position) {
                console.log(position.coords.latitude);
                console.log(position.coords.longitude);
                //x.innerHTML = "Latitude: " + position.coords.latitude + 
                //"<br>Longitude: " + position.coords.longitude;
            }*/
            </script>
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div><!-- ./wrapper -->
    </body>
</html>
