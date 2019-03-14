<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$select_condition = 'where 1';

if(isset($_GET['global_serach']) && $_GET['global_serach']!== ''){
	$select_condition .= " AND `email` LIKE '%".addslashes($_GET['global_serach'])."%'  OR  `password` LIKE '%".addslashes($_GET['global_serach'])."%'  OR `address` LIKE '%".addslashes($_GET['global_serach'])."%' OR `pin` LIKE '%".addslashes($_GET['global_serach'])."%' OR `gender` LIKE '%".addslashes($_GET['global_serach'])."%' OR `status` LIKE '%".addslashes($_GET['global_serach'])."%' OR `created_date` LIKE '%".addslashes($_GET['global_serach'])."%' OR `fname` LIKE '%".addslashes($_GET['global_serach'])."%' OR `lname` LIKE '%".addslashes($_GET['global_serach'])."%' OR `mobile` LIKE '%".addslashes($_GET['global_serach'])."%'";
}

$select_register_users = "SELECT `email`, `password`, `address`, `pin`, `gender`, `status`, `created_date`, `fname`, `lname`, `mobile`, `chkd_terms_and_condn` FROM tbl_mb_register_users ".$select_condition." ORDER BY id DESC ";
$rows_register_users = $DBI->get_result($select_register_users);

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
                        View Register Users
                    </h1>
                </section>
				
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Register Users List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">

							<!-- /.Search Form start-->
							<form class="form-horizontal" method="GET" action="registered-users.php">
								<div class="col-sm-3">
								 <input type="text" name="global_serach" id="global_serach" value="<?php if(isset($_GET['global_serach'])){ echo $_GET['global_serach'];}?>" class="form-control input-sm" placeholder="Global search">
								</div>
								<div class="col-sm-3">
									<input type="submit" name="submit" value="Search" class="btn btn-primary">
									<a href="registered-users.php" class="btn btn-default">Reset</a>
								</div>
							</form>
							<!-- /.Search Form end -->

							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_register_users )){?>
								<tr>
								  <th>#</th>
								  <th>First Name</th>
								  <th>Last Name</th>
								  <th>Mobile</th>
								  <th>Email</th>
								  <th>Password</th>
								  <th>Address</th>
								  <th>PIN</th>
								  <th>Gender</th>
								  <th>Status</th>
								  <th>Created Date</th>
								  <th>Checked Term and Condition</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_register_users as $key => $value){
									$term = "No";
									if($value['chkd_terms_and_condn'] == 'Y'){
										$term = "Yes";
									}
								?>
								<tr>
								  <td><?=$i?></td>
								  <td><?=$value['fname']?></td>
								  <td><?=$value['lname']?></td>
								  <td><?=$value['mobile']?></td>
								  <td><?=$value['email']?></td>
								  <td><?=$value['password']?></td>
								  <td><?=$value['address']?></td>
								  <td><?=$value['pin']?></td>
								  <td><?=$value['gender']?></td>
								  <td><?=$value['status']?></td>
								  <td><?=$value['created_date']?></td>
								  <td><?=$term?></td>
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