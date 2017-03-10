<?php
	require '../connection.php';
	session_start();
	if (!isset($_SESSION['user_name']) || $_SESSION['role']!="issuer") {
		# code...
		header("Location: ../index.php");
		exit();
	}
	if (isset($_POST['submit'])) {
		# code...
	
		$indent_id=$_POST['indent_id'];
		$result=mysqli_query($link,"SELECT * FROM indent WHERE id=".$indent_id." AND status=3");
		//echo "SELECT * FROM indent WHERE id=".$indent_id." AND status=3";
		if (mysqli_num_rows($result)<1) {
			# code...
			echo "<script>alert('All items of this indent had been delivered or Indent ID is Invalid!!');</script>";
		}else{
			$flag=mysqli_query($link,"UPDATE indent SET status=4 WHERE id=".$indent_id);
			if ($flag) {
				# code...
				$alrt="The Indent is Updated!!";
				echo "<script>alert('".$alrt."');</script>";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Issue items</title>
</head>
<body>
	<?php require ('header.html');?>
	<?php require ('sidebar.html');?>
<div style="padding-top: 230px; padding-left: 240px; padding-right:20px;" >
	<div class="text-center panel panel-default" style="width: 100%;" >
		<div class="panel-heading" style="width: 100%;"><h3>Issue Items</h3></div>
		<form action="issue_item.php" method="POST">
			<div class="panel-heading form-inline" style="padding-top: 20px;">
				<label>Indent id:&nbsp;</label>
				<input type="text" name="indent_id" class="form-control" style="margin-left: 10px" required>
			</div>
			<div class="form-inline" style="margin-top: 20px; margin-bottom: 10px;">
				<input type="submit" name="submit" class="btn btn-primary" value="Details">
			</div>
		</form>
	</div>
</div>
</body>
</html>