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
		$role_id=$_POST['role'];
		$username=$_POST['username'];
		$pass=$_POST['password'];
		$cpass=$_POST['cpassword'];
		$mobile=$_POST['mobile'];
		$email=$_POST['email'];
		$name=$_POST['name'];
		$department=$_POST['department'];
		if ($pass!=$cpass) {
					# code...
			echo "<script>alert('Password did not match.');</script>";
		} else{
			if (mysqli_query($link,"INSERT INTO users VALUES('".$username."','".$name."','".$department."','".$email."',".$mobile.")")) {
				# code...
				mysqli_query($link,"INSERT INTO login VALUES('".$username."','".md5($pass)."',".$role_id.")");
				echo "<script>alert('User successfully added.');</script>";
				?>
				<form id="form" action="send_email_new_user.php" method="POST">
					<input type="hidden" name="id" value="<?php echo $username;?>">
					<input type="hidden" name="pass" value="<?php echo $pass;?>">
				</form>
				<script type="text/javascript">
					document.getElementById("form").submit();
				</script>

				<?php
			}else{
				echo "<script>alert(".mysqli_error($link).");</script>";
			}
		}		
	}else{
	$role=mysqli_query($link,"SELECT * FROM role_master");
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Add User</title>
</head>
<body>
	<?php require ('header.html');?>
	<?php require ('sidebar.html');?>
	<div style="padding-top: 230px; padding-left: 240px; padding-right: 20px;">
	<div class="panel panel-default">
		<div class="panel-heading"><h4>Add user</h4></div>
		<div class="panel-body">
			<form action="add_user.php" method="POST">
				<div class="col-xs-12 form-group-sm">
					<label>Role</label>
					<select name="role" required class="form-control" style="width: 10%">
						<option></option>
						<?php while($role_name=mysqli_fetch_assoc($role)){?>
						<option value="<?php echo $role_name['id']?>"><?php echo $role_name['role']?></option>
						<?php }?>
					</select>
				</div>
				
				<div class="col-xs-4 form-group-sm" style="margin-top: 10px">
					<label>Username</label>
					<input type="text" name="username" class="form-control" required>
				</div>
				<div class="col-xs-4 form-group-sm" style="margin-top: 10px">
					<label>Name</label>
					<input type="text" name="name" class="form-control" required>
				</div>
				<div class="col-xs-4 form-group-sm" style="margin-top: 10px">
					<label>Department</label>
					<input type="text" name="department" class="form-control" required>
				</div>
				<div class="col-xs-6 form-group-sm" style="margin-top: 10px">
					<label>E-mail</label>
					<input type="email" name="email" class="form-control" required>
				</div>
				<div class="col-xs-6 form-group-sm" style="margin-top: 10px">
					<label>Mobile No.</label>
					<input type="number" name="mobile" class="form-control" required>
				</div>
				<div class="col-xs-6 form-group-sm" style="margin-top: 10px">
					<label>Password</label>
					<input type="password" name="password" class="form-control" required>
				</div>
				<div class="col-xs-6 form-group-sm" style="margin-top: 10px">
					<label>Confirm password</label>
					<input type="password" name="cpassword" class="form-control" required>
				</div>
				<div class="col-xs-12 form-group-sm" style="margin-top: 30px">
					<input type="submit" name="submit" value="SUBMIT" class="btn btn-primary btn-block">
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>
<?php } ?>