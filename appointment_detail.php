<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$select_condition = '';

if(isset($_GET['global_serach']) && $_GET['global_serach']!== ''){
	$select_condition .= " AND `insurance_company` LIKE '%".addslashes($_GET['global_serach'])."%' ";
}

$select_insurance_company = "SELECT `id` , `insurance_company` FROM `tbl_mb_insurance_company_master` WHERE `is_active` = 'Y' ".$select_condition." ";
$result_insurance_company = $DBI->query($select_insurance_company);
$rows_insurance_company = $DBI->get_result($select_insurance_company);

 
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
			                      		<h3 class="timeline-header no-border"><b>Appointment Code:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Dealer Name:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-green"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>User Name:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-cube bg-orange"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Model Name:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-gray"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Fuel Type:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-yellow"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Date:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-clock-o bg-gray"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Time:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Category Type:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Service Type:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Repair Type:</b> 123</h3>
			                    	</div>
			                  	</li> 	
			                </ul>
						</div>
						<div class="col-xs-6">
							<ul class="timeline timeline-inverse">
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Pickup Drop:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Pickup Location:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Pickup Pincode:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Description:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Terms And Condition:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Appointment Status:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Appointment Status Changed Time:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-user bg-aqua"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Booked By:</b> 123</h3>
			                    	</div>
			                  	</li>
			                  	<li>
			                    	<i class="fa fa-clock-o bg-gray"></i>
									<div class="timeline-item">
			                      		<h3 class="timeline-header no-border"><b>Booking Time:</b> 123</h3>
			                    	</div>
			                  	</li>
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