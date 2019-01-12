<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");



$select_contactus = "SELECT * FROM tbl_mb_contact_us ORDER BY id DESC ";
$result_contactus = $DBI->query($select_contactus);
$rows_contactus = $DBI->get_result($select_contactus);
 
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
                        View Custmore Contact Us
                    </h1>
                </section>
				
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Contact Us List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_contactus )){?>
								<tr>
								  <th>#</th>
								  <th>Email</th>
								  <th>Mobile</th>
								  <th>Date</th>
								  <th>Contact Us</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_contactus as $key => $value){
									$row_color = "";
									if($value['is_read'] == 'Y'){
										$row_color = "red";
									}
								?>
								<tr id="row_<?=$value['id']?>" style="background-color: <?=$row_color?>;">
								  <td><?=$i?></td>
								  <td><?=$value['emailid']?></td>
								  <td><?=$value['mobile']?></td>
								  <td><?=$value['created_date']?></td>
								  <td><?=$value['contact_text']?></td>
								  <td>
								  	<?php 
								  		if($value['is_read'] == 'N'){
								  		
								  	?>
								  		<a href="#" onclick="read_contact_us(<?=$value['id']?>);">Read</a>
								  	<?php } else {?>
								  		-
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