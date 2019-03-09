<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$dealer_name = '';
$user_name = '';
$brand_name = '';
$user_mobile = '';
$user_address = '';

if(isset($_GET['id']) && $_GET['id'] != ""){

    $select_appointment = "SELECT 
        da.id,da.appmt_code,da.brand_id,da.model_id,da.fuel_type,da.appmt_date,appmt_time,if(da.pickup_drop = 1 , 'Pickup and Drop', 'Self Delivered') as pickup_drop, da.pickup_location,IFNULL(da.pickup_pincode, '') as pickup_pincode,da.description,da.appmt_status,da.appmt_booking_time,da.appmt_service_pkg,da.appmt_repair_concern,da.dealer_id,
        dm.dealer_code,dm.dealer_name,dm.dealer_name2,dm.mobile_no,bmm.brand_model_name as brand_name, bmm1.brand_model_name as model_name,ru.fname, ru.lname, ru.mobile as user_mobile_no, ru.address, ru.pin, ru.email, pm.pkg_type_id, pm.pkg_price 
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
            LEFT JOIN
        tbl_mb_pkg_master AS pm ON da.appmt_service_pkg = pm.id
    where da.id = '".$_GET['id']."'";
	
	$rows_appointment = $DBI->get_result($select_appointment);

    if($rows_appointment[0]['appmt_repair_concern'] != ""){
        $select_repair_concern = "SELECT name FROM tbl_mb_booking_service_repair_master WHERE id IN (".$rows_appointment[0]['appmt_repair_concern'].")";;
        $rows_repair_concern = $DBI->get_result($select_repair_concern);

    }

    //echo "<pre>";print_r($rows_appointment);exit;

}

?>

<!DOCTYPE html>
<html>
    <head>
        <?php include_once('header_script.php'); ?>
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
                        Appointment Detail
                    </h1>
                </section>
				<section class="content">
					<div class="box box-info">
						<div class="box-body">
							<p>
								<label class="col-sm-2 col-md-2 control-label">Appointment Code : </label>
								<?= $rows_appointment[0]['appmt_code']?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">User Name : </label>
								<?= $rows_appointment[0]['fname']." - ".$rows_appointment[0]['lname']?>
							</p>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">User Mobile : </label>
                                <?= $rows_appointment[0]['user_mobile_no']?>
                            </p>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">User Email : </label>
                                <?= $rows_appointment[0]['email']?>
                            </p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">User Address : </label>
								<?= $rows_appointment[0]['address']." - ".$rows_appointment[0]['pin']?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">Brand/Model - Fuel Type : </label>
								<?= $rows_appointment[0]['brand_name']."-".$rows_appointment[0]['model_name']." / ".$rows_appointment[0]['fuel_type']?>
							</p>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Dealer Code : </label>
                                <?= $rows_appointment[0]['dealer_code']?>
                            </p>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Dealer Name : </label>
                                <?= $rows_appointment[0]['dealer_name']." ".$rows_appointment[0]['dealer_name2']?>
                            </p>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Dealer Mobile : </label>
                                <?= $rows_appointment[0]['dealer_name']." ".$rows_appointment[0]['mobile_no']?>
                            </p>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Pickup & Drop : </label>
                                <?= $rows_appointment[0]['pickup_drop']?>
                            </p>
                            <?php if($rows_appointment[0]['pickup_drop'] == 'Pickup and Drop' ){?>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Pickup Address : </label>
                                <?= $rows_appointment[0]['pickup_location']." , ".$rows_appointment[0]['pickup_pincode']?>
                            </p>
                            <?php }?>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Service Package : </label>
                                <?= $pkg_type_arry[$rows_appointment[0]['pkg_type_id']]?>
                            </p>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Package Price: </label>
                                <?= $rows_appointment[0]['pkg_price']?>
                            </p>

                            <?php if($rows_appointment[0]['appmt_repair_concern'] != ""){ ?>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Repair Concern : </label>
                                <?php 
                                    foreach ($rows_repair_concern as $concern_key => $concern_value) {
                                        echo $concern_value['name']." , ";
                                    }
                                ?>
                            </p>
                            <?php }?>
                            <p>
                                <label class="col-sm-2 col-md-2 control-label">Appointment Status : </label>
                                <?= $rows_appointment[0]['appmt_status']?>
                            </p>


						</div>
					</div>
				</section>
            </div><!-- /.content-wrapper -->
			<?php include_once 'footer.php';?>
  
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div><!-- ./wrapper -->

    </body>
</html>