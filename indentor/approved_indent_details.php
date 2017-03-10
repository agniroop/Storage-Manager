<?php
	$msg="";
	$sl=1;
	require '../connection.php';
	session_start();
	/*if ($_SESSION['role']!="indentor" || !isset($_SESSION['user_name'])) {
		# code...
		header("Location: ../index.php");
		exit();
	}*/

	if (!isset($_GET['id'])) {
		# code...
		header("Location: approved_indents.php");
		exit();
	}

	if (isset($_SESSION['msg'])) {
		# code...
		$msg="<script>alert('".$_SESSION['msg']."')</script>";
		unset($_SESSION['msg']);
	}
	$temp=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM indent WHERE id=".$_GET['id'] ));
	if($_SESSION['user_name']!= $temp['user_id']){
		header("Location: approved_indents.php");
		exit();
	}
	$result=mysqli_query($link,"SELECT * FROM indent WHERE id=".$_GET['id']." AND status=2");
	if (mysqli_num_rows($result)<1) {
		# code...
		header("Location: approved_indents.php");
	}
	$row=mysqli_fetch_assoc($result);
	$user=mysqli_query($link,"SELECT * FROM users WHERE id=".$row['user_id']);
	$user_row=mysqli_fetch_assoc($user);
	$indent_items=mysqli_query($link,"SELECT * FROM indent_item WHERE indent_id=".$row['id']);
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
		<title>Canceled Indent Details</title>
	</head>
	<body>
	<?php require ('header.html'); ?>
	<?php require ('sidebar.html'); ?>
	<div class="row" style="padding-right: 20px; padding-left: 20px; padding-top: 230px">
		<div class="col-sm-2"></div>
    	<div class="col-sm-10">
		<div class="panel panel-default">
			<div class="panel-heading">Indentor</div>
			<div class="panel-body">
				<label>ID:</label><?php echo " ".$user_row['id']?><br>
				<label>Name: </label><?php echo " ".$user_row['name'] ?><br>
				<label>Department: </label><?php echo " ".$user_row['department'] ?><br>
				<label>Date: </label><?php echo " ".$row['date'] ?><br>
				<label>Purpose:</label><?php echo " ".$row['purpose']?>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">ITEMS</div>
			<div class="panel-body">
				<table class="table table-striped text-center table-bordered table-condensed" style="border: 5">
					<thead style="background-color: #bbdefb">
						<tr>
							<th>Sl. No.</th>
							<th>Name</th>
							<th>Company</th>
							<th>Quantity</th>
							<th>Purpose</th>
						</tr>
					</thead>
					<tbody style="background-color: #e3f2fd;">
			<?php while ($row2=mysqli_fetch_assoc($indent_items)) {
				# code...
				$stock=mysqli_query($link,"SELECT sum(current_stock) as stock FROM stock WHERE item_id=".$row2['item_id']);
				$row3=mysqli_fetch_assoc($stock);
				$items=mysqli_query($link,"SELECT * FROM item_master WHERE id=".$row2['item_id']);
				$item=mysqli_fetch_assoc($items);
				$purpose=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM purpose_master WHERE id=".$row2['officer_purpose_id']));
			?>	
				<tr>
					<td><?php echo $sl; $sl=$sl+1;?></td>
					<td><?php echo $item['name'] ?></td>
					<td><?php echo $item['company']?></td>
					<td><?php echo $row2['quantity']?></td>
					<td><?php echo $purpose['purpose']?></td>	
				</tr>
				<?php }?>
				</tbody>	
				</table>
					<form id="myform" action="form.php" method="POST">
					<input type="submit" name="submit" class="btn btn-success" value="ACCEPT">
					<a href="cancel_indent.php?indent_id=<?php echo $_GET['id'];?>" class="btn btn-danger">CANCEL</a>
					<input type="hidden" name="indent_id" value="<?php echo $_GET['id'] ?>">
			</div>
		</div>
	</div>
	<?php echo $msg ?>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</body>
	</html>