<?php
	require 'connection.php';
	if (isset($_GET['encrypt'])) {
		# code...
		
		//echo "string";
		$user=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM users WHERE id='".$_GET['id']."'"));
		//echo "string";
		$result=mysqli_query($link,"SELECT id FROM users WHERE md5(90*13+mobile)='".$_GET['encrypt']."'");
		$row=mysqli_fetch_assoc($result);
		//echo mysqli_error($link);
		//echo "SELECT * FROM users WHERE md5(90*13+mobile)='".$_GET['encrypt']."'";
		//echo mysqli_num_rows($result);
		if(mysqli_num_rows($result)<1){
			echo "<script>alert('Invalid link.');</script>";
			header("Location: index.php");
			exit();
		}
		//echo "string"; 
	}else if (isset($_POST['submit'])) {
		# code...
		if ($_POST['cpass']!=$_POST['pass']) {
			# code...
			$alrt="Password did not match!!";
			echo "<script>alert('$alrt');</script>";

		}else{
			$pass=$_POST['pass'];
			$id=$_POST['id'];
			$flag=mysqli_query($link,"UPDATE login SET password=md5('".$pass."') WHERE user_id='".$id."'");
			//echo "<script>alert('".$mysqli_error($link)."');</script>";
			//echo "UPDATE login SET password=md5('".$pass."') WHERE user_id='".$id."'";
			if ($flag) {
				# code...
				$alrt="Password Successfully Changed!!";
				echo "<script>alert('$alrt');</script>";
				header("Location: index.php");
				exit();
			}
		}
	} else{
		header("Location: index.php");
		exit();
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="css/navigate.css" rel="stylesheet" type="text/css"/>
	<title>Change Password</title>
</head>
<body>
	<?php require 'header.html';?>
<div style="padding-top: 200px">
<center>
	<div class="panel panel-default" style="margin: 50px;width: 50%">
		<div class="panel-heading"><h4>Change Password</h4></div>
		<div class="panel-body">
			<form action="forgot_change_password.php" method="POST" id="myForm">
				<div class="col-xs-12 form-group-sm" style="margin-top: 20px">
					<label>New password</label>
					<input type="password" name="pass" id="pass" class="form-control" style="width: 40%" required>
				</div>
				<div class="col-xs-12 form-group-sm" style="margin-top: 20px">
					<label >Confirm password</label>
					<input type="password" name="cpass" class="form-control" id="cpass" style=" width: 40%" required>
				</div>
				<div class="col-xs-12 form-group-sm">
					<input type="submit" class="btn btn-primary" name="submit"  style="margin-top: 30px;margin-bottom: 30px" value="SUBMIT">
				</div>
				<input type="text" name="id" style="visibility: hidden" value="<?php echo $row['id']?>">
			</form>
		</div>
	</div>
</center>
</div>
</body>
</html>