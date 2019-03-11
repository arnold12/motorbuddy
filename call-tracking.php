<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");


$select_call_track = "SELECT 
        tc.id, tc.created_at,
        dm.dealer_code,dm.dealer_name,dm.dealer_name2,dm.mobile_no,bmm.brand_model_name as brand_name, bmm1.brand_model_name as model_name,ru.fname, ru.lname, ru.mobile as user_mobile_no, ru.address, ru.pin, ru.email
    FROM
        tbl_mb_track_call AS tc
            LEFT JOIN
        tbl_mb_delaer_master AS dm ON tc.dealer_id = dm.id
            LEFT JOIN
        tbl_mb_register_users AS ru ON tc.user_id = ru.id
            LEFT JOIN
        tbl_mb_brand_model_master AS bmm ON tc.brand_id = bmm.id
            LEFT JOIN
        tbl_mb_brand_model_master AS bmm1 ON tc.model_id = bmm1.id
    order by tc.id desc
    ";

$rows_call_track = $DBI->get_result($select_call_track);
 
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
                        View Call Tracking
                    </h1>
                </section>
				
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Call Tracking List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_call_track )){?>
								<tr>
								  <th>#</th>
								  <th>User Name</th>
								  <th>User Mobile</th>
								  <th>User Email</th>
								  <th>User Address</th>
								  <th>Brand</th>
								  <th>Model</th>
								  <th>Dealer Code</th>
								  <th>Dealer Name</th>
								  <th>Dealer Mobile</th>
								  <th>Call Date</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_call_track as $key => $value){
								?>
								<tr>
								  <td><?=$i?></td>
								  <td><?=$value['fname']." - ".$value['lname']?></td>
								  <td><?=$value['user_mobile_no']?></td>
								  <td><?=$value['email']?></td>
								  <td><?=$value['address']." - ".$value['pin']?></td>
								  <td><?=$value['brand_name']?></td>
								  <td><?=$value['model_name']?></td>
								  <td><?=$value['dealer_code']?></td>
								  <td><?=$value['dealer_name']." - ".$value['dealer_name2']?></td>
								  <td><?=$value['dealer_name']." - ".$value['mobile_no']?></td>
								  <td><?=$value['created_at']?></td>
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