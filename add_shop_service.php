<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");


if(isset($_GET['id']) && $_GET['id'] != "" && !isset($_POST['frm'])){
    
    
    $select_info = "SELECT `id`, `shop_service`
    FROM `tbl_mb_shop_service_master`
    WHERE (`id` = '".$_GET['id']."')
    AND `is_active` = 'Y'";

    $result_info = $DBI->query($select_info);
    
    if(mysql_num_rows($result_info) == 0){
        header('Location: view_shop_service.php');die();
    }
    
    $rows_info = $DBI->get_result($select_info);
       
}

if(isset($_POST['frm']) && $_POST['frm'] == '1' ){
    
    
    $shop_service = mysql_real_escape_string($_POST['shop_service']);
    
    if($_POST['mode'] == 'edit'){ // Edit mode
        
        $shop_shop_service = $_POST['id'];

        // Update data for delaer
        $update = "UPDATE `tbl_mb_shop_service_master` SET `shop_service`='".$shop_service."', `updated_by`='".$_SESSION['id']."', `updated_at` = now() WHERE id = '".$_POST['id']."' ";
        $res_update = $DBI->query($update);

        
    } else { // Add mode
        
       // Insert data for delaer 
       $insert = "INSERT INTO `tbl_mb_shop_service_master` (`shop_service`, `is_active`, `created_at`, `created_by`) VALUES ('".$shop_service."', 'Y', now(), '".$_SESSION['id']."')";
       $res_insert = $DBI->query($insert);
       
    }   
    header('Location: view_shop_service.php');
        
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
                        Add Shop Service
                        <!--<small>Preview</small>-->
                    </h1>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="view_shop_service.php">View Shop Service</a></div>
                    <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_shop_service.php">Add Shop Service</a></div>
                    <br>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" action="add_shop_service.php">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>
                                <div class="form-group">
                                    <label class="col-sm-3 col-md-3 control-label">Shop Service</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="shop_service" name="shop_service" value="<?=isset($rows_info[0]['shop_service']) ? $rows_info[0]['shop_service'] : '';?>">
                                        <label id="err_msg_shop_service" for="shop_service" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
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
                                <button type="submit" class="btn btn-primary" onclick="return validate_shop_service();" id="submit"><?php echo (isset($_GET['id']) ? 'Update' : 'Add' )?></button>
                                <a href="view_shop_service.php" class="btn bg-maroon margin">cancel</a>
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
