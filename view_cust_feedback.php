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
		<?php include_once('header_script.php'); ?>
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