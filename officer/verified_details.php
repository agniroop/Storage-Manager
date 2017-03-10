<?php
	$msg="";
	$sl=1;
	require '../connection.php';
	session_start();
	/*if ($_SESSION['role']!="officer" || !isset($_SESSION['user_name'])) {
		# code...
		header("Location: ../index.php");
		exit;
	}*/
	if (!isset($_GET['id'])) {
		# code...
		header("Location: verified_indents.php");
	}
	if (isset($_SESSION['msg'])) {
		# code...
		$msg="<script>alert('".$_SESSION['msg']."')</script>";
		unset($_SESSION['msg']);
	}

	$result=mysqli_query($link,"SELECT * FROM indent WHERE id=".$_GET['id']." AND status=2");
	if (mysqli_num_rows($result)<1) {
		# code...
		header("Location: verified_indents.php");
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
		<title>Indent Details</title>
	</head>
	<body>
	<?php require ('header.html'); ?>
	<?php require ('sidebar.html'); ?>
	<div class="row" style="padding-right: 20px; padding-left: 20px; padding-top: 230px">
			<div class="col-lg-2">
    		</div>
    	<div class="col-sm-10">
		<div class="panel panel-default">
			<div class="panel-heading">INDENTOR</div>
			<div class="panel-body">
				<label>ID:</label><?php echo " ".$user_row['id']?><br>
				<label>Name: </label><?php echo " ".$user_row['name'] ?><br>
				<label>Department: </label><?php echo " ".$user_row['department'] ?><br>
				<label>Date: </label><?php echo " ".$row['date'] ?><br>
				<label>Purpose:</label><?php echo " ".$row['purpose']?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
			</div>
			<div class="col-lg-4">
				<div class="alert alert-info">
  					<strong>Info!</strong> For cancelling any item enter 0 in the input.
				</div>
			</div>
			<div class="col-lg-4"></div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">Verified Items</div>
			<div class="panel-body">
				<form action="confirm_indent.php" method="GET">
				<table class="table table-striped text-center table-bordered table-condensed" style="border: 5">
					<thead style="background-color: #bbdefb">
						<tr>
							<th>Sl. No.</th>
							<th>Name</th>
							<th>Company</th>
							<th>Quantity</th>
							<th>Passing quantity</th>
							<th>Available stock</th>
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
				$purpose=mysqli_query($link,"SELECT * FROM purpose_master");
			?>	
				<tr>
					<td><?php echo $sl; $sl=$sl+1;?></td>
					<td><?php echo $item['name'] ?></td>
					<td><?php echo $item['company']?></td>
					<td><?php echo $row2['quantity']?></td>
					<td><input type="number" name="<?php echo $item['id'] ?>" id="<?php echo $item['id'] ?>" class="form-control"  min="0" required onchange="check(<?php echo $item['id'].",".$row2['quantity'];?>)"></td>
					<td><?php echo " ".$row3['stock']?></td>
					<td><select class="form-control" id="purpose<?php echo $item['id']?>" name="purpose<?php echo $item['id']?>" style="visibility: hidden">
					<option></option>
					<?php while($purpose_row=mysqli_fetch_assoc($purpose)){?>
					<option value="<?php echo $purpose_row['id']?>"><?php echo $purpose_row['purpose']?></option>
					<?php } ?>
				</select></td>	
				</tr>
				<?php } ?>
				</tbody>
				</table>
					<div class="form-group" style="margin-top: 20px">
					<input type="submit" name="submit" class="btn btn-block btn-success " value="Verify Indent">
					<!--<a href="cancel_indent.php?id=<?php //echo $_GET['id'] ?>" class="btn btn-danger" style="margin-left: 20px">Cancel indent</a>-->
					</div>
					<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
				</form>
			</div>
		</div>
	</div>
	<?php echo $msg ?>
	<script type="text/javascript">
		function check(item_id,quantity){
			console.log("check");
			if (document.getElementById(item_id).value<quantity && document.getElementById(item_id).value!="") {
				console.log("true");
				document.getElementById("purpose"+item_id).style.visibility="visible";
				document.getElementById("purpose"+item_id).required=true;
			} else{
				document.getElementById("purpose"+item_id).style.visibility="hidden";
				document.getElementById("purpose"+item_id).required=false;
				document.getElementById("purpose"+item_id).options.selectedIndex = 0;
			}
		}
	</script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</body>
	</html>