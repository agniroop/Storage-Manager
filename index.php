<?php

	$msg="";
	require 'connection.php';
	session_start();
	date_default_timezone_set('Asia/Kolkata');
	if (isset($_POST['submit']) && !isset($_SESSION['user_name']) && !isset($_SESSION['role'])) {
		# code...
		
		$user_name=$_POST['user_name'];
		$password=$_POST['password'];
		$encrypted_password=md5($password);
		$result=mysqli_query($link,"SELECT role_id FROM login WHERE user_id='".$user_name."' AND password='".$encrypted_password."'");
		//$msg="done";
		if(mysqli_num_rows($result)==1){
			$row=mysqli_fetch_assoc($result);
			$result=mysqli_query($link,"SELECT * FROM role_master WHERE id=".$row['role_id']);
			$row=mysqli_fetch_assoc($result);
			mysqli_query($link,"INSERT INTO user_log (user_id) VALUES ('".$user_name."')");
			$_SESSION['user_name']=$user_name;
			$_SESSION['role']=$row['role'];
			//$msg="INSERT INTO user_log (user_id) VALUES ('".$user_name."')";
			if ($row['role']=="admin") {
				# code...
				header("Location: admin/dashboard.php");
				exit();
			} else if($row['role']=='indentor'){
				# code...
				header("Location: indentor/dashboard.php");
				exit();
			} else if($row['role']=='issuer'){
				header("Location: issuer/dashboard.php");
				exit();
			} else if($row['role']=='officer'){
				header("Location: officer/dashboard.php");
				exit();
			}		
		} else{
			$msg="Incorrect username or password!";
		}	
	} else if(isset($_SESSION['user_name'])){
		//echo "<script>alert('".$_SESSION['role']."');</script>";
		if ($_SESSION['role']==="admin") {
				# code...
				header("Location: admin/dashboard.php");
				exit();
			} else if($_SESSION['role']==='indentor'){
				# code...
				header("Location: indentor/dashboard.php");
				exit();
			} else if($_SESSION['role']==='issuer'){
				header("Location: issuer/dashboard.php");
				exit();
			} else if($_SESSION['role']==='officer'){
				header("Location: officer/dashboard.php");
				exit();
			}		
	}
?>
<html>
<head>
	<title>Welcome</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="css/navigate.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<?php require ('header.html');?>
	<div style="padding-top: 250px; padding-left: 400px; padding-right: 400px;">
		<div class="panel panel-default text-center">
		<div class="panel-heading"><h2 style="font-weight: 600; color: #000080;">LOGIN</h2></div>
		<div class="panel-body" style="padding-top: 30px">
			<form action="index.php" method="POST">
			<div class=" col-xs-12 form-group-sm">
				<div class="col-xs-12 form-group-sm">
				<input type="text" class="form-control" placeholder="Username" name="user_name" maxlength="12" style="margin-top: 20px;" required>
				</div>
				<div class="col-xs-12 form-group-sm">
				<input type="password" class="form-control" placeholder="Password" name="password" minlength="10" style="margin-top: 20px;" required>
				</div>
				<div class="col-xs-12 form-group-sm">
				<input type="submit" class="btn btn-primary form-control" value="Login" name="submit" style="background-color: #000080; margin-top: 20px;">
				</div>
			</div>
			</form>
			<div class=" col-xs-12"><h4 style="color: orange;"><?php echo $msg;?></h4></div>
		<div style="text-align: right; padding-right: 30px"><h5><a style="color: #2196f3
; font-weight: 600;" href="send_email.php">Forgot My Password?</a></h5></div>
		</div>
		</div>
	</div>

    	<script type="text/javascript" src="js/bootstrap.min.js"></script>
    	<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
</body>
</html>