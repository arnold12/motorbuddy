<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");


if(isset($_GET['id']) && $_GET['id'] != "" && !isset($_POST['frm'])){
    
    
    $select_info = "SELECT `id`, `name`, `type` 
    FROM `tbl_mb_booking_service_repair_master`
    WHERE (`id` = '".$_GET['id']."')
    AND `is_active` = 'Y'";

    $result_info = $DBI->query($select_info);
    
    if(mysql_num_rows($result_info) == 0){
        header('Location: view_services_repair.php');die();
    }
    
    $rows_info = $DBI->get_result($select_info);
       
}

if(isset($_POST['frm']) && $_POST['frm'] == '1' ){
    
    
    $name = mysql_real_escape_string($_POST['name']);
    $type = mysql_real_escape_string($_POST['type']);
    
    if($_POST['mode'] == 'edit'){ // Edit mode
        
        $shop_shop_service = $_POST['id'];

        // Update data for delaer
        $update = "UPDATE `tbl_mb_booking_service_repair_master` SET `name`='".$name."', `type`='".$type."' WHERE id = '".$_POST['id']."' ";
        $res_update = $DBI->query($update);

        
    } else { // Add mode
        
       // Insert data for delaer 
       $insert = "INSERT INTO `tbl_mb_booking_service_repair_master` (`name`, `type`, `is_active`) VALUES ('".$name."', '".$type."', 'Y')";
       $res_insert = $DBI->query($insert);
       
    }   
    header('Location: view_services_repair.php');
    // echo "abc";
    // exit();
        
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
                        Add Service Repair
                        <!--<small>Preview</small>-->
                    </h1>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="view_services_repair.php">View Service Repair</a></div>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_service_repair.php">Add Service Repair</a></div>
                    <br>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" action="add_service_repair.php">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Name</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="name" name="name" value="<?=isset($rows_info[0]['name']) ? $rows_info[0]['name'] : '';?>">
                                        <label id="err_msg_name" for="name" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Type</label>
                                    <div class="col-sm-4 col-md-3">
                                        <select class="form-control select2" id="type" name="type" style="width: 100%; height: 30px" onchange="getServiceForm(this.value);">
                                            <option selected="selected" value="">Select</option>
                                            <option <?php if(isset($rows_info[0]['type']) && $rows_info[0]['type'] == 'service') {?> selected <?php }?> value="service">Service</option>
                                            <option <?php if(isset($rows_info[0]['type']) && $rows_info[0]['type'] == 'repair') {?> selected <?php }?> value="repair">Repair</option>
                                        </select>
                                        <label id="err_msg_type" for="type" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
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
                                <button type="submit" class="btn btn-primary" onclick="return validate_service_repair();" id="submit"><?php echo (isset($_GET['id']) ? 'Update' : 'Add' )?></button>
                                <a href="view_services_repair.php" class="btn bg-maroon margin">cancel</a>
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
