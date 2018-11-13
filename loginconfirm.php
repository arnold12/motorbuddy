<?php
require_once 'config.php';
if($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['login']) && $_POST['login'] == 'loginnow')
{
	$DBI = new Db();
	$error = 0;
    $username	=	trim($_POST['username']);
    $password	=	trim($_POST['password']);
	$rememberme	=	(isset($_POST['rememberme']) && $_POST['rememberme'] == 1) ? $_POST['rememberme'] : 0 ;
	
	if($username == ''){
		$error = 1;
	}
	if($password == ''){
		$error = 1;
	}
	
	if(!$error){
		
		$sql = "SELECT id, username FROM tbl_mb_user WHERE username = '".$DBI->sql_escape($username)."' AND password = '".$DBI->sql_escape($password)."' LIMIT 1";
		
		$result = $DBI->query($sql);
		
		if(mysql_num_rows($result) == 0){
			$error = 1;
		} else {
			$row = $DBI->get_array($sql);
			
			$_SESSION['id'] = $row['id'];
			$_SESSION['username'] = $row['username'];
			
			if($rememberme){
				
			}
			
			header("Location: index.php");die();
		} 
	}
	
	if($error){
		header("Location: login.php?errmsg=1");die();
	}
}
?>