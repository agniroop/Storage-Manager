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
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		//echo $_POST['contact_mobile']." ** ".$_POST['contact_name'];
		//echo isset($_POST['contact_name'])."---".isset($_POST['contact_mobile']);
		if ($_POST['contact_name']!="" && $_POST['contact_mobile']!="") {
			# code...
			//echo "true";
			$contact_name=$_POST['contact_name'];
			$contact_mobile=$_POST['contact_mobile'];
			$flag=mysqli_query($link,"INSERT INTO supplier_master (name,phone,email,contact_name,contact_mobile) VALUES('".$name."',".$phone.",'".$email."','".$contact_name."',".$contact_mobile.")");
		} elseif ($_POST['contact_name']!="") {
			# code...
			//echo "true";
			$contact_name=$_POST['contact_name'];
			$flag=mysqli_query($link,"INSERT INTO supplier_master (name,phone,email,contact_name) VALUES('".$name."',".$phone.",'".$email."','".$contact_name."')");
		}elseif ($_POST['contact_mobile']!="") {
			# code...
			//echo "true";
			$contact_mobile=$_POST['contact_mobile'];
			$flag=mysqli_query($link,"INSERT INTO supplier_master (name,phone,email,contact_mobile) VALUES('".$name."',".$phone.",'".$email."',".$contact_mobile.")");
		}else{
			$flag=mysqli_query($link,"INSERT INTO supplier_master (name,phone,email) VALUES('".$name."',".$phone.",'".$email."')");
			//echo "INSERT INTO supplier_master (name,phone,email) VALUES('".$name."',".$phone.",'".$email."')";
		}
		if ($flag) {
			# code...
			echo "here";
			$id=mysqli_fetch_assoc(mysqli_query($link,"SELECT max(id) as id FROM supplier_master"));
			//echo mysqli_error($link);
			//echo count($group_id);
			foreach ($group_id as $g) {
				# code...
				//echo "loop";
				$flag=mysqli_query($link,"INSERT INTO supplier_group VALUES(".$id['id'].",".$g.")");
			}
			if($flag)
				echo "<script>alert('Supplier successfully added.');</script>";
			else
				echo "<script>alert('".mysqli_error($link)."');</script>";
		}else
			echo "<script>alert('".mysqli_error($link)."');</script>";
				
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
	<title>Add supplier</title>
</head>
<body>
	<?php require ('header.html');?>
	<?php require ('sidebar.html');?>
	<div style="padding-top: 230px; padding-left: 240px; padding-right: 20px;">
	<div class="panel panel-default">
		<div class="panel-heading"><h4>Add new supplier</h4></div>
		<div class="panel-body">
			<form action="add_supplier.php" method="POST">
				<div class="col-xs-12 form-group-sm">
					<div class="col-xs-2" style="padding-left: 0px">
						<label>Group</label>
						<select name="group[]" id="group" class="form-control" required multiple >
							<?php while($group_row=mysqli_fetch_assoc($group)){?>
							<option value="<?php echo $group_row['id']?>"><?php echo $group_row['group_name']?></option>
							<?php } ?>
							
						</select>
					</div>
					<div class="col-xs-10" id="add_here"></div>
				</div>
				<div class="col-xs-4 form-group-sm">
					<label style="padding-top: 15px">Name</label>
					<input type="text" name="name" class="form-control" required>
				</div>
				<div class="col-xs-4 form-group-sm">
					<label style="padding-top: 15px">E-mail</label>
					<input type="email" name="email" class="form-control" required>
				</div>
				<div class="col-xs-4 form-group-sm">
					<label style="padding-top: 15px">Phone no.</label>
					<input type="number" name="phone" class="form-control" required>
				</div>
				<div class="col-xs-4 form-group-sm">
					<label style="padding-top: 15px">Contact person name</label>
					<input type="text" name="contact_name" class="form-control">
				</div>
				<div class="col-xs-4 form-group-sm">
					<label style="padding-top: 15px">Contact person mobile no.</label>
					<input type="number" name="contact_mobile" class="form-control">
				</div>
				<div class="col-xs-12 form-group-sm">
					<input type="submit" name="submit" value="SUBMIT" class="btn btn-primary btn-block" style="margin-top: 30px">
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