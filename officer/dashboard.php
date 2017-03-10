<?php
	require '../connection.php';
	session_start();
	if (!isset($_SESSION['user_name']) || $_SESSION['role']!="officer") {
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
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Officer Dashboard</title>
</head>
<body>
<?php require ('header.html');?>
<?php require ('sidebar.html');?>
<div style="padding-top: 230px; padding-right:20px">
	<div class="panel panel-default text-center" style=" margin-left: 240px;">
		<div class="panel-heading"><h2 >Welcome&nbsp; <?php echo $user['name'] ?></h2></div>
		<div class="panel-body">
		<h4 style="margin-top: 30px"><strong>Last login:</strong><?php echo $log['login_time']?></h4>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>