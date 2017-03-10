<?php
	require '../connection.php';
	session_start();
	/*if (!isset($_SESSION['user_name']) || $_SESSION['role']!="admin") {
		# code...
		header("Location: http://localhost/store/index.php");
		exit();
	}*/
	if (isset($_POST['submit1'])) {
		# code...
		$group=$_POST['group'];
		mysqli_query($link,"INSERT INTO group_master(group_name) VALUES ('".$group."')");
		echo "<script>alert('Submission successful.')</script>";
	}elseif (isset($_POST['submit2'])) {
		# code...
		$flag=true;
		//echo "submit2";
		$group=$_POST['group'];
		$supp=$_POST['supp'];
		foreach ($group as $g) {
			# code...
			$row=mysqli_query($link,"SELECT * FROM supplier_group WHERE supp_id=".$supp." AND group_id=".$g);
			if (mysqli_num_rows($row)>0) {
				# code...
				$g_name=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM group_master WHERE id=".$g));
				echo "<script>alert('Group ".$g_name['group_name']." already exists for this supplier');</script>";
				$flag=false;
			}
		}
		if($flag){
			foreach($group as $g){
				$flag=mysqli_query($link,"INSERT INTO supplier_group VALUES(".$supp.",".$g.")");

			}
		}
		if ($flag) {
			# code...
			echo "<script>alert('Submission successful.');</script>";
		}
	}
	$groups=mysqli_query($link,"SELECT * FROM group_master ORDER BY group_name");
	echo mysqli_error($link);
	$supps=mysqli_query($link,"SELECT * FROM supplier_master ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Add Group</title>
</head>
<body>
	<?php require ('header.html');?>
	<?php require ('sidebar.html');?>
	<div style="padding-top: 230px; padding-left: 240px; padding-right: 20px;">
	<div class="panel panel-default text-center">
		<div class="panel-heading"><h3>ADD GROUPS</h3></div>
		<div class="panel-body">
			<div class="text-center">
				<button class="btn btn-primary" onclick="create1()">ADD GROUP</button>
				<button class="btn btn-primary" style="margin-left: 20px" onclick="create2()">ADD SUPPLIER GROUP</button>
			</div>
			<form action="add_group.php" method="POST">
				<div class="col-xs-12 form-group-sm text-center"  id="to_be_inserted">
				</div>
			</form>
		</div>
	</div>
</div>
	<script type="text/javascript">
		function create1(){
			if (to_del=document.getElementById("group2")) {
				document.getElementById("to_be_inserted").removeChild(to_del);
				document.getElementById("to_be_inserted").removeChild(document.getElementById("supp"));
				document.getElementById("to_be_inserted").removeChild(document.getElementById("label1"));
				document.getElementById("to_be_inserted").removeChild(document.getElementById("label2"));
				document.getElementById("to_be_inserted").removeChild(document.getElementById("button2"));
			}
			if(document.getElementById("group1")==null){
				var new_element=document.createElement("input");
				new_element.setAttribute("name","group");
				new_element.setAttribute("id","group1");
				new_element.setAttribute("style","width: 20%;margin-left: 40%;");
				new_element.setAttribute("class","form-control");
				new_element.setAttribute("type","text");
				new_element.required=true;
				var label=document.createElement("label");
				label.setAttribute("style","margin-top: 20px");
				label.setAttribute("id","label");
				label.innerHTML="Group name";
				var new_button=document.createElement("input");
				new_button.setAttribute("type","submit");
				new_button.setAttribute("name","submit1");
				new_button.setAttribute("value","SUBMIT");
				new_button.setAttribute("class","btn btn-default");
				new_button.setAttribute("id","button1");
				new_button.setAttribute("style","margin-top: 20px");
				document.getElementById("to_be_inserted").appendChild(label);
				document.getElementById("to_be_inserted").appendChild(new_element);
				document.getElementById("to_be_inserted").appendChild(new_button);
			}
		}
		function create2(){
			if (to_del=document.getElementById("group1")) {
				console.log("here");
				document.getElementById("to_be_inserted").removeChild(to_del);
				document.getElementById("to_be_inserted").removeChild(document.getElementById("label"));
				document.getElementById("to_be_inserted").removeChild(document.getElementById("button1"));
			}
			if(document.getElementById("group2")==null){
				var new_element=document.createElement("select");
				new_element.setAttribute("name","group[]");
				new_element.setAttribute("id","group2");
				new_element.setAttribute("style","width: 20%;margin-left: 40%;");
				new_element.setAttribute("class","form-control");
				new_element.setAttribute("multiple","");
				new_element.required=true;
				var new_element2=document.createElement("select");
				new_element2.setAttribute("name","supp");
				new_element2.setAttribute("id","supp");
				new_element2.setAttribute("style","width: 20%;margin-left: 40%;");
				new_element2.setAttribute("class","form-control");
				new_element2.required=true;
				<?php while($group=mysqli_fetch_assoc($groups)){?>
				var new_option=document.createElement("option");
				new_option.setAttribute("value","<?php echo $group['id']?>")
				new_option.innerHTML="<?php echo $group['group_name']?>";
				new_element.appendChild(new_option);
				<?php } ?>
				new_element2.appendChild(document.createElement("option"));
				<?php while($supp=mysqli_fetch_assoc($supps)){?>
				var new_option=document.createElement("option");
				new_option.setAttribute("value","<?php echo $supp['id']?>")
				new_option.innerHTML="<?php echo $supp['name']?>";
				new_element2.appendChild(new_option);
				<?php } ?>
				var label1=document.createElement("label")
				label1.innerHTML="Group Id";
				label1.setAttribute("style","margin-top: 20px");
				label1.setAttribute("id","label1");
				var label2=document.createElement("label");
				label2.innerHTML="Supplier";
				label2.setAttribute("style","margin-top: 20px");
				label2.setAttribute("id","label2");
				var new_button=document.createElement("input");
				new_button.setAttribute("type","submit");
				new_button.setAttribute("name","submit2");
				new_button.setAttribute("value","SUBMIT");
				new_button.setAttribute("class","btn btn-default");
				new_button.setAttribute("id","button2");
				new_button.setAttribute("style","margin-top: 20px");
				document.getElementById("to_be_inserted").appendChild(label1);
				document.getElementById("to_be_inserted").appendChild(new_element);
				document.getElementById("to_be_inserted").appendChild(label2);
				document.getElementById("to_be_inserted").appendChild(new_element2);
				document.getElementById("to_be_inserted").appendChild(new_button);
			}
		}
	</script>
</body>
</html>