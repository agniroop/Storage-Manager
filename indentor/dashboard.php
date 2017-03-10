<?php
	require '../connection.php';
	session_start();
	if (!isset($_SESSION['user_name']) || $_SESSION['role']!="indentor") {
		# code...
		header("Location: ../index.php");
		exit;
	}
	$result=mysqli_query($link,"SELECT * FROM users WHERE id='".$_SESSION['user_name']."'");
	$user=mysqli_fetch_assoc($result);
	$log=mysqli_fetch_assoc(mysqli_query($link,"SELECT login_time FROM user_log WHERE user_id='".$_SESSION['user_name']."' AND logout_time IS NOT NULL ORDER BY login_time DESC"));
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Indentor Dashboard</title>
</head>
<body>
<?php require ('header.html');?>
<?php require 'sidebar.html';?>
<div style="padding-top: 240px">
<div class="col-lg-2"> 
</div>
<div class="col-lg-10">
	<div class="panel panel-default text-center">
		<div class="panel-heading"><h2 style="margin: 0px">Welcome&nbsp; <?php echo $user['name'] ?></h2></div>
		<div class="panel-body">
		<h4><strong>Last login:&nbsp;</strong><?php echo $log['login_time']?></h4>
		</div>
	</div>
</div>
</div>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>