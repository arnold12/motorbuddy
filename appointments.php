<?php
require_once 'config.php';
if (!isUserLoggedIn() || ($_SESSION['role'] != 'dealer' && $_SESSION['role'] != 'superadmin')) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$where_condition = '';

if($_SESSION['role'] == 'dealer'){
	$delaer_sql = "SELECT id FROM tbl_mb_delaer_master WHERE dealer_code = '".$_SESSION['username']."' LIMIT 1";	
	$delaer_result = $DBI->query($delaer_sql);
	$delaer_id = 0;
	if(mysql_num_rows($delaer_result) > 0){
		$delaer_row = $DBI->get_result($delaer_sql);
		$delaer_id = $delaer_row[0]['id'];
	}
	$where_condition .= " AND da.dealer_id = ".$delaer_id;
}


if(isset($_GET['booking_status']) && $_GET['booking_status']!== ''){

	//$select_condition .= " (`appmt_code` LIKE '%".addslashes($_GET['global_serach'])."%'  OR  `fuel_type` LIKE '%".addslashes($_GET['global_serach'])."%'  OR `appmt_date` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_time` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_category_type` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_service_type` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_repair_type` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_status` LIKE '%".addslashes($_GET['global_serach'])."%')";
	$booking_status = mysql_real_escape_string($_GET['booking_status']);
	$where_condition .= " AND da.appmt_status = '".$booking_status."' ";
} else {
	$where_condition .= " AND da.appmt_status = 'verified' ";
}

$select_dealer_appointment = "SELECT da.id, da.appmt_code, da.dealer_id, da.user_id, da.fuel_type, da.appmt_date, da.appmt_time, da.appmt_service_pkg, da.appmt_repair_concern, da.pickup_drop, da.pickup_location, da.pickup_pincode, da.appmt_status, da.pickup_otp_sent_count, dm.dealer_code, dm.dealer_name, dm.dealer_name2, dm.mobile_no as dealer_mobile_no, ru.fname, ru.lname, ru.mobile as user_mobile_no, ru.address, ru.pin,bmm.brand_model_name as brand_name, bmm1.brand_model_name as model_name,pm.pkg_type_id, pm.pkg_price 
	FROM 
		tbl_mb_dealer_appointment as da 
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
	    WHERE 1
		".$where_condition." ORDER BY da.appmt_booking_time DESC ";
