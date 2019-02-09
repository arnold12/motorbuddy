<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");

// fetch brand from master
$select_brand_master = "SELECT `id`, `brand_id`, `brand_model_name`
FROM `tbl_mb_brand_model_master`
WHERE `is_active` = 'Y'";
$result_brand_master = $DBI->get_result($select_brand_master);
$result_brand_master_arry = [];
if(!empty($result_brand_master)){
    foreach ($result_brand_master as $key => $value) {
        $result_brand_master_arry[$value['brand_id']][$value['id']] =  $value['brand_model_name'];
    }
}

if(isset($_GET['pkg_group_name']) && !empty($_GET['pkg_group_name']) ){

    $pkg_group_name = trim($_GET['pkg_group_name']);

    $select_pkg_master =  "SELECT * FROM tbl_mb_pkg_master WHERE pkg_group_name = '".$pkg_group_name."' AND status = 'Active' ";
    $result_pkg_master = $DBI->get_result($select_pkg_master);
    if( empty($result_pkg_master) ){
        header('Location: view_pkg.php');
    }
    $result_pkg_master_new = array();
    foreach ($result_pkg_master as $key => $value) {
        $result_pkg_master_new[$value['pkg_type_id']] = $value;
    }

    $select_pkg_service_details =  "SELECT * FROM tbl_mb_pkg_service_details WHERE pkg_group_name = '".$pkg_group_name."' AND status = 'Active' ";
    $result_pkg_service_details = $DBI->get_result($select_pkg_service_details);
    $result_pkg_service_details_new = array();
    foreach ($result_pkg_service_details as $key => $value) {
        $result_pkg_service_details_new[$value['pkg_master_id']][] = $value;
    }

    


    $select_pkg_brand_mapping =  "SELECT * FROM tbl_mb_pkg_brand_mapping WHERE pkg_group_name = '".$pkg_group_name."' ";
    $result_pkg_brand_mapping = $DBI->get_result($select_pkg_brand_mapping);
    $result_pkg_brand_mapping_new = array();
    foreach ($result_pkg_brand_mapping as $key => $value) {
        $result_pkg_brand_mapping_new[$value['brand_model_id']] = $value;
    }
    
}

