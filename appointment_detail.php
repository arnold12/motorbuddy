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
$brand_model_name = '';

if(isset($_GET['id']) && $_GET['id'] != ""){

$select_appointment = "SELECT * FROM `tbl_mb_dealer_appointment` WHERE (`id` = '".$_GET['id']."')";
$rows_appointment = $DBI->get_result($select_appointment);

if(!empty($rows_appointment[0]['dealer_id'])){
	$select_dealer = "SELECT `dealer_name`
    FROM `tbl_mb_delaer_master`
    WHERE (`id` = '".$rows_appointment[0]['dealer_id']."')";
    $rows_dealer = $DBI->get_result($select_dealer);
    $dealer_name = $rows_dealer[0]['dealer_name'];
}

if(!empty($rows_appointment[0]['user_id'])){
	$select_user = "SELECT `username`
    FROM `tbl_mb_user`
    WHERE (`id` = '".$rows_appointment[0]['user_id']."')";
    $rows_user = $DBI->get_result($select_user);
    $user_name = $rows_user[0]['username'];
}

if(!empty($rows_appointment[0]['brand_id'])){
	$select_brand = "SELECT `brand_model_name`
    FROM `tbl_mb_brand_model_master`
    WHERE (`id` = '".$rows_appointment[0]['brand_id']."')";
    $rows_brand = $DBI->get_result($select_brand);
    $brand_name = $rows_brand[0]['brand_model_name'];
}

if(!empty($rows_appointment[0]['model_id'])){
	$select_model_brand = "SELECT `brand_model_name`
    FROM `tbl_mb_brand_model_master`
    WHERE (`id` = '".$rows_appointment[0]['model_id']."')";
    $rows_model_brand = $DBI->get_result($select_model_brand);
    $brand_model_name = $rows_model_brand[0]['brand_model_name'];
}


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
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		  <!-- Ionicons -->
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">	
	    <!-- DataTables -->
		<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
        <!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
		 <!-- jQuery UI 1.11.4 -->
		<script src="dist/js/jquery-ui.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
		<!-- AdminLTE App -->
		<script src="dist/js/app.min.js"></script>
		<script src="plugins/datatables/jquery.dataTables.min.js"></script>		
		<script src="dist/js/common.js"></script>
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
					<div class="row">
						<div class="col-xs-6">
							<ul class="timeline timeline-inverse">
			                  	<li>
			                    	<i class="fa fa-code bg-red"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Appointment Code:</b> <?= $rows_appointment[0]['appmt_code']?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Dealer Name:</b> <?= $dealer_name?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-green"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>User Name:</b> <?= $user_name?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-btc bg-blue"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Brand Name:</b> <?= $brand_name?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-cube bg-orange"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Model Name:</b> <?= $brand_model_name?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-gray"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Fuel Type:</b> <?= $rows_appointment[0]['fuel_type']?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	if(!empty($rows_appointment[0]['appmt_booking_time'])){
			                  	?>
			                  	<li>
			                    	<i class="fa fa-calendar bg-blue"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Date:</b> <?= date_wording($rows_appointment[0]['appmt_date'])?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	}
			                  	?>
			                  	<li>
			                    	<i class="fa fa-clock-o bg-pink"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Time:</b> <?= $rows_appointment[0]['appmt_time']?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-adjust bg-red"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Category Type:</b> <?= $rows_appointment[0]['appmt_category_type']?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-bullhorn bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Service Type:</b> <?= $rows_appointment[0]['appmt_service_type']?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-wrench bg-green"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Repair Type:</b> <?= $rows_appointment[0]['appmt_repair_type']?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-car bg-orange"></i>
									<div class="timeline-item">
										<?php
										$pickup_drop = 'Self Delivered';
										if($rows_appointment[0]['pickup_drop'] == 1){
											$pickup_drop = 'Pickup and Drop';
										}
										?>
			                      		<h3 class="timeline-header no-border"><b>Pickup Drop:</b> <?= $pickup_drop?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-map-marker bg-gray"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Pickup Location:</b> <?= $rows_appointment[0]['pickup_location']?></h3>
			                    	</div>
			                  	</li>	
			                </ul>
						</div>
						<div class="col-xs-6">
							<ul class="timeline timeline-inverse">
								<li>
			                    	<i class="fa fa-map-pin bg-blue"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Pickup Pincode:</b> <?= $rows_appointment[0]['pickup_pincode']?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-list-alt bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Description:</b> <?= $rows_appointment[0]['description']?></h3>
			                    	</div>
			                  	</li> 
			                  	<li>
			                    	<i class="fa fa-list-alt bg-red"></i>
									<div class="timeline-item">
										<?php
										$terms_n_condition = 'Not Accepted';
										if($rows_appointment[0]['terms_n_condition'] == 1){
											$terms_n_condition = 'Accepted';
										}
										?>
			                      		<h3 class="timeline-header no-border"><b>Terms And Condition:</b> <?= $terms_n_condition?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	if(!empty($rows_appointment[0]['appmt_status'])){
			                  	?>
			                  	<li>
			                    	<i class="fa fa-info-circle bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Appointment Status:</b> <?= ucfirst($rows_appointment[0]['appmt_status'])?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	}
			                  	?>
			                  	<?php
			                  	if(!empty($rows_appointment[0]['appmt_status_change_time'])){
			                  	?>
			                  	<li>
			                    	<i class="fa fa-clock-o bg-green"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Appointment Status Changed Time:</b> <?= date_time_wording($rows_appointment[0]['appmt_status_change_time'])?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	}
			                  	?>
			                  	<li>
			                    	<i class="fa fa-user bg-orange"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Booked By:</b> <?= $rows_appointment[0]['appmt_booked_by']?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	if(!empty($rows_appointment[0]['appmt_booking_time'])){
			                  	?>
			                  	<li>
			                    	<i class="fa fa-clock-o bg-gray"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Booking Time:</b> <?= date_time_wording($rows_appointment[0]['appmt_booking_time'])?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	}
			                  	?>
			                  	<li>
			                    	<i class="fa fa-key bg-pink"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>OTP:</b> <?= $rows_appointment[0]['otp']?></h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-adjust bg-red"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>OTP Send Count:</b> <?= $rows_appointment[0]['otp_sent_count']?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	if(!empty($rows_appointment[0]['otp_sent_date'])){
			                  	?>
			                  	<li>
			                    	<i class="fa fa-calendar bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>OTP Send Date:</b> <?= $rows_appointment[0]['otp_sent_date']?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	}
			                  	?>
			                  	<li>
			                  		<?php
										$is_otp_verify = 'No';
										if($rows_appointment[0]['is_otp_verify'] == 'Y'){
											$is_otp_verify = 'Yes';
										}
									?>
			                    	<i class="fa fa-bullhorn bg-green"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>OTP Verified?:</b> <?= $is_otp_verify?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	if(!empty($rows_appointment[0]['otp_verification_date'])){
			                  	?>
			                  	<li>
			                    	<i class="fa fa-calendar bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>OTP Verification Date:</b> <?= $rows_appointment[0]['otp_verification_date']?></h3>
			                    	</div>
			                  	</li>
			                  	<?php
			                  	}
			                  	?>
			                </ul>
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