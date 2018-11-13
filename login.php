<?php
    session_start();
    if(isset($_SESSION['username'])){
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Motorbuddy | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
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
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
	
	<script type="text/javascript">
	 $(document).ready(function() {
		 $( "#signin" ).click(function() {
                     $("#username_error").html("");
                     $("#password_error").html("");
                     if($("#username").val() === '')
                     {
                        $("#username_error").html("Please Enter Username.");
                        $("#username_error").focus();
                        return false;
                     }
                     if($("#password").val() === '')
                     {  
                        $("#password_error").html("Please Enter Password.");
                        $("#password_error").focus();
                        return false;
                     }
                     return true;
		 }); 
	 });
	</script>
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <!--<div class="login-logo">
        <a href="login.php"><img alt="logo" class="" height="81px" width="358px" src="dist/img/logo.jpg"></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="loginconfirm.php" method="post">
          <div class="form-group has-feedback">
			<?php
			$msg = '';
			if(isset($_GET['errmsg']) && $_GET['errmsg'] == 1){
				$msg = 'Invalid login details';
			}
			?>
            <label id="username_error" for="username" class="control-label" style="color: #dd4b39;"><?=$msg?></label>
            <input type="text" class="form-control" placeholder="Username" id="username" name="username">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
             <label id="password_error" for="password" class="control-label" style="color: #dd4b39;"></label>
            <input type="password" class="form-control" placeholder="Password" id="password" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" id="rememberme" name="rememberme" value="1"> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat" name="login" value="loginnow" id="signin">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

   
  </body>
</html>
