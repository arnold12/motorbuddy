<?php
 require_once 'config.php';

 if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

$select_recom_pdf = "SELECT `id` , `file_url` FROM `tbl_mb_recomendation_pdf` WHERE status = 'Active' ORDER BY id desc";
$result_recom_pdf = $DBI->query($select_recom_pdf);
$rows_recom_pdf = $DBI->get_result($select_recom_pdf);

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
                        View Recommendation PDF
                    </h1>
                </section>
				<div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_recommedation_pdf.php">Add PDF</a></div><br>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
						  <div class="box">
							<div class="box-header">
							  <h3 class="box-title">PDF List</h3> 
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive no-padding">
							 
							 <br><br>
							  <table id="" class="table table-bordered">
								<tbody>
								<?php if(!empty($rows_recom_pdf )){?>
								<tr>
								  <th>Id</th>
								  <th>PDF</th>
								  <th>Model Name</th>
								  <th>Action</th>
								</tr>
								<?php
								$i = 1;
								
								foreach($rows_recom_pdf as $key => $value){

									$select_pdf_assign_model = "SELECT 
									    pdf.model_id, bm.brand_model_name
									FROM
									    tbl_mb_recomedation_pdf_model_mapping AS pdf
									        LEFT JOIN
									    tbl_mb_brand_model_master AS bm ON pdf.model_id = bm.id
									WHERE
									    pdf.recomedation_pdf_id = '".$value['id']."' ";

									$result_pdf_assign_model = $DBI->query($select_pdf_assign_model);
									$rows_pdf_assign_model = $DBI->get_result($select_pdf_assign_model);

								?>
								<tr id="row_<?=$key?>">
								  <td><?=$i?></td>
								  <td><a href="<?=$value['file_url']?>" target="_blank"><?=$value['file_url']?></a></td>
								  <td>
								  	<?php
								  		
							  			foreach ($rows_pdf_assign_model as $key1 => $value1) {
							  				
							  				echo $value1['brand_model_name']." || ";
							  			} 
								  		
								  			
								  	?>
								  </td>
								  <td>
								  	<a href="add_recommedation_pdf.php?id=<?=$value['id']?>">Edit</a>&nbsp;|&nbsp;
								  	<a href="#" onclick="delete_recom_pdf(<?=$value['id']?>, 'Inactive');">Delete</a>
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