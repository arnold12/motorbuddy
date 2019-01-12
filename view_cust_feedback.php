<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");



$select_feedback = "SELECT tbl_mb_feedback.*, tbl_mb_register_users.fname, tbl_mb_register_users.lname,tbl_mb_register_users.email FROM tbl_mb_feedback left join tbl_mb_register_users on tbl_mb_feedback.userid = tbl_mb_register_users.id ORDER BY tbl_mb_feedback.id DESC ";
$result_feedback = $DBI->query($select_feedback);
$rows_feedback = $DBI->get_result($select_feedback);
 
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
                        View Custmore Feedback
                    </h1>
                </section>
				
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Feedback List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_feedback )){?>
								<tr>
								  <th>#</th>
								  <th>Name</th>
								  <th>Email</th>
								  <th>Date</th>
								  <th>Feedback</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_feedback as $key => $value){
									$row_color = "";
									if($value['is_read'] == 'Y'){
										$row_color = "red";
									}
								?>
								<tr id="row_<?=$value['id']?>" style="background-color: <?=$row_color?>;">
								  <td><?=$i?></td>
								  <td><?=$value['fname']." ".$value['lname']?></td>
								  <td><?=$value['email']?></td>
								  <td><?=$value['created_date']?></td>
								  <td><?=$value['feedback']?></td>
								  <td>
								  	<?php 
								  		if($value['is_read'] == 'N'){
								  		
								  	?>
								  		<a href="#" onclick="read_feedback(<?=$value['id']?>);">Read</a>
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