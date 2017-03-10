<?php
	require '../connection.php';
	session_start();
	/*if (!isset($_SESSION['user_name']) || $_SESSION['role']!="officer") {
		# code...
		header("Location: http://localhost/store/index.php");
		exit();
	}*/
	if (isset($_POST['submit'])) {
		# code...
		$item_id=mysqli_fetch_assoc(mysqli_query($link,"SELECT id FROM item_master WHERE name='".$_POST['select_item']."' AND group_id=".$_POST['group']));
		//echo $_POST['group'];
		//echo "SELECT id FROM item_master WHERE name='".$_POST['select_name']."' AND group_id=".$_POST['group'];
		$quantity=$_POST['quantity'];
		$purpose=$_POST['purpose'];
		$cur_date=date('Y-m-d');
		$row=mysqli_fetch_assoc(mysqli_query($link,"SELECT current_stock FROM stock WHERE item_id=".$item_id['id']));
		//echo mysqli_error($link);
		//echo "UPDATE stock SET current_stock=current_stock-".$quantity." WHERE item_id=".$item_id['id'];
		if(($row['current_stock']-$quantity)>=0){
			if (mysqli_query($link,"UPDATE stock SET current_stock=current_stock-".$quantity." WHERE item_id=".$item_id['id'])) {
				# code...
				mysqli_query($link,"INSERT INTO scrap VALUES (".$item_id['id'].",".$quantity.",'".$purpose."','".$cur_date."')");
				echo "<script>alert('Update successful.');</script>";
			}
		} else{
			echo "<script>alert('Stock cannot be negative.');</script>";
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
	<title>Scrap Entry</title>
</head>
<body>
	<?php require ('header.html'); ?>
	<?php require ('sidebar.html'); ?>
	<div style="padding-right: 20px; padding-left: 240px; padding-top: 230px">
<div class="panel panel-default text-center">
<div class="panel-heading"><h4>SCRAP ENTRY</h4></div>
<div class="panel-body">
	<form action="scrap_entry.php" method="POST">
		<div class="form-group-sm">
			<div class="col-sm-3">
				<label>Item group</label>
				<select name="group" id="group" class="form-control" required onchange="create()">
					<option></option>
					<?php while($group_name=mysqli_fetch_assoc($group)){?>
					<option value="<?php echo $group_name['id'];?>"><?php echo $group_name['group_name']?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-3" id="items">
				<label>Item name</label>
			</div>
			<div class="col-sm-3">
				<label>Quantity</label>
				<input type="number" name="quantity" class="form-control" required="">
			</div>
			<div class="col-sm-3">
				<label>Purpose</label>
				<textarea name="purpose" class="form-control" required></textarea>
			</div>
		</div>
		<div class="form-group-sm form-group text-center" style="margin: 35px">
			<input type="submit" name="submit" value="Update" class="btn btn-primary" >
		</div>
	</form>
</div>
</div>
</div>
	<script type="text/javascript">
	function create(){
		if (document.getElementById("select_item")!=null) {
			var item=document.getElementById("select_item");
			item.parentNode.removeChild(item);
		}
		var select_group=document.getElementById("group");
        var to_be_inserted=document.getElementById("items");
        <?php
          $group=mysqli_query($link,"SELECT * FROM group_master");
         while($group_row=mysqli_fetch_assoc($group)){?>
          console.log("first while");
          if(select_group.value==="<?php echo $group_row['id'];?>"){
            console.log("if");
         select_name=document.createElement("select");
         select_name.setAttribute("class","form-control");
         select_name.setAttribute("name","select_item");
         select_name.setAttribute("id","select_item");
         select_name.setAttribute("style","width: 100%");
         select_name.required=true;
         var blank=document.createElement("option");
          blank.innerHTML="";
          select_name.appendChild(blank);
          <?php 
          $items=mysqli_query($link,"SELECT * from item_master WHERE group_id=".$group_row['id']);

          while($item=mysqli_fetch_assoc($items)){?>
            console.log("second while");
            select_option=document.createElement("option");
            select_option.innerHTML="<?php echo $item['name'];?>";
            select_name.appendChild(select_option);
            select_option.value="<?php echo $item['name'];?>";
         <?php }?>
          to_be_inserted.appendChild(select_name);
          
          last=select_name;
          
          }
         <?php } ?>
     } 
	</script>
</body>
</html>