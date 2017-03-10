<?php
	require '../connection.php';
	session_start();
	/*if($_SESSION['role']!="officer" || !isset($_SESSION['user_name'])){
		header("Location: ../index.php");
		exit;
	}*/
	if(!isset($_GET['search'])){
		$result=mysqli_query($link,"SELECT * FROM indent WHERE status in (2,3) AND passed_by_id='".$_SESSION['user_name']."'");
	} elseif($_GET['search']=="date"){
		$result=mysqli_query($link,"SELECT * FROM indent WHERE status in (2,3) AND passed_by_id='".$_SESSION['user_name']."' ORDER BY ".$_GET['search']." DESC");
	} elseif ($_GET['search']=="department") {
		# code...
		$result=mysqli_query($link,"SELECT * FROM indent WHERE status in (2,3) AND passed_by_id='".$_SESSION['user_name']."' ORDER BY ".$_GET['search']);
	}
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
		<title>Verified Indents</title>
	</head>
	<body>
	<?php require ('header.html');?>
	<?php require ('sidebar.html');?>
	<div style="padding-top: 230px; padding-left: 240px; padding-right: 20px">
		<div class="well well-lg">
	<?php  if(mysqli_num_rows($result)>0)
	{
	?>
				<label style="margin-top: 10px; margin-left: 80%">Sort By:&nbsp;</label>
				<select onchange="check()" id="sort">
					<option value="-1"></option>
					<option value="0">Date Asc</option>
					<option value="1">Date Dsc</option>
					<option value="2">Department</option>
				</select>
		<div class="table-responsive" style="margin-top: 10px">
			<table class="table table-striped text-center table-bordered table-condensed" >
				<caption class="text-center" style="margin-bottom: 10px"><b>INDENTS</b></caption>
				<thead style="background-color: #bbdefb;">
					<tr>
						<th>Name</th>
						<th>Department</th>
						<th>Date</th>
						<th></th>
					</tr>
				</thead>
				<tbody style="background-color: #e3f2fd;">
	<?php while($row=mysqli_fetch_assoc($result)){
		$result2=mysqli_query($link,"SELECT * FROM users WHERE id=".$row['user_id']);
		$row2=mysqli_fetch_assoc($result2);
		?>
				<tr>
					<td><?php echo $row2['name'] ?></td>
					<td><?php echo $row2['department'] ?></td>
					<td><?php echo $row['date'] ?></td>
					<td><a href="verified_details.php?id=<?php echo $row['id'] ?>" class="btn btn-primary btn-block">Edit This</a></td>
				</tr>
	<?php } ?>
	</tbody>
	</table>
	</div>
	</div>
</div>
	<?php } elseif(mysqli_num_rows($result)==0){?>
	<div class="row" style="padding-bottom: 200px">
		<div class="col-lg-4"></div>
		<div class="col-lg-4">
			<div class="alert alert-info">
  				<strong>Info!</strong> No Indents, have been Verified.
			</div>
		</div>
		<div class="col-lg-4"></div>
	</div>
	<?php } ?>
	<script type="text/javascript">
		function check(){
			var val=document.getElementById("sort").value;
			if (val==0) {
				window.location="verified_indents.php";
			} else if(val==1){
				window.location="verified_indents.php?search=date";
			} else if(val==2){
				window.location="verified_indents.php?search=department";
			}
		}
	</script>
	</body>
	</html>