<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$select_condition = '';

if(isset($_GET['global_serach']) && $_GET['global_serach']!== ''){
	$select_condition .= " AND (`rating` LIKE '%".addslashes($_GET['global_serach'])."%' OR `review` LIKE '%".addslashes($_GET['global_serach'])."%')";
}

$select_review_model = "SELECT `id` , `rating`, `review` FROM `tbl_mb_review_rating` WHERE `is_active` = 'Y' ".$select_condition." ";
$rows_review_model = $DBI->get_result($select_review_model);

 
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
                        View Reviews & Ratings
                    </h1>
                </section>
				<div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add-review-rating.php">Add Review & Rating</a></div><br>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Review & Rating List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							 <!-- /.Search Form start-->
							 <form class="form-horizontal" name="reviews-ratings-form" id="reviews-ratings-form" method="GET" action="reviews-ratings.php">
															
								<div class="col-sm-3">
								 <input type="text" name="global_serach" id="global_serach" value="<?php if(isset($_GET['global_serach'])){ echo $_GET['global_serach'];}?>" class="form-control input-sm" placeholder="Global search">
								</div>
								
								<div class="col-sm-3">
									<input type="submit" name="submit" value="Search" class="btn btn-primary">
									<a href="reviews-ratings.php" class="btn btn-default">Reset</a>
								</div>
						
							 </form>
							 <!-- /.Search Form end -->
							 <br><br>
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_review_model )){?>
								<tr>
								  <th>Id</th>
								  <th>Review</th>
								  <th>Rating</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								foreach($rows_review_model as $key => $value){
								?>
								<tr id="row_<?=$value['id']?>">
								  <td><?=$i?></td>
								  <td><?=$value['review']?></td>
								  <td><?=$value['rating']?></td>
								  <td><a href="add-review-rating.php?id=<?=$value['id']?>">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="delete_review_rating(<?=$value['id']?>);">Delete</a></td>
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