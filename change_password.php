<?php
require_once 'config.php';

if (!isUserLoggedIn()) {
    header("Location: logout.php");
}
if($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['action']) && $_POST['action'] == '1')
{
    $DBI = new Db();
	$error = 0;
	$oldpassword	=	trim($_POST['oldpassword']);
    $newpassword	=	trim($_POST['newpassword']);
	
	if($oldpassword == ''){
		$error = 1;
	}
	if($newpassword == ''){
		$error = 1;
	}
	
	if(!$error)
			{
				$sql = "SELECT password FROM tbl_mb_user WHERE password = '".$DBI->sql_escape($oldpassword)."' AND id = '".$DBI->sql_escape($_SESSION['id'])."' LIMIT 1";
				$result = $DBI->query($sql);
				if(mysql_num_rows($result) == 0)
				{
						$error = 2;
						header("Location: change_password.php?errmsg=".$error."");die();
				}
				else
				{	
					$update_sql = "UPDATE `tbl_mb_user` SET `password` = '".$newpassword."' WHERE id = '".$_SESSION['id']."'";
		            $ret = $DBI->query($update_sql);
					$error = 3; 
					header("Location: change_password.php?errmsg=".$error."");die();
				}
			}
			//echo $error;exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Advanced form elements</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">		
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		<!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
		 <!-- jQuery UI 1.11.4 -->
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
		<!-- AdminLTE for master validation and submit purposes -->
        <script src="dist/js/common_master.js"></script>
		<!-- AdminLTE App -->
		<script src="dist/js/app.min.js"></script>
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
                        CHANGE PASSWORD
                        <!--<small>Preview</small>-->
                    </h1>
                </section>
				
				<!--<div style="float: right; margin-right: 15px;"><a href="master_service.php" class="btn btn-block btn-success btn-sm">Add service</a></div>-->
				<br>
                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->

                    <div class="box box-info">

                        <form class="form-horizontal" action="#" method="post" name="change_admin_pass" id="change_admin_pass">
                            <div class="box-body">
                                <!-- Inquiry form general info start -->
								<?php
									$msg = '';
									$label = '';
									if(isset($_GET['errmsg']) && $_GET['errmsg'] == 1)
									{
										$label = '<center><label id="err_msg" for="oldpass" class="control-label" style="color: red;">Please Enter The Required Field</label></center><br>';
									}
									if(isset($_GET['errmsg']) && $_GET['errmsg'] == 2)
									{
										$label = '<center><label id="err_msg" for="oldpass" class="control-label" style="color: red;">Your old password is wrong</label></center><br>';
									}
									if(isset($_GET['errmsg']) && $_GET['errmsg'] == 3)
									{
										$label = '<center><label id="err_msg" for="oldpass" class="control-label" style="color: blue;">Your new password is UPDATE Sucessfully</label></center><br>';
									}
									?>
					
					             <?php echo $label;?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Old Password</label>
                                    <div class="col-sm-4">
                                        <input type="password" class="form-control input-sm" id="oldpassword" name="oldpassword" value="" placeholder="Old Password">
										<label id="err_msg_oldpassword" for="oldpassword" class="control-label" style="color: #dd4b39;"></label>
								    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">New Password</label>
                                    <div class="col-sm-4">
                                        <input type="password" class="form-control input-sm" id="newpassword" name="newpassword" value="" placeholder="New Password">
										<label id="err_msg_newpassword" for="newpassword" class="control-label" style="color: #dd4b39;"></label>
								    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Confirm New Password</label>
                                    <div class="col-sm-4">
                                        <input type="password" class="form-control input-sm" id="confirm_password" name="confirm_password" value="" placeholder="Confirm New Password">
										<label id="err_msg_confirm_password" for="confirm_password" class="control-label" style="color: #dd4b39;"></label>
								    </div>
                                </div>
								 </div><!-- /.box-body -->
                            <div class="box-footer">
								<input type="hidden" name="action" id="action" value="1">
                                <button type="submit" id="submit" class="btn btn-primary" onclick="return validate_change_password();">Submit</button>
                                <a href="index.php" class="btn bg-maroon margin">cancel</a>
                            </div>
                        </form>

                    </div><!-- /.box -->
                </section><!-- /.content -->
			</div><!-- /.content-wrapper -->
			<script>
			function validate_change_password()
			{	
					$("#err_msg_oldpassword").html('');
					if($("#oldpassword").val() == '')
					{
						$("#err_msg_oldpassword").html('Please Enter Old Password');
						$("#err_msg_oldpassword").focus();
						return false;
					}
					
					$("#err_msg_newpassword").html('');
					if($("#newpassword").val() == '')
					{
						$("#err_msg_newpassword").html('Please Enter New Password');
						$("#err_msg_newpassword").focus();
						return false;
					}
					
					$("#err_msg_confirm_password").html('');
					if($("#confirm_password").val() == '')
					{
						$("#err_msg_confirm_password").html('Please Enter Confirm Password');
						$("#err_msg_confirm_password").focus();
						return false;
					}
					
					$("#err_msg_confirm_password").html('');
					if(!$("#newpassword").val().match($("#confirm_password").val()))
					 {
						$("#err_msg_confirm_password").html("Your New Password and Confirm New Password not match.");
						$("#err_msg_confirm_password").focus();
                        return false;
					}
                     return true;
	
            }

			</script>
			<?php include_once 'footer.php';?>
  
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div><!-- ./wrapper -->
    </body>
</html>
