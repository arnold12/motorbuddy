<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$select_condition = '';

if(isset($_GET['global_serach']) && $_GET['global_serach']!== ''){
	$select_condition .= " AND `pkg_group_name` LIKE '%".addslashes($_GET['global_serach'])."%' ";
}

$select_pkges = "SELECT `id` , `pkg_group_name` , `pkg_type_id` , `pkg_price`, `status`  FROM `tbl_mb_pkg_master` WHERE 1 ".$select_condition." ";
$result_pkges = $DBI->query($select_pkges);
$rows_pkges = $DBI->get_result($select_pkges);

$final_pkg_details = array();
foreach ($rows_pkges as $key => $value) {
 	$final_pkg_details[$value['pkg_group_name']][] = $value;
} 

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
                        View Service Packages
                    </h1>
                </section>
				<div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_pkg_group.php">Add New Package Group</a></div><br>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Service Packages List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							 <!-- /.Search Form start-->
							 <form class="form-horizontal" name="view_snakeinfo_form" id="view_snakeinfo_form" method="GET" action="view_pkg.php">
															
								<div class="col-sm-3">
								 <input type="text" name="global_serach" id="global_serach" value="<?php if(isset($_GET['global_serach'])){ echo $_GET['global_serach'];}?>" class="form-control input-sm" placeholder="Global search">
								</div>
								
								<div class="col-sm-3">
									<input type="submit" name="submit" value="Search" class="btn btn-primary">
									<a href="view_pkg.php" class="btn btn-default">Reset</a>
								</div>
						
							 </form>
							 <!-- /.Search Form end -->
							 <br><br>
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($final_pkg_details )){?>
								<tr>
								  <th>Id</th>
								  <th>Pkg Group</th>
								  <th>Pkg Details</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								foreach($final_pkg_details as $key => $value){
								?>
								<tr>
								  <td style="text-align: center;vertical-align: middle;" align="center"><?=$i?></td>
								  <td style="text-align: center;vertical-align: middle;"><?=$key?></td>
								  <td style="text-align: center;vertical-align: middle;">
								  	<table class="table table-bordered">
								  		<?php
								  			if( $i == 1 ){

								  		?>
								  			<tr>
								  				<th>Pkg Type</th>
								  				<th>Pkg Price</th>
								  			</tr>

								  		<?php }?>
								  		<?php
								  			foreach ($value as $pkg_key => $pkg_val) {

								  		?>
								  		
								  			<tr>
								  				<td><?=$pkg_type_arry[$pkg_val['pkg_type_id']]?></td>
								  				<td><?=$pkg_val['pkg_price']?></td>
								  			</tr>
								  		<?php				
								  			}
								  		?>
								  	</table>
								  </td>
								  <td style="text-align: center;vertical-align: middle;">
								  	  <?php if($value[0]['status'] == 'Active') {?>
									  <a href="add_pkg_group.php?pkg_group_name=<?=$key?>">Edit</a>&nbsp;|&nbsp;
									  <a href="#" onclick="delete_pkg('<?=$key?>','Inactive');">Disable</a>
									  <?php } else {?>
									  <a style="color: red" href="#" onclick="delete_pkg('<?=$key?>','Active');">Enable</a>
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