$rows_dealer_appointment = $DBI->get_result($select_dealer_appointment);


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
                        View Appointments
                    </h1>
                </section>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Appointments List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							 <!-- /.Search Form start-->
							 <form class="form-horizontal" method="GET" action="appointments.php">
															
								<div class="col-sm-3">
									<select name="booking_status" class="form-control input-sm">
										<option value="verified" <?php if(isset($_GET['booking_status']) && $_GET['booking_status'] == 'verified'){ echo "selected";}?> >verified</option>
										<option value="pending" <?php if(isset($_GET['booking_status']) && $_GET['booking_status'] == 'pending'){ echo "selected";}?> >pending</option>
										<option value="confirmed" <?php if(isset($_GET['booking_status']) && $_GET['booking_status'] == 'confirmed'){ echo "selected";}?> >confirmed</option>
										<option value="cancelled" <?php if(isset($_GET['booking_status']) && $_GET['booking_status'] == 'cancelled'){ echo "selected";}?> >cancelled</option>
										<option value="rejected" <?php if(isset($_GET['booking_status']) && $_GET['booking_status'] == 'rejected'){ echo "selected";}?> >rejected</option>
										<option value="closed" <?php if(isset($_GET['booking_status']) && $_GET['booking_status'] == 'closed'){ echo "selected";}?> >closed</option>
									</select>
								</div>
								
								<div class="col-sm-3">
									<input type="submit" name="submit" value="Search" class="btn btn-primary">
									<a href="appointments.php" class="btn btn-default">Reset</a>
								</div>
						
							 </form>
							 <!-- /.Search Form end -->
							 <br><br>
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_dealer_appointment )){?>
								<tr>
								  <th>Id</th>
								  <th>Booking Code</th>
								  <th>User Name/Mobile</th>
								  <th>User Address</th>
								  <?php
								  	if($_SESSION['role'] == 'superadmin'){
								  ?>
								  <th>Delaer Name/Mobile</th>
								  <?php }?>
								  <th>Brand/Model/Fuel Type</th>
								  <th>Pickup & Drop</th>
								  <th>Pickup Location</th>
								  <th>Service Package</th>
								  <th>Repair Concern</th>
								  <th>Appmt Date/Time</th>
								  <th>Status</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_dealer_appointment as $key => $value){

									if($value['appmt_repair_concern'] != ""){
										$select_repair_concern = "SELECT name FROM tbl_mb_booking_service_repair_master WHERE id IN (".$value['appmt_repair_concern'].")";;
										$rows_repair_concern = $DBI->get_result($select_repair_concern);

									}
								?>
								<tr id="row_<?=$value['id']?>">
								  <td><?=$i?></td>
								  <td><?=$value['appmt_code']?></td>
								  <td><?=$value['fname']." ".$value['lname']." - ".$value['user_mobile_no']?></td>
								  <td><?=$value['address']." , ".$value['pin']?></td>
								  <?php
								  	if($_SESSION['role'] == 'superadmin'){
								  ?>
								  <td><?=$value['dealer_code']." : ".$value['dealer_name']." ".$value['dealer_name2']?></td>
								  <?php }?>
								  <td><?=$value['brand_name']. " - ".$value['model_name']." ".$value['fuel_type']?></td>
								  <td><?=($value['pickup_drop'] == 1 ? 'pickup and drop' : 'self delivered')?></td>
								  <td><?=($value['pickup_drop'] == 1 ? $value['pickup_location']." , ".$value['pickup_pincode'] : '-')?></td>
								  <td><?=(!empty($value['pkg_type_id']) ? $pkg_type_arry[$value['pkg_type_id']]." - ".$value['pkg_price'] : '' ) ?></td>
								  <td>
								  <?php if($value['appmt_repair_concern'] != ""){
								  	foreach ($rows_repair_concern as $concern_key => $concern_value) {
								  		echo $concern_value['name']." , ";
								  	}
								  }
								  ?>
								  </td>
								  <td><?=date_wording($value['appmt_date'])."<br>".$value['appmt_time']?></td>
								  <td><?=ucfirst($value['appmt_status'])?></td>
								  <td>
								  <a href="appointment_detail.php?id=<?=$value['id']?>">View</a>
								  <?php if( $value['appmt_status'] == 'verified') {?>
									  &nbsp; || &nbsp;<a href="#" onclick="appointment_action(<?=$value['id']?>, <?=$value['user_id']?>, 'confirmed');">Confirm</a>
									  	&nbsp; || &nbsp;
									  <a href="#" onclick="appointment_action(<?=$value['id']?>, <?=$value['user_id']?>, 'rejected');">Reject</a>
								  <?php }?>

								  <?php if( $value['appmt_status'] == 'confirmed') {

								  	$pickeup_persons = get_pickup_persons($value['dealer_id']);

								  ?>
								  		&nbsp; || &nbsp;
								  		<?php
								  			if( empty($pickeup_persons)){
								  		?>

								  			You dont have pickup persons, please contact to seraj lopese.

								  		<?php } else {?>

								  			<?php 
								  				if( $value['pickup_otp_sent_count'] < 3 ){
								  			?>
								  			<select id="pickeup_person">
								  				<option value="">Select Pickup Person</option>
								  				<?php
								  					foreach ($pickeup_persons as $pp_id => $pp_value) {
								  						echo "<option value='".$pp_value['id']."'>".$pp_value['person_full_name']."</option>";
								  					}
								  				?>
								  			</select>

								  			<a href="#" onclick="send_pickeup_otp(<?=$value['id']?>, <?=$value['user_id']?>);">Send Pickup OTP</a>

								  			&nbsp; || &nbsp;
								  			<?php } ?>

								  			

								  			<a href="#" onclick="appointment_action(<?=$value['id']?>, <?=$value['user_id']?>, 'closed');">Closed</a>

								  		<?php }?>
								  <?php }?>
								  </td>
								  
								</tr>
								</tr>
								<?php
								$i++;
								}
								} else {
									echo "<div style='margin-left:10px'>No Record Found</div>";
								}
								?>
							  </tbody>
							  </table>
							</div><!-- /.box-body -->
						  </div><!-- /.box -->
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