if(isset($_POST['frm']) && $_POST['frm'] == '1' ){

    if($_POST['mode'] == 'edit'){ // Edit mode

        $pkg_group_code = $_POST['pkg_group_name'];

        foreach ($_POST['pkg_type_id'] as $key => $pkg_type_id) {

            $pkg_price = mysql_real_escape_string($_POST['pkg_price'][$key]);
            $pkg_description = mysql_real_escape_string($_POST['pkg_description'][$key]);
            $mb_tip = mysql_real_escape_string($_POST['mb_tip'][$key]);

            $update = "UPDATE `tbl_mb_pkg_master` SET `pkg_price` = '".$pkg_price."', `pkg_description` = '".$pkg_description."', `mb_tip` = '".$mb_tip."', `updated_by` = '".$_SESSION['id']."', `updated_on` = now() WHERE `pkg_group_name` = '".$pkg_group_code."' AND  `pkg_type_id` = '".$pkg_type_id."'";
            $res_update = $DBI->query($update);
            

            foreach ($_POST['service_name_'.$pkg_type_id] as $service_key => $service_name) {

                $service_action = mysql_real_escape_string($_POST['service_action_'.$pkg_type_id][$service_key]);
                $service_status = mysql_real_escape_string($_POST['service_status_'.$pkg_type_id][$service_key]);
                $service_id = mysql_real_escape_string($_POST['service_id_'.$pkg_type_id][$service_key]);

                $update = "UPDATE `tbl_mb_pkg_service_details` SET `service_name` = '".$service_name."', `service_action` = '".$service_action."', `status` = '".$service_status."' WHERE `id` = '".$service_id."' ";
                $res_update = $DBI->query($update);
                
            }
            
        }

        $delete = "DELETE FROM `tbl_mb_pkg_brand_mapping` WHERE `pkg_group_name` = '".$pkg_group_code."' ";
        $res_delete = $DBI->query($delete);

        foreach ($_POST['brand_model'] as $key => $brand_model_id) {

            $insert = "INSERT INTO `tbl_mb_pkg_brand_mapping` (`pkg_group_name`, `brand_model_id`) VALUES ('".$pkg_group_code."', '".$brand_model_id."')";
            $res_insert = $DBI->query($insert);
        }

        

    } else { //Add Mode

        $pkg_group_code = generatePkgGroupCode();
        
        foreach ($_POST['pkg_type_id'] as $key => $pkg_type_id) {

            $pkg_price = mysql_real_escape_string($_POST['pkg_price'][$key]);
            $pkg_description = mysql_real_escape_string($_POST['pkg_description'][$key]);
            $mb_tip = mysql_real_escape_string($_POST['mb_tip'][$key]);

            $insert = "INSERT INTO `tbl_mb_pkg_master` (`pkg_group_name`, `pkg_type_id`, `pkg_price`, `pkg_description`, `mb_tip`, `status`, `created_by`, `created_on`) VALUES ('".$pkg_group_code."', '".$pkg_type_id."', '".$pkg_price."', '".$pkg_description."', '".$mb_tip."', 'Active', '".$_SESSION['id']."', now())";
            $res_insert = $DBI->query($insert);

            $pkg_master_id = $DBI->get_insert_id();

            foreach ($_POST['service_name_'.$pkg_type_id] as $service_key => $service_name) {

                $service_action = mysql_real_escape_string($_POST['service_action_'.$pkg_type_id][$service_key]);
                $service_status = mysql_real_escape_string($_POST['service_status_'.$pkg_type_id][$service_key]);

                $insert = "INSERT INTO `tbl_mb_pkg_service_details` (`pkg_group_name`, `pkg_master_id`, `service_name`, `service_action`, `status`) VALUES ('".$pkg_group_code."', '".$pkg_master_id."', '".mysql_real_escape_string($service_name)."', '".$service_action."', '".$service_status."')";
                $res_insert = $DBI->query($insert);
            }
            
        }

        foreach ($_POST['brand_model'] as $key => $brand_model_id) {
            $insert = "INSERT INTO `tbl_mb_pkg_brand_mapping` (`pkg_group_name`, `brand_model_id`) VALUES ('".$pkg_group_code."', '".$brand_model_id."')";
            $res_insert = $DBI->query($insert);
        }

    }
    header('Location: view_pkg.php');

}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=SITE_TITLE?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
         <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <!-- datepicker -->
        <link rel="stylesheet" href="dist/css/jquery-ui.css">
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
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
         <!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <!-- Select2 -->
        <script src="plugins/select2/select2.full.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/app.min.js"></script>
        <script src="dist/js/jquery-ui.js"></script>
        <script src="dist/js/common.js"></script>
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
                        <?php echo (isset($_GET['pkg_group_name']) ? 'Update' : 'Add' ) ?> Package Group
                        <!--<small>Preview</small>-->
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" action="add_pkg_group.php">
                            <div class="box-body">

                                <?php if(isset($_GET['pkg_group_name'])){?>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Package Group</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" readonly class="form-control input-sm" value="<?=$pkg_group_name?>">
                                    </div>
                                </div>
                                <hr>
                                <?php }?>

                                <?php foreach ($pkg_type_arry as $pkg_type_id => $pkg_type) { ?>
                                
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Package Type</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" readonly class="form-control input-sm" value="<?=$pkg_type?>">
                                        <input type="hidden" name="pkg_type_id[]" value="<?=$pkg_type_id?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Package Price</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="pkg_price_<?=$pkg_type_id?>" name="pkg_price[]" value="<?php if( isset($result_pkg_master_new[$pkg_type_id]['pkg_price']) ) echo $result_pkg_master_new[$pkg_type_id]['pkg_price']; ?>">
                                        <label id="err_msg_pkg_price_<?=$pkg_type_id?>" for="pkg_price_<?=$pkg_type_id?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Package Description</label>
                                    <div class="col-sm-4 col-md-4">
                                         <textarea rows="4" cols="50" class="form-control input-sm" id="pkg_description_<?=$pkg_type_id?>" name="pkg_description[]"><?php if( isset($result_pkg_master_new[$pkg_type_id]['pkg_description']) ) echo $result_pkg_master_new[$pkg_type_id]['pkg_description']; ?></textarea> 
                                        <label id="err_msg_pkg_description_<?=$pkg_type_id?>" for="pkg_description_<?=$pkg_type_id?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Motorbuddy TIP</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="mb_tip_<?=$pkg_type_id?>" name="mb_tip[]" value="<?php if( isset($result_pkg_master_new[$pkg_type_id]['mb_tip']) ) echo $result_pkg_master_new[$pkg_type_id]['mb_tip']; ?>">
                                        <label id="err_msg_mb_tip_<?=$pkg_type_id?>" for="mb_tip_<?=$pkg_type_id?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <?php
                                    $pkg_master_id = "";
                                    if( isset($result_pkg_master_new[$pkg_type_id]['id']) ){
                                        $pkg_master_id = $result_pkg_master_new[$pkg_type_id]['id'];
                                    }
                                    for( $pp = 1; $pp <=5; $pp++ ){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Package Service <?=$pp?></label>
                                    <div class="col-sm-3 col-md-2">
                                        <input type="text" placeholder="Service Name" class="form-control input-sm" id="service_name_<?=$pkg_type_id?>_<?=$pp?>" name="service_name_<?=$pkg_type_id?>[]" value="<?php if(isset($result_pkg_service_details_new[$pkg_master_id][$pp-1]['service_name'])) echo $result_pkg_service_details_new[$pkg_master_id][$pp-1]['service_name']; ?>">
                                        <label id="err_msg_service_name_<?=$pkg_type_id?>_<?=$pp?>" for="service_name_<?=$pkg_type_id?>_<?=$pp?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>

                                    
                                    <input type="hidden" name="service_id_<?=$pkg_type_id?>[]" value="<?php if(isset($result_pkg_service_details_new[$pkg_master_id][$pp-1]['id'])) echo $result_pkg_service_details_new[$pkg_master_id][$pp-1]['id']; ?>">


                                    <div class="col-sm-3 col-md-2">
                                        <select class="form-control input-sm" id="service_action_<?=$pkg_type_id?>_<?=$pp?>" name="service_action_<?=$pkg_type_id?>[]">
                                            <?php 
                                                $service_action = "";
                                                if(isset($result_pkg_service_details_new[$pkg_master_id][$pp-1]['service_action'])){
                                                    $service_action = $result_pkg_service_details_new[$pkg_master_id][$pp-1]['service_action'];  
                                                } 
                                            ?>
                                            <option value="">Select Action</option>
                                            <option value="Cleaned" <?php if($service_action == 'Cleaned') echo 'selected'?>>Cleaned</option>
                                            <option value="Replaced" <?php if($service_action == 'Replaced') echo 'selected'?>>Replaced</option>
                                            <option value="Top Up" <?php if($service_action == 'Top Up') echo 'selected'?>>Top Up</option>
                                            <option value="Included" <?php if($service_action == 'Included') echo 'selected'?>>Included</option>
                                            <option value="Serviced" <?php if($service_action == 'Serviced') echo 'selected'?>>Serviced</option>
                                        </select>
                                        <label id="err_msg_service_action_<?=$pkg_type_id?>_<?=$pp?>" for="service_action_<?=$pkg_type_id?>_<?=$pp?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>

                                    <div class="col-sm-3 col-md-2">
                                        <select class="form-control input-sm" id="service_status_<?=$pkg_type_id?>_<?=$pp?>" name="service_status_<?=$pkg_type_id?>[]">
                                            <?php 
                                                $service_status = "";
                                                if(isset($result_pkg_service_details_new[$pkg_master_id][$pp-1]['status'])){
                                                    $service_status = $result_pkg_service_details_new[$pkg_master_id][$pp-1]['status'];  
                                                } 
                                            ?>
                                            <option value="Active" <?php if($service_status == 'Active') echo 'selected'?>>Active</option>
                                            <option value="Inactive" <?php if($service_status == 'Inactive') echo 'selected'?>>Inactive</option>
                                        </select>
                                        <label id="err_msg_service_status_<?=$pkg_type_id?>_<?=$pp?>" for="service_status_<?=$pkg_type_id?>_<?=$pp?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>

                                <?php }?>
                                <hr>
                                <?php }?>
                                <hr>
                                <hr>
                                <h4><font color="red">Assign package to brand model</font></h4>
                                    
                                    <label id="err_msg_shop_insurance" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;padding-bottom: 20px;"></label>
                                    <div class="row" style="margin-left: 4px;">
                                        <?php
                                        foreach($result_brand_master_arry[0] as $brand_id => $brand_name){
                                        ?>
                                        <div class="col-sm-8 col-md-8">
                                            <div class="form-group">
                                                <!-- <input type="checkbox" class="brand" name="brand_model[]" value="<?= $brand_id ?>"> -->
                                                <label><?= $brand_name?></label>
                                                <?php
                                                if( isset($result_brand_master_arry[$brand_id]) && count($result_brand_master_arry[$brand_id]) ){
                                                echo "&nbsp;=>&nbsp;";    
                                                foreach ($result_brand_master_arry[$brand_id] as $model_id => $model_name) {
                                                    $checked = '';
                                                    if(array_key_exists($model_id, $result_pkg_brand_mapping_new)){
                                                        $checked = 'checked';
                                                    }
                                                ?>
                                                &nbsp;&nbsp;
                                                <input type="checkbox" class="brand" name="brand_model[]" value="<?= $model_id ?>" <?=$checked?>>
                                                <label><?= $model_name?></label>
                                                <?php                                                        
                                                }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                         
                                         }
                                        ?>
                                    </div>
                                    <hr>
                                    <hr>

                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="frm" value="1">
                                <input type="hidden" name="pkg_group_name" value="<?php echo (isset($_GET['pkg_group_name']) ? $_GET['pkg_group_name']  : '')?>">
                                <input type="hidden" name="mode" id="mode" value="<?php echo (isset($_GET['pkg_group_name']) ? 'edit' : 'add' )?>">
                                
                                <button type="submit" class="btn btn-primary" onclick="return validate_pkg_group();" id="submit"><?php echo (isset($_GET['pkg_group_name']) ? 'Update' : 'Add' )?></button>
                                <a href="view_pkg.php" class="btn bg-maroon margin">cancel</a>
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
