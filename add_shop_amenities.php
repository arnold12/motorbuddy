<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");


if(isset($_GET['id']) && $_GET['id'] != "" && !isset($_POST['frm'])){
    
    
    $select_info = "SELECT `id`, `shop_amenities`
    FROM `tbl_mb_shop_amenities_master`
    WHERE (`id` = '".$_GET['id']."')
    AND `is_active` = 'Y'";

    $result_info = $DBI->query($select_info);
    
    if(mysql_num_rows($result_info) == 0){
        header('Location: view_shop_amenities.php');die();
    }
    
    $rows_info = $DBI->get_result($select_info);
       
}

if(isset($_POST['frm']) && $_POST['frm'] == '1' ){
    
    
    $shop_amenities = mysql_real_escape_string($_POST['shop_amenities']);
    
    if($_POST['mode'] == 'edit'){ // Edit mode
        
        $shop_amenities_id = $_POST['id'];

        // Update data for delaer
        $update = "UPDATE `tbl_mb_shop_amenities_master` SET `shop_amenities`='".$shop_amenities."', `updated_by`='".$_SESSION['id']."', `updated_at` = '".CURRENT_DATE_TIME."' WHERE id = '".$_POST['id']."' ";
        $res_update = $DBI->query($update);

        
    } else { // Add mode
        
       // Insert data for delaer 
       $insert = "INSERT INTO `tbl_mb_shop_amenities_master` (`shop_amenities`, `is_active`, `created_at`, `created_by`) VALUES ('".$shop_amenities."', 'Y', '".CURRENT_DATE_TIME."', '".$_SESSION['id']."')";
       $res_insert = $DBI->query($insert);
       
    }   
    header('Location: view_shop_amenities.php');
        
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
                        Add Shop Amenities
                        <!--<small>Preview</small>-->
                    </h1>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="view_shop_amenities.php">View Shop Amenities</a></div>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_shop_amenities.php">Add Shop Amenities</a></div>
                    <br>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" action="add_shop_amenities.php">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Shop Amenities</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="shop_amenities" name="shop_amenities" value="<?=isset($rows_info[0]['shop_amenities']) ? $rows_info[0]['shop_amenities'] : '';?>">
                                        <label id="err_msg_shop_amenities" for="shop_amenities" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
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
                                <button type="submit" class="btn btn-primary" onclick="return validate_shop_amenities();" id="submit"><?php echo (isset($_GET['id']) ? 'Update' : 'Add' )?></button>
                                <a href="view_shop_amenities.php" class="btn bg-maroon margin">cancel</a>
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
