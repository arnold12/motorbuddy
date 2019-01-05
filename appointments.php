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
	$where_condition .= "WHERE `dealer_id` = ".$delaer_id;
}


$select_condition = '';

if(isset($_GET['global_serach']) && $_GET['global_serach']!== ''){
	if($_SESSION['role'] == 'dealer'){
		$select_condition .= " AND";
	}
	if($_SESSION['role'] == 'superadmin'){
		$select_condition .= " Where";
	}
	$select_condition .= " (`appmt_code` LIKE '%".addslashes($_GET['global_serach'])."%'  OR  `fuel_type` LIKE '%".addslashes($_GET['global_serach'])."%'  OR `appmt_date` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_time` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_category_type` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_service_type` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_repair_type` LIKE '%".addslashes($_GET['global_serach'])."%' OR `appmt_status` LIKE '%".addslashes($_GET['global_serach'])."%')";
}

$select_dealer_appointment = "SELECT `id` , `dealer_id`, `user_id`, `appmt_code` , `fuel_type` , `appmt_category_type`, `appmt_service_type`, `appmt_date`, `appmt_time`, `appmt_status`  FROM `tbl_mb_dealer_appointment` ".$where_condition." ".$select_condition;
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
								 <input type="text" name="global_serach" id="global_serach" value="<?php if(isset($_GET['global_serach'])){ echo $_GET['global_serach'];}?>" class="form-control input-sm" placeholder="Global search">
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
								  <th>Code</th>
								  <?php
								  	if($_SESSION['role'] == 'superadmin'){
								  ?>
								  <th>Delaer Name</th>
								  <?php }?>
								  <th>Fuel Type</th>
								  <th>Category Type</th>
								  <th>Service Type</th>
								  <th>Date</th>
								  <th>Time</th>
								  <th>Status</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_dealer_appointment as $key => $value){
								?>
								<tr id="row_<?=$value['id']?>">
								  <td><?=$i?></td>
								  <td><?=$value['appmt_code']?></td>
								  <?php
								  	if($_SESSION['role'] == 'superadmin'){
								  		$dealerDtls = getDealerDtls($value['dealer_id']);
								  ?>
								  <td><?=$dealerDtls[0]['dealer_name']." ".$dealerDtls[0]['dealer_name2']?></td>
								  <?php }?>
								  <td><?=$value['fuel_type']?></td>
								  <td><?=$value['appmt_category_type']?></td>
								  <td><?=$value['appmt_service_type']?></td>
								  <td><?=date_wording($value['appmt_date'])?></td>
								  <td><?=$value['appmt_time']?></td>
								  <td><?=ucfirst($value['appmt_status'])?></td>
								  <td>
								  <a href="appointment_detail.php?id=<?=$value['id']?>">View</a>
								  <?php if( $value['appmt_status'] == 'verified') {?>
									  &nbsp;|&nbsp;<a href="#" onclick="appointment_action(<?=$value['id']?>, <?=$value['user_id']?>, 'confirmed');">Confirm</a>
									  	&nbsp;|&nbsp;
									  <a href="#" onclick="appointment_action(<?=$value['id']?>, <?=$value['user_id']?>, 'rejected');">Reject</a>
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