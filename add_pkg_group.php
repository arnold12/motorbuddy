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
                        Add new Package Group
                        <!--<small>Preview</small>-->
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" action="add_pkg_group.php">
                            <div class="box-body">

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
                                        <input type="text" class="form-control input-sm" id="pkg_price_<?=$pkg_type_id?>" name="pkg_price[]" value="">
                                        <label id="err_msg_pkg_price_<?=$pkg_type_id?>" for="pkg_price_<?=$pkg_type_id?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Package Description</label>
                                    <div class="col-sm-4 col-md-4">
                                         <textarea rows="4" cols="50" class="form-control input-sm" id="pkg_description_<?=$pkg_type_id?>" name="pkg_description[]"></textarea> 
                                        <label id="err_msg_pkg_description_<?=$pkg_type_id?>" for="pkg_description_<?=$pkg_type_id?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Motorbuddy TIP</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="mb_tip_<?=$pkg_type_id?>" name="mb_tip[]" value="">
                                        <label id="err_msg_mb_tip_<?=$pkg_type_id?>" for="mb_tip_<?=$pkg_type_id?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                </div>
                                <?php
                                    for( $pp = 1; $pp <=5; $pp++ ){
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Package Service <?=$pp?></label>
                                    <div class="col-sm-3 col-md-2">
                                        <input type="text" placeholder="Service Name" class="form-control input-sm" id="service_name_<?=$pkg_type_id?>_<?=$pp?>" name="service_name_<?=$pkg_type_id?>[]" value="">
                                        <label id="err_msg_service_name_<?=$pkg_type_id?>_<?=$pp?>" for="service_name_<?=$pkg_type_id?>_<?=$pp?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>

                                    <div class="col-sm-3 col-md-2">
                                        <select class="form-control input-sm" id="service_action_<?=$pkg_type_id?>_<?=$pp?>" name="service_action_<?=$pkg_type_id?>[]">
                                            <option value="">Select Action</option>
                                            <option value="Cleaned">Cleaned</option>
                                            <option value="Replaced">Replaced</option>
                                            <option value="Top Up">Top Up</option>
                                            <option value="Included">Included</option>
                                            <option value="Serviced">Serviced</option>
                                        </select>
                                        <label id="err_msg_service_action_<?=$pkg_type_id?>_<?=$pp?>" for="service_action_<?=$pkg_type_id?>_<?=$pp?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>

                                    <div class="col-sm-3 col-md-2">
                                        <select class="form-control input-sm" id="service_status_<?=$pkg_type_id?>_<?=$pp?>" name="service_status_<?=$pkg_type_id?>[]">
                                            <option value="">Select Status</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
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
                                                <input type="checkbox" class="brand" name="brand_model[]" value="<?= $brand_id ?>">
                                                <label><?= $brand_name?></label>
                                                <?php
                                                if( isset($result_brand_master_arry[$brand_id]) && count($result_brand_master_arry[$brand_id]) ){
                                                echo "&nbsp;=>&nbsp;";    
                                                foreach ($result_brand_master_arry[$brand_id] as $model_id => $model_name) {
                                                ?>
                                                &nbsp;&nbsp;
                                                <input type="checkbox" class="brand" name="brand_model[]" value="<?= $model_id ?>">
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
