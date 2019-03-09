<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$select_condition = '';

if(isset($_GET['global_serach']) && $_GET['global_serach']!== ''){
	$select_condition .= " AND (`name` LIKE '%".addslashes($_GET['global_serach'])."%' OR `type` LIKE '%".addslashes($_GET['global_serach'])."%')";
}

$select_service_repair = "SELECT `id` , `name`, `type` FROM `tbl_mb_booking_service_repair_master` WHERE `is_active` = 'Y' ".$select_condition." ";
$result_service_repair = $DBI->query($select_service_repair);
$rows_service_repair = $DBI->get_result($select_service_repair);
 
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
                        View Service Repair
                    </h1>
                </section>
				<div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_service_repair.php">Add Service Repair</a></div><br>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Service Repair List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							 <!-- /.Search Form start-->
							 <form class="form-horizontal" method="GET" action="view_services_repair.php">
															
								<div class="col-sm-3">
								 <input type="text" name="global_serach" id="global_serach" value="<?php if(isset($_GET['global_serach'])){ echo $_GET['global_serach'];}?>" class="form-control input-sm" placeholder="Global search">
								</div>
								
								<div class="col-sm-3">
									<input type="submit" name="submit" value="Search" class="btn btn-primary">
									<a href="view_services_repair.php" class="btn btn-default">Reset</a>
								</div>
						
							 </form>
							 <!-- /.Search Form end -->
							 <br><br>
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_service_repair )){?>
								<tr>
								  <th>Id</th>
								  <th>Name</th>
								  <th>Type</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_service_repair as $key => $value){
								?>
								<tr id="row_<?=$value['id']?>">
								  <td><?=$i?></td>
								  <td><?=$value['name']?></td>
								  <td><?=$value['type']?></td>
								  <td><a href="add_service_repair.php?id=<?=$value['id']?>">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="delete_service_repair(<?=$value['id']?>);">Delete</a></td>
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