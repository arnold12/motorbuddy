<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$select_condition = '';

if(isset($_GET['global_serach']) && $_GET['global_serach']!== ''){
	$select_condition .= " AND `brand_model_name` LIKE '%".addslashes($_GET['global_serach'])."%' ";
}

$select_brand_model = "SELECT `id` , `brand_model_name`, `brand_id`, `is_active` FROM `tbl_mb_brand_model_master` WHERE 1 ".$select_condition." ";
$result_brand_model = $DBI->query($select_brand_model);
$rows_brand_model = $DBI->get_result($select_brand_model);

$result_brand_master_arry = [];
if(!empty($rows_brand_model)){
    foreach ($rows_brand_model as $key => $value) {
    	if($value['brand_id'] == 0 || ($value['brand_id'] != 0 && $value['is_active'] == 'Y') ){
        	$result_brand_master_arry[$value['brand_id']][$value['id']] =  $value;
    	}
    }
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
                        View Brand
                    </h1>
                </section>
				<div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_brand_model.php">Add Brand</a></div><br>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">Brand List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							 <!-- /.Search Form start-->
							 <form class="form-horizontal" name="view_snakeinfo_form" id="view_snakeinfo_form" method="GET" action="view_brand_model.php">
															
								<div class="col-sm-3">
								 <input type="text" name="global_serach" id="global_serach" value="<?php if(isset($_GET['global_serach'])){ echo $_GET['global_serach'];}?>" class="form-control input-sm" placeholder="Global search">
								</div>
								
								<div class="col-sm-3">
									<input type="submit" name="submit" value="Search" class="btn btn-primary">
									<a href="view_brand_model.php" class="btn btn-default">Reset</a>
								</div>
						
							 </form>
							 <!-- /.Search Form end -->
							 <br><br>
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($result_brand_master_arry )){?>
								<tr>
								  <th>Id</th>
								  <th>Brand Name</th>
								  <th>Model Name</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								
								foreach($result_brand_master_arry[0] as $key => $value){
									$color = "";
									if($value['is_active'] == 'N'){
										$color = "red";
									}
								?>
								<tr id="row_<?=$key?>">
								  <td><?=$i?></td>
								  <td><?=$value['brand_model_name']?></td>
								  <td>
								  	<?php
								  		if(array_key_exists($key, $result_brand_master_arry)){
								  			foreach ($result_brand_master_arry[$key] as $model_key => $model_name) {
								  				
								  				echo $model_name['brand_model_name']." || ";
								  			} 
								  		}
								  			
								  	?>
								  </td>
								  <td>
								  <?php if($value['is_active'] == 'N'){ ?>
								  <a style="color: red" href="#" onclick="delete_brand_model(<?=$key?>, 'Y');">Enable</a>
								  <?php } else {?>
								  <a href="add_brand_model.php?id=<?=$key?>">Edit</a>&nbsp;|&nbsp;
								  <a href="#" onclick="delete_brand_model(<?=$key?>, 'N');">Disable</a>
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