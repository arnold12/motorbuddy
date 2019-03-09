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
		<?php include_once('header_script.php'); ?>
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
