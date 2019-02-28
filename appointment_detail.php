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

	$select_appointment = "SELECT * FROM `tbl_mb_dealer_appointment` WHERE (`id` = '".$_GET['id']."')";
	$rows_appointment = $DBI->get_result($select_appointment);

	if(!empty($rows_appointment[0]['dealer_id'])){
		$select_dealer = "SELECT `dealer_name`, `dealer_code`, `dealer_name2`
	    FROM `tbl_mb_delaer_master`
	    WHERE (`id` = '".$rows_appointment[0]['dealer_id']."')";
	    $rows_dealer = $DBI->get_result($select_dealer);
	}

	if(!empty($rows_appointment[0]['user_id'])){
		$select_user = "SELECT `fname`, `lname`, `mobile`, `address`, `email`
	    FROM `tbl_mb_register_users`
	    WHERE (`id` = '".$rows_appointment[0]['user_id']."')";
	    $rows_user = $DBI->get_result($select_user);
	}

	if(!empty($rows_appointment[0]['brand_id'])){
		$select_brand = "SELECT `brand_model_name`
	    FROM `tbl_mb_brand_model_master`
	    WHERE `id` IN ('".$rows_appointment[0]['brand_id']."', '".$rows_appointment[0]['model_id']."') ";
	    $rows_brand = $DBI->get_result($select_brand);
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
								<label class="col-sm-2 col-md-2 control-label">Dealer Name : </label>
								<?= $rows_dealer[0]['dealer_name']." - ".$rows_dealer[0]['dealer_name2']?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">Dealer Code : </label>
								<?= $rows_dealer[0]['dealer_name']." - ".$rows_dealer[0]['dealer_code']?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">User Name : </label>
								<?= $rows_user[0]['fname']." ".$rows_user[0]['lname']?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">User Email : </label>
								<?= $rows_user[0]['email']?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">User Mobile : </label>
								<?= $rows_user[0]['mobile']?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">Brand - Model : </label>
								<?= $rows_brand[0]['brand_model_name']."-".$rows_brand[1]['brand_model_name']." : ".$rows_appointment[0]['fuel_type'] ?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">Appointment Date - Time : </label>
								<?= $rows_appointment[0]['appmt_date']." : ".$rows_appointment[0]['appmt_time'] ?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">Service Package: </label>
								<?= $rows_appointment[0]['appmt_service_pkg'] ?>
							</p>
							<p>
								<label class="col-sm-2 col-md-2 control-label">Repair Concern: </label>
								<?= $rows_appointment[0]['appmt_repair_concern'] ?>
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