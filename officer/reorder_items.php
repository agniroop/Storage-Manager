<?php
	session_start();
	require '../connection.php';
	if (!isset($_SESSION['user_name']) || $_SESSION['role']!="officer") {
		# code...
		header("Location: ../index.php");
		exit();
	}
	$items=mysqli_query($link,"SELECT * FROM item_master,stock WHERE item_master.id=stock.item_id AND stock.current_stock<item_master.reorder_lable");
	
$count=1;
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Reorder items</title>
</head>
<body>
	<?php require ('header.html');?>
	<?php require ('sidebar.html');?>
<div class="panel panel-default" style="padding-top: 230px; padding-left: 240px; padding-right: 20px;">
	<div class="panel-heading"><h4>Items with low stocks</h4></div>
	<div class="panel-body">
		<table class="table table-bordered table-condensed table-striped" style="margin-top:  20px;">
			<thead style="background-color: #bbdefb;">
				<tr>
					<th>Sl.no.</th>
					<th>Item</th>
					<th>Group</th>
					<th>Current stocks</th>
				</tr>
			</thead>
			<tbody style="background-color: #e3f2fd;">
				<?php while($item=mysqli_fetch_assoc($items)){
					$group=mysqli_fetch_assoc(mysqli_query($link,"SELECT group_name FROM group_master WHERE id=".$item['group_id']));
					$stock=mysqli_fetch_assoc(mysqli_query($link,"SELECT current_stock FROM stock WHERE item_id=".$item['id']));
					?>
					<tr>
						<td><?php echo $count;?></td>
						<td><?php echo $item['name'];?></td>
						<td><?php echo $group['group_name'];?></td>
						<td><?php echo $stock['current_stock'];?></td>
					</tr>
					<?php $count++; }?>
			</tbody>
		</table>
	</div>
</div>
</body>
</html>