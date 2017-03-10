<?php
	require '../connection.php';
	session_start();
	if (!isset($_SESSION['user_name']) || $_SESSION['role']!="admin") {
		# code...
		header("Location: ../index.php");
		exit();
	}
	if (isset($_POST['submit'])) {
		# code...
		$curr_pass=$_POST['curr_pass'];
		$new_pass=$_POST['new_pass'];
		$c_new_pass=$_POST['c_new_pass'];
		$result=mysqli_query($link,"SELECT * FROM login WHERE user_id='".$_SESSION['user_name']."' AND password='".md5($curr_pass)."'");
		if(mysqli_num_rows($result)==1){
			if($new_pass==$c_new_pass){
				$result=mysqli_query($link,"UPDATE login SET password = '".md5($new_pass)."' WHERE user_id = '".$_SESSION['user_name']."'");
				if($result){
					echo "<script>alert('Password successfully updated!');";
					header("Location: dashboard.php");
				} else{
					echo "<script>alert('ERROR occured please contact admin! ERROR:".$mysqli_error($link)."');</script>";
					header("Location: dashboard.php");
				}
			} else{
				echo "<script>alert('New password and confirm password did not match!')</script>";
			}
		} else{
			echo "<script>alert('Incorrect password!');</script>";
		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Change password</title>
</head>

<body>
<?php require 'header.html';?>
<?php require 'sidebar.html';?>
<div style="padding-top: 240px; padding-left: 240px; padding-right: 20px">
<div class="panel panel-default">
		<div class="panel-heading"><h4>Change Password</h4></div>
		<div class="panel-body">
		<form action="change_password.php" method="POST">
			<div class="form-inline text-center" style="margin-top: 40px">
			<div class="row">
			<div class="col-lg-3">
				<label>User name:&nbsp;</label><br>
				<input type="text" name="user_name" value="<?php echo $_SESSION['user_name'];?>" class="form-control" disabled>
			</div>
			<div class="col-lg-3">
				<label>Old password:&nbsp;</label><br>
				<input type="password" name="curr_pass" class="form-control" required>
			</div>
			<div class="col-lg-3">
				<label>New password:&nbsp;</label><br>
				<input type="password" name="new_pass" class="form-control" required>
			</div>
			<div class="col-lg-3">
				<label>Confirm new password:&nbsp;</label><br>
				<input type="password" name="c_new_pass" class="form-control" required>
			</div>
			</div>
			</div>

			<div class="form-inline" style="margin-top: 30px">
				<input type="submit" name="submit" class="btn btn-default" value="Update" style="margin-left: 30px">
				<input type="reset" name="reset" class="btn btn-default" value="Clear" style="margin-left: 10px">
			</div>
			</div>
		</form>
		</div>
	</div>
	</div>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>