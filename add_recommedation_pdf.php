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

if(isset($_GET['id']) && !empty($_GET['id']) ){

    $id = trim($_GET['id']);

    $select_recom_pdf = "SELECT id, file_url FROM tbl_mb_recomendation_pdf WHERE id = '".$id."' ";
    $result_recom_pdf = $DBI->get_result($select_recom_pdf);

    $select_recom_pdf_model = "SELECT model_id FROM tbl_mb_recomedation_pdf_model_mapping WHERE recomedation_pdf_id = '".$id."' ";
    $result_recom_pdf_model = $DBI->get_result($select_recom_pdf_model);
    
    $result_recom_pdf_model_new = array();
    foreach ($result_recom_pdf_model as $key => $value) {
        $result_recom_pdf_model_new[$value['model_id']] = $value['model_id'];
    }

}


if(isset($_POST['frm']) && $_POST['frm'] == '1' ){
    
    $target_dir_img = "upload_files/recommedation/";
    
    if($_POST['mode'] == 'edit'){ // Edit mode

        if($_FILES["recom_pdf"]["name"] !== ''){
        
            $fileFormatExtArr = explode('.', basename($_FILES["recom_pdf"]["name"]));
            $file_name = $fileFormatExtArr[0] . '_' . date('YmdHis') . '.' . $fileFormatExtArr[1];

            $target_file_img = $target_dir_img . $file_name;
            $img_url = SITE_URL."/".$target_dir_img . $file_name;
            move_uploaded_file($_FILES["recom_pdf"]["tmp_name"], $target_file_img);

            $update = "UPDATE tbl_mb_recomendation_pdf SET file_url = '".$img_url."', update_by = '".$_SESSION['id']."', updated_on = now() WHERE id = '".$_POST['id']."'";
            $res_update = $DBI->query($update);
            
        }

        if(isset($_POST['brand_model'])){

            $delete = "DELETE FROM tbl_mb_recomedation_pdf_model_mapping WHERE recomedation_pdf_id = '".$_POST['id']."'";
            $res_delete = $DBI->query($delete);

            foreach ($_POST['brand_model'] as $key => $value) {
                $insert = "INSERT INTO tbl_mb_recomedation_pdf_model_mapping (recomedation_pdf_id, model_id) VALUES ('".$_POST['id']."', '".$value."')";
                $res_insert = $DBI->query($insert);
            }
        }


    } else {

        if($_FILES["recom_pdf"]["name"] !== ''){
        
            $fileFormatExtArr = explode('.', basename($_FILES["recom_pdf"]["name"]));
            $file_name = $fileFormatExtArr[0] . '_' . date('YmdHis') . '.' . $fileFormatExtArr[1];

            $target_file_img = $target_dir_img . $file_name;
            $img_url = SITE_URL."/".$target_dir_img . $file_name;
            move_uploaded_file($_FILES["recom_pdf"]["tmp_name"], $target_file_img);

            $insert = "INSERT INTO tbl_mb_recomendation_pdf (file_url, status, created_by, created_on) VALUES ('".$img_url."', 'Active', '".$_SESSION['id']."', now())";
            $res_insert = $DBI->query($insert);

            $recom_pdf_id = $DBI->get_insert_id();

            if(isset($_POST['brand_model'])){

                foreach ($_POST['brand_model'] as $key => $value) {
                    $insert = "INSERT INTO tbl_mb_recomedation_pdf_model_mapping (recomedation_pdf_id, model_id) VALUES ('".$recom_pdf_id."', '".$value."')";
                    $res_insert = $DBI->query($insert);
                }
            }
        }
    }

    header('Location: view_recommedation_pdf.php');
        
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
                        Add PDF
                    </h1>
                </section>
                <div style="float: right; margin-right: 15px;"><a class="btn btn-block btn-success btn-sm" href="add_recommedation_pdf.php">Add PDF</a></div><br>

                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" id="species_frm" method="POST" action="add_recommedation_pdf.php" enctype="multipart/form-data">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
                                <label id="succes_msg" class="control-label succes_msg" style="color: green;font-size: 14px;display: none;"></label>

                                <div class="form-group">
                                    <label class="col-sm-2 col-md-2 control-label">Recommendation PDF</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="file" id="recom_pdf" name="recom_pdf" value="">
                                        [PDF]
                                        <label id="err_msg_recom_pdf" for="recom_pdf" class="control-label err_msg" style="color: #dd4b39;font-size: 11px;display: none;"></label>
                                        <?php
                                            if(isset($id) && isset($result_recom_pdf)){
                                        ?>
                                        <a href="<?=$result_recom_pdf[0]['file_url']?>"><?=$result_recom_pdf[0]['file_url']?></a>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 col-md-2 control-label">Assign PDF To Model</label>
                                </div>

                                <div class="form-group">
                                <?php
                                    foreach($result_brand_master_arry[0] as $brand_id => $brand_name){
                                    ?>
                                    <div class="col-sm-8 col-md-8">
                                        
                                            <!-- <input type="checkbox" class="brand" name="brand_model[]" value="<?= $brand_id ?>"> -->
                                            <label><?= $brand_name?></label>
                                            <?php
                                            if( isset($result_brand_master_arry[$brand_id]) && count($result_brand_master_arry[$brand_id]) ){
                                            echo "&nbsp;=>&nbsp;";    
                                            foreach ($result_brand_master_arry[$brand_id] as $model_id => $model_name) {
                                                $checked = '';
                                                if( isset($result_recom_pdf_model_new) && array_key_exists($model_id, $result_recom_pdf_model_new)){
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
                                    <?php
                                     
                                     }
                                    ?>
                                </div>

                                
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <input type="hidden" name="frm" value="1">
                                <input type="hidden" name="id" value="<?php echo (isset($_GET['id']) ? $_GET['id']  : '')?>">
                                <button type="submit" class="btn btn-primary" onclick="return validate_recom_pdf();" id="submit">Upload</button>
                                <a href="view_recommedation_pdf.php" class="btn bg-maroon margin">cancel</a>
                                <input type="hidden" name="mode" id="mode" value="<?php echo (isset($_GET['id']) ? 'edit' : 'add' )?>">
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
