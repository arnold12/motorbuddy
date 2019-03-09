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