<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");


if(isset($_GET['dealer_id']) && $_GET['dealer_id'] != "" && !isset($_POST['frm'])){
    
    
    $select_ratings = "SELECT dr.id, dr.user_id, dr.dealer_id, dr.ratings, dr.comment, dr.created_on, ru.email, dm.dealer_code, dm.dealer_name, dm.dealer_name2
    FROM `tbl_mb_dealer_ratings` AS dr LEFT JOIN `tbl_mb_register_users` AS ru ON dr.user_id = ru.id
    LEFT JOIN `tbl_mb_delaer_master` AS dm ON dr.dealer_id = dm.id
    WHERE dr.dealer_id = '".$_GET['dealer_id']."' AND dr.status = 'Active' ORDER BY id DESC";

    $result_ratings = $DBI->query($select_ratings);
    
    $rows_ratings = $DBI->get_result($select_ratings);

    //echo "<pre>";print_r($rows_ratings);exit;

    $select_dealer_info = "SELECT id, dealer_code, dealer_name, dealer_name2 FROM tbl_mb_delaer_master WHERE id = '".$_GET['dealer_id']."' ";

    $result_dealer_info = $DBI->query($select_dealer_info);

    if(mysql_num_rows($result_dealer_info) == 0){
        header('Location: index.php');die();
    }
    
    $rows_dealer_info = $DBI->get_result($select_dealer_info);
    

    $select_user_info = "SELECT id, email, fname, lname FROM tbl_mb_register_users WHERE status = 'Active' ORDER BY id DESC ";

    $result_user_info = $DBI->query($select_user_info);
    
    $rows_user_info = $DBI->get_result($select_user_info);

    
       
}

if(isset($_POST['frm']) && $_POST['frm'] == '1' ){
    
    $dealer_id 	= mysql_real_escape_string($_POST['dealer_id']);
    $user_id 	= mysql_real_escape_string($_POST['user_id']);
    $ratings 	= mysql_real_escape_string($_POST['ratings']);
    $comments 	= mysql_real_escape_string($_POST['comments']);


    $params['dealer_id'] 	= $dealer_id;
    $params['ratings'] 		= $ratings;
    
    $avg_rating_data = calculate_avg_dealer_ratings($params);
   	
   	$insert = "INSERT INTO `tbl_mb_dealer_ratings` (`user_id`, `dealer_id`, `ratings`, `comment`, `total_ratings`, `total_user`, `status`, `created_by`, `created_on`) VALUES ('".$user_id."', '".$dealer_id."', '".$ratings."', '".$comments."','".$avg_rating_data['total_ratings']."','".$avg_rating_data['total_user']."', 'Active', '".$_SESSION['id']."', now())";
   	$res_insert = $DBI->query($insert);

   	$update = "UPDATE tbl_mb_delaer_master SET dealer_rating = '".$avg_rating_data['avg_ratings']."' WHERE id = '".$dealer_id."' ";
   	$res_update = $DBI->query($update);
    
    header('Location: add_dealer_ratings.php?dealer_id='.$dealer_id);
        
}

?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once('header_script.php'); ?>
        <style>
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 73%;
                }

                td, th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }

                tr:nth-child(even) {
                    background-color: #dddddd;
                }
        </style>
        
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
                        Add Dealer Review & Rating
                        <!--<small>Preview</small>-->
                    </h1>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="index.php">View Dealers</a></div>
                    <br>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="review_rating" method="POST" action="add_dealer_ratings.php">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Dealer Code</label>
                                    <div class="col-sm-2 col-md-2">
                                        <input type="text" class="form-control input-sm" readonly value="<?=$rows_dealer_info[0]['dealer_code'];?>">
                                        <input type="hidden" name="dealer_id" value="<?=$rows_dealer_info[0]['id'];?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Dealer Name</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" readonly value="<?=$rows_dealer_info[0]['dealer_name']." ".$rows_dealer_info[0]['dealer_name2'];?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">User Name</label>
                                    <div class="col-sm-4 col-md-4">
                                    	<select class="form-control input-sm" id="user_id" name="user_id">
                                    		<option value="">Select User</option>
                                    		<?php
                                    			foreach ($rows_user_info as $key => $value) {
                                    				echo "<option value=".$value['id'].">".$value['email']." - ".$value['fname']." ".$value['lname']."</option>";
                                    			}
                                    		?>
                                    	</select>
                                        <label id="err_msg_user_id" for="user_id" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Ratings</label>
                                    <div class="col-sm-4 col-md-4">
                                        <select class="form-control input-sm" id="ratings" name="ratings">
                                        	<option value="">Select Ratings</option>
                                        	<option value="1">1</option>
                                        	<option value="2">2</option>
                                        	<option value="3">3</option>
                                        	<option value="4">4</option>
                                        	<option value="5">5</option>
                                        </select> 
                                        <label id="err_msg_ratings" for="ratings" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Comments</label>
                                    <div class="col-sm-4 col-md-4">
                                        <textarea rows="4" cols="50" class="form-control input-sm" id="comments" name="comments"></textarea> 
                                        <label id="err_msg_comments" for="comments" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>

                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="frm" value="1">
                                <button type="submit" class="btn btn-primary" onclick="return validate_dealer_rating();" id="submit">Add</button>
                                <a href="index.php" class="btn bg-maroon margin">cancel</a>
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;"></label>                              
                            </div>
                        </form>
                    </div><!-- /.box -->
                </section><!-- /.content -->

                <section class="content">
                	<div class="box box-info">
                		<div class="box-body">
                			<?php
                				if( !empty($rows_ratings) ){
							?>
								<table id="" class="table table-bordered">
									<tr>
									  <th>Id</th>
									  <th>User</th>
									  <th>Delaer</th>
									  <th>Rating</th>
									  <th>Comment</th>
									  <th>Date</th>
									</tr>

									<?php
										$i = 1;
										foreach ($rows_ratings as $key => $value) {
									?>
									
										<tr>
											<td><?=$i?></td>
											<td><?=$value['email']?></td>
											<td><?=$value['dealer_code']." = ".$value['dealer_name']." ".$value['dealer_name2'] ?></td>
											<td><?=$value['ratings']?></td>
											<td><?=$value['comment']?></td>
											<td><?=$value['created_on']?></td>
										</tr>
									<?php
									$i++;		
										}
									?>
								</table>
							<?php                					
                				}
                			?>
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