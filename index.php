<?php
require_once 'config.php';
if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$select_condition = '';

if(isset($_GET['global_serach']) && $_GET['global_serach']!== ''){
	$select_condition .= " AND `dealer_code` LIKE '%".addslashes($_GET['global_serach'])."%'  OR  `dealer_name` LIKE '%".addslashes($_GET['global_serach'])."%'  OR `dealer_name2` LIKE '%".addslashes($_GET['global_serach'])."%' OR `address` LIKE '%".addslashes($_GET['global_serach'])."%' OR `landmark` LIKE '%".addslashes($_GET['global_serach'])."%' OR `city` LIKE '%".addslashes($_GET['global_serach'])."%' OR `state` LIKE '%".addslashes($_GET['global_serach'])."%' OR `pincode` LIKE '%".addslashes($_GET['global_serach'])."%' OR `mobile_no` LIKE '%".addslashes($_GET['global_serach'])."%' OR `telephone_no` LIKE '%".addslashes($_GET['global_serach'])."%'";
}

$select_dealer_info = "SELECT `tbl_mb_delaer_master`.`id` , `dealer_code` , `dealer_name` , `dealer_name2`, `landmark` , `city`, `state`, `pincode`, `mobile_no`, `telephone_no`, `status`, `username`, `password` FROM `tbl_mb_delaer_master` LEFT JOIN `tbl_mb_user` ON `tbl_mb_delaer_master`.`dealer_code` = `tbl_mb_user`.`username` ".$select_condition." ORDER BY status ASC, id desc";
$result_dealer_info = $DBI->query($select_dealer_info);
$rows_dealer_info = $DBI->get_result($select_dealer_info);

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
                        View Dealer
                    </h1>
                </section>
				<div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_dealer_info.php">Add Dealer Information</a></div><br>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Dealer List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							 <!-- /.Search Form start-->
							 <form class="form-horizontal" method="GET" action="index.php">
															
								<div class="col-sm-3">
								 <input type="text" name="global_serach" id="global_serach" value="<?php if(isset($_GET['global_serach'])){ echo $_GET['global_serach'];}?>" class="form-control input-sm" placeholder="Global search">
								</div>
								
								<div class="col-sm-3">
									<input type="submit" name="submit" value="Search" class="btn btn-primary">
									<a href="index.php" class="btn btn-default">Reset</a>
								</div>
						
							 </form>
							 <!-- /.Search Form end -->
							 <br><br>
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_dealer_info )){?>
								<tr>
								  <th>Id</th>
								  <th>Dealer Code</th>
								  <th>Delaer Name1</th>
								  <th>Delaer Name2</th>
								  <th>Landmark</th>
								  <th>City</th>
								  <th>State</th>
								  <th>Pincode</th>
								  <th>Mobile Number</th>
								  <th>Telephone Number</th>
								  <th>Username</th>
								  <th>Password</th>
								  <th>Status</th>
								  <th>Action</th>
								  <th>Dealer Ratings</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_dealer_info as $key => $value){
								?>
								<tr id="row_<?=$value['id']?>">
								  <td><?=$i?></td>
								  <td>
								  <?php if($value['status'] == "Inactive"){?>
								  	<font color="red"><?=$value['dealer_code']?></font>
								  <?php } else {?>
								  	<?=$value['dealer_code']?>
								  <?php }?>
								  </td>
								  <td><?=$value['dealer_name']?></td>
								  <td><?=$value['dealer_name2']?></td>
								  <td><?=$value['landmark']?></td>
								  <td><?=$value['city']?></td>
								  <td><?=$value['state']?></td>
								  <td><?=$value['pincode']?></td>
								  <td><?=$value['mobile_no']?></td>
								  <td><?=$value['telephone_no']?></td>
								  <td><?=$value['username']?></td>
								  <td><?=$value['password']?></td>
								  <td><?=$value['status']?></td>
								  <td>
								  <?php if($value['status'] == "Inactive"){?>
								  <a href="#" onclick="delete_dealer_info(<?=$value['id']?>, 'Active');"><font color="red">Enable</font></a>
								  <?php } else {?>
								  <a href="add_dealer_info.php?id=<?=$value['id']?>">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="delete_dealer_info(<?=$value['id']?>, 'Inactive');">Disable</a>
								  <?php }?>
								  </td>
								  <td>
								  <?php if($value['status'] == "Active"){?>	  
								  <a href="add_dealer_ratings.php?dealer_id=<?=$value['id']?>">Add Ratings</a>
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