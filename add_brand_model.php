<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");


if(isset($_GET['id']) && $_GET['id'] != "" && !isset($_POST['frm'])){
    
    // fetch delaer data
    $select_info = "SELECT `id`, `brand_id`, `brand_model_name`
    FROM `tbl_mb_brand_model_master`
    WHERE (`id` = '".$_GET['id']."' OR `brand_id` = '".$_GET['id']."')
    AND `is_active` = 'Y'
    ORDER BY brand_id ASC";

    $result_info = $DBI->query($select_info);
    
    if(mysql_num_rows($result_info) == 0){
        header('Location: view_brand_model.php');die();
    }
    
    $rows_info = $DBI->get_result($select_info);
       
}

if(isset($_POST['frm']) && $_POST['frm'] == '1' ){
    
    
    $brand_name = mysql_real_escape_string($_POST['brand_name']);
    
    if($_POST['mode'] == 'edit'){ // Edit mode
        
        $brand_model_id = $_POST['id'];

        // Update data for delaer
        $update = "UPDATE `tbl_mb_brand_model_master` SET `brand_model_name`='".$brand_name."', `updated_by`='".$_SESSION['id']."', `updated_at` = '".CURRENT_DATE_TIME."' WHERE id = '".$_POST['id']."' ";
        $res_update = $DBI->query($update);



        //$delete = "DELETE FROM `tbl_mb_brand_model_master` WHERE `brand_id` = '".$_POST['id']."' ";
        //$res_delete = $DBI->query($delete);

        foreach ($_POST['edit_brand_name'] as $key => $value) {

            if( trim($value) != "" ){

                $update = "UPDATE `tbl_mb_brand_model_master` SET `brand_model_name` = '".trim($value)."', updated_at = '".CURRENT_DATE_TIME."', updated_by = '".$_SESSION['id']."' WHERE id = '".$_POST['edit_model_id'][$key]."' ";
                $res_update = $DBI->query($update);

            } else {

                $update = "UPDATE `tbl_mb_brand_model_master` SET `is_active` = 'N', updated_at = '".CURRENT_DATE_TIME."', updated_by =  '".$_SESSION['id']."' WHERE id = '".$_POST['edit_model_id'][$key]."' ";
                $res_update = $DBI->query($update);
            }
           
       }

       foreach ($_POST['add_brand_name'] as $key => $value) {

            if( trim($value) != "" ){

                $insert = "INSERT INTO `tbl_mb_brand_model_master` (`brand_id`, `brand_model_name`, `is_active`, `created_at`, `created_by`) VALUES ('".$_POST['id']."', '".trim($value)."', 'Y', '".CURRENT_DATE_TIME."', '".$_SESSION['id']."')";
                $res_insert = $DBI->query($insert);

            }
       }

        
    } else { // Add mode
        
       // Insert data for delaer 
       $insert = "INSERT INTO `tbl_mb_brand_model_master` (`brand_id`, `brand_model_name`, `is_active`, `created_at`, `created_by`) VALUES ('0', '".$brand_name."', 'Y', '".CURRENT_DATE_TIME."', '".$_SESSION['id']."')";
       $res_insert = $DBI->query($insert);
       $brand_id = mysql_insert_id();

       foreach ($_POST['add_brand_name'] as $key => $value) {

            if( trim($value) != "" ){

                $insert = "INSERT INTO `tbl_mb_brand_model_master` (`brand_id`, `brand_model_name`, `is_active`, `created_at`, `created_by`) VALUES ('".$brand_id."', '".trim($value)."', 'Y', '".CURRENT_DATE_TIME."', '".$_SESSION['id']."')";
                $res_insert = $DBI->query($insert);

            }
           
       }
       
    }   
    header('Location: view_brand_model.php');
        
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
                        Add Brand
                        <!--<small>Preview</small>-->
                    </h1>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="view_brand_model.php">View Brand</a></div>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_brand_model.php">Add Brand</a></div>
                    <br>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" action="add_brand_model.php">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Brand Name</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="brand_name" name="brand_name" value="<?=isset($rows_info[0]['brand_model_name']) ? $rows_info[0]['brand_model_name'] : '';?>">
                                        <label id="err_msg_brand_name" for="brand_name" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>

                                <?php
                                    $cnt = 1;
                                    if(isset($_GET['id'])){

                                        foreach ($rows_info as $key => $value) {

                                            if($value['brand_id'] != 0 ){

                                ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 col-md-3 control-label">Brand Model <?=$cnt?></label>
                                                        <div class="col-sm-4 col-md-3">
                                                            <input type="text" class="form-control input-sm" id="edit_brand_model_<?=$cnt?>" name="edit_brand_name[]" value="<?=$value['brand_model_name']?>">
                                                            <input type="hidden" name="edit_model_id[]" value="<?=$value['id']?>">
                                                        </div>
                                                    </div>
                                <?php   
                                            $cnt++;

                                            }

                                        }

                                    }

                                    $cnt = $cnt - 1;
                                ?>


                                <?php 
                                    for($i = 1; $i <= 5; $i++) {

                                ?>

                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Brand Model <?=$i+$cnt?></label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="add_brand_model_<?=$i+$cnt?>" name="add_brand_name[]" value="">
                                    </div>
                                </div>

                                <?php                           
                                    }
                                ?>


                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="frm" value="1">
                                <input type="hidden" name="mode" id="mode" value="<?php echo (isset($_GET['id']) ? 'edit' : 'add' )?>">
                                <?php
                                    if(isset($_GET['id'])){
                                ?>
                                    <input type="hidden" name="id" id="id" value="<?= $_GET['id'] ?>">
                                <?php
                                    }
                                ?>
                                <button type="submit" class="btn btn-primary" onclick="return validate_brand_model();" id="submit"><?php echo (isset($_GET['id']) ? 'Update' : 'Add' )?></button>
                                <a href="view_brand_model.php" class="btn bg-maroon margin">cancel</a>
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;"></label>                              
                            </div>
                        </form>
                    </div><!-- /.box -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
           <?php include_once 'footer.php';?>
           
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div><!-- ./wrapper -->
    </body>
</html>
