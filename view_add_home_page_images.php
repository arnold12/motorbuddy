<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}

$DBI = new Db();

$DBI->query("SET NAMES 'utf8'");


$select_img = "SELECT *
FROM `tbl_mb_home_page_banner`
ORDER BY `order` ASC";

$result_info = $DBI->query($select_img);

if(mysql_num_rows($result_info)){
    $rows_info = $DBI->get_result($select_img);    
}



if(isset($_POST['frm']) && $_POST['frm'] == '1' ){
    
    $target_dir_img = "images/homepage/";

    for( $i=1; $i<=3; $i++){
        $j = $i - 1;
        $update_img = "";

        if($_FILES["img"]["name"][$j] !== ''){
        
            $fileFormatExtArr = explode('.', basename($_FILES["img"]["name"][$j]));
            $file_name = $fileFormatExtArr[0] . '_' . date('YmdHis') . '.' . $fileFormatExtArr[1];

            $target_file_img = $target_dir_img . $file_name;
            $img_url = SITE_URL."/".$target_dir_img . $file_name;
            move_uploaded_file($_FILES["img"]["tmp_name"][$j], $target_file_img);
            $update_img = " ,`img_url` = '".$img_url."' ";

            $update = "UPDATE tbl_mb_home_page_banner SET `img_url` = '".$img_url."' WHERE id = ".$i." ";
            $res_update = $DBI->query($update);
        }

        $update = "UPDATE tbl_mb_home_page_banner SET `description` = '".mysql_real_escape_string(trim($_POST['description_'.$i]))."' WHERE id = ".$i." ";
        $res_update = $DBI->query($update);

    }

    header('Location: view_add_home_page_images.php');
        
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
                        Add Home Page Images
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" action="view_add_home_page_images.php" enctype="multipart/form-data">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>

                                <?php

                                    for( $i=1; $i<=3; $i++){
                                        $j = $i - 1;
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 col-md-2 control-label">Image <?=$i?></label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" class="form-control input-sm" id="description_<?=$i?>" name="description_<?=$i?>" value="<?=isset($rows_info[$j]['description']) ? $rows_info[$j]['description'] : '';?>" placeholder='description'>
                                        <label id="err_msg_description_<?=$i?>" for="description_<?=$i?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="file" id="img_<?=$i?>" name="img[]" value="">
                                        [jpg, jpeg]
                                        <label id="err_msg_img_<?=$i?>" for="img_<?=$i?>" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                    </div>
                                    <?php if( isset($rows_info[$j]['img_url']) && $rows_info[$j]['img_url'] != "" ){?>
                                    <div class="col-sm-4 col-md-3">
                                        <img src="<?=$rows_info[$j]['img_url']?>" alt="" height="42" width="42">
                                    </div>
                                    <?php }?>
                                </div>
                                <?php } ?>

                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="frm" value="1">
                                <button type="submit" class="btn btn-primary" onclick="return validate_home_page_images();" id="submit"><?php echo (isset($_GET['id']) ? 'Update' : 'Add' )?></button>
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
