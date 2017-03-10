<?php
	require '../connection.php';
	session_start();
	/*if (!isset($_SESSION['user_name']) || $_SESSION['role']!="issuer") {
		# code...
		header("Location: http://localhost/store/index.php");
		exit();
	}*/
	if (isset($_POST['submit'])) {
		# code...
		$group_id=$_POST['group'];
		$name=$_POST['name'];
		$room=$_POST['room'];
		$store_loc=$_POST['store_loc'];
		$reorder=$_POST['reorder'];
		if (isset($_POST['new_group'])) {
			# code...
			mysqli_query($link,"INSERT INTO group_master(group_name) VALUES('".$_POST['new_group']."')");
			$group=mysqli_fetch_assoc(mysqli_query($link,"SELECT max(id) as id FROM group_master"));
			$group_id=$group['id'];
			echo mysqli_error($link);
		}
		if (isset($_POST['company']) && isset($_POST['desc'])) {
			# code...
			$company=$_POST['company'];
			$desc=$_POST['desc'];
			$flag=mysqli_query($link,"INSERT INTO item_master(name,group_id,description,company,room,store_location,reorder_lable) VALUES ('".$name."',".$group_id.",'".$desc."','".$company."','".$room."','".$store_loc."',".$reorder.")");
		}elseif (isset($_POST['company'])) {
			# code...
			$company=$_POST['company'];
			$flag=mysqli_query($link,"INSERT INTO item_master(name,group_id,company,room,store_location,reorder_lable) VALUES ('".$name."',".$group_id.",'".$company."','".$room."','".$store_loc."',".$reorder.")");
		}elseif (isset($_POST['desc'])) {
			# code...
			$desc=$_POST['desc'];
			$flag=mysqli_query($link,"INSERT INTO item_master(name,group_id,description,room,store_location,reorder_lable) VALUES ('".$name."',".$group_id.",'".$desc."','".$room."','".$store_loc."',".$reorder.")");
		}else{
			$flag=mysqli_query($link,"INSERT INTO item_master(name,group_id,room,store_location,reorder_lable) VALUES ('".$name."',".$group_id.",'".$room."','".$store_loc."',".$reorder.")");
		}
		if ($flag) {
			# code...
			echo "<script>alert('Item successfully added');</script>";
		}
		
	}
	$group=mysqli_query($link,"SELECT * FROM group_master");
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Add items</title>
</head>
<body>
	<?php require ('header.html');?>
	<?php require ('sidebar.html');?>
	<div style="padding-top: 230px; padding-left: 240px; padding-right: 20px;">
	<div class="panel panel-default">
		<div class="panel-heading"><h4>Add new item</h4></div>
		<div class="panel-body">
			<form action="add_item.php" method="POST" ">
				<div class="col-xs-12 form-group-sm" >
					<div class="col-xs-2" style="padding-left: 0px">
						<label>Group</label>
						<select name="group" id="group" class="form-control" required  onchange="check()">
							<option></option>
							<option value="0">new group</option>
							<?php while($group_row=mysqli_fetch_assoc($group)){?>
							<option value="<?php echo $group_row['id']?>"><?php echo $group_row['group_name']?></option>
							<?php }?>
						</select>
					</div>
					<div class="col-xs-10" id="add_here"></div>
				</div>
				<div class="col-xs-6 form-group-sm">
					<label style="padding-top: 15px">Name</label>
					<input type="text" name="name" class="form-control" required>
				</div>
				<div class="col-xs-6 form-group-sm">
					<label style="padding-top: 15px">Company</label>
					<input type="text" name="company" class="form-control" >
				</div>
				<div class="col-xs-12 form-group-sm">
					<label style="padding-top: 15px">Description</label>
					<textarea class="form-control" name="desc" ></textarea>
				</div>
				<div class="col-xs-4 form-group-sm">
					<label style="padding-top: 15px">Room</label>
					<input type="text" name="room" class="form-control" >
				</div>
				<div class="col-xs-4 form-group-sm">
					<label style="padding-top: 15px">Store location</label>
					<input type="text" name="store_loc" class="form-control">
				</div>
				<div class="col-xs-4 form-group-sm">
					<label style="padding-top: 15px">Reorder label</label>
					<input type="number" name="reorder" class="form-control" required>
				</div>
				<div class="col-xs-12 form-group-sm" style="margin-top: 30px">
					<input type="submit" name="submit" value="SUBMIT" class="btn btn-primary btn-block">
				</div>
			</form>
		</div>
	</div>
</div>
	<script type="text/javascript">
		function check(){
			if(document.getElementById("new_element")){
				document.getElementById("add_here").removeChild(document.getElementById("new_element"));
				document.getElementById("add_here").removeChild(document.getElementById("new_lable"));
			}
			if (document.getElementById("group").value=="0") {
				console.log("here");
				var new_lable=document.createElement("label");
				new_lable.setAttribute("id","new_lable");
				new_lable.innerHTML="New group";
				var new_element=document.createElement("input");
				new_element.setAttribute("name","new_group");
				new_element.setAttribute("id","new_element");
				new_element.setAttribute("style","width: 20%")
				new_element.setAttribute("class","form-control");
				new_element.setAttribute("type","text");
				new_element.required=true;
				document.getElementById("add_here").appendChild(new_lable);
				document.getElementById("add_here").appendChild(new_element);
			}
		}
	</script>
</body>
</html>