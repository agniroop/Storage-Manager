<?php
	require '../connection.php';
	session_start();
	if(!isset($_SESSION['user_name']) || $_SESSION['role']!="officer") {
		# code...
		header("Location: ../index.php");
		exit;
	}
	if (isset($_POST['submit'])) {
		# code...
		$chalan_no=$_POST['chalan_no'];
		$supp_id=$_POST['supp_id'];
		$dor=strtotime($_POST['dor']);
		$chalan_date=strtotime($_POST['chalan_date']);
		$quantity=$_POST['quantity'];
		$batch_no=$_POST['batch_no'];
		$exp_date=strtotime($_POST['exp_date']);
		//echo $dor."  ".$chalan_date."  ".$exp_date."<br>";
		$dor=date('Y-m-d',$dor);
		$chalan_date=date('Y-m-d',$chalan_date);
		//echo $dor."  ".$chalan_date."  ".$exp_date." ".date('Y-m-d');

		$result=mysqli_query($link,"SELECT * FROM item_master WHERE id=".$_POST['name']." AND company='".$_POST['company']."'");
		//echo mysqli_num_rows($result);

		if (mysqli_num_rows($result)<1) {
			# code...
			$_SESSION['msg']="No item found! Please check the name and the company of the item.";
			header("Location: stock_entry.php");
			exit;
		}
		
		$row=mysqli_fetch_assoc($result);
		$item_id=$row['id'];
		$supp=mysqli_query($link,"SELECT * FROM supplier_group WHERE supp_id=".$supp_id." AND group_id=".$row['group_id']);
		if (mysqli_num_rows($supp)<1) {
			# code...
			$_SESSION['msg']="This supplier does not supply this item.";
			header("Location: stock_entry.php");
			exit();
		}
		
		mysqli_autocommit($link,FALSE);
		if ($exp_date!='' && $batch_no!='') {
			# code...
			$exp_date=date('Y-m-d',$exp_date);
			$res=mysqli_query($link,"INSERT INTO stock_entry (chalan_no,item_id,supp_id,batch_no,exp_date,date_of_receive,chalan_date,quantity) VALUES ('".$chalan_no."',".$item_id.",".$supp_id.",".$batch_no.",'".$exp_date."','".$dor."','".$chalan_date."',".$quantity.")");
		}
		else if($exp_date!=''){
			$exp_date=date('Y-m-d',$exp_date);
			$res=mysqli_query($link,"INSERT INTO stock_entry (chalan_no,item_id,supp_id,exp_date,date_of_receive,chalan_date,quantity) VALUES ('".$chalan_no."',".$item_id.",".$supp_id.",'".$exp_date."','".$dor."','".$chalan_date."',".$quantity.")");
		}
		else if($batch_no!=''){
			$res=mysqli_query($link,"INSERT INTO stock_entry (chalan_no,item_id,supp_id,batch_no,date_of_receive,chalan_date,quantity) VALUES ('".$chalan_no."',".$item_id.",".$supp_id.",".$batch_no.",'".$dor."','".$chalan_date."',".$quantity.")");
		} else{
			$res=mysqli_query($link,"INSERT INTO stock_entry (chalan_no,item_id,supp_id,date_of_receive,chalan_date,quantity) VALUES ('".$chalan_no."',".$item_id.",".$supp_id.",'".$dor."','".$chalan_date."',".$quantity.")");			
		}
		//echo mysqli_error($link);
		//echo "INSERT INTO stock_entry (chalan_no,item_id,supp_id,date_of_receive,chalan_date,quantity) VALUES ('".$chalan_no."',".$item_id.",".$supp_id.",'".$dor."','".$chalan_date."',".$quantity.")";
		//$result=mysqli_query($link,"SELECT * FROM stock WHERE item_id=".$item_id);
		//if (mysqli_num_rows($result)>0) {
		
			# code...
			if($res){
				$res=mysqli_query($link,"UPDATE stock SET current_stock =current_stock+".$quantity." WHERE item_id =".$item_id);
			}
			
		//} else {
			# code...
			//mysql_query($link,"INSERT INTO stock VALUES (".$item_id.",".$quantity.")");
		//}
		if ($res && mysqli_commit($link)) {
			# code...
			//echo $quantity;
			echo "<script>alert('stock entry successful!')</script>";
			
		} else{
			mysqli_rollback($link);
			$_SESSION['msg']="<script>alert('".mysqli_error($link)."');</script>";
		}
	}
	if (isset($_SESSION['msg'])) {
		# code...
		$msg="<script>alert('".$_SESSION['msg']."');</script>";
		unset($_SESSION['msg']);
	}
		$items1=mysqli_query($link,"SELECT DISTINCT name FROM item_master");
		$items2=mysqli_query($link,"SELECT DISTINCT company FROM item_master WHERE company<>''");
		$supp=mysqli_query($link,"SELECT * FROM supplier_master");
		$grp=mysqli_query($link,"SELECT * FROM group_master");
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<title>Stock Entry</title>
</head>
<body>
	<?php require ('header.html');?>
	<?php require ('sidebar.html');?>
<div style="padding-top: 230px; padding-left: 240px; padding-right: 20px;" >
	<div class="panel panel-default" style="width: 100%">
	<div class="panel-heading text-center"><h4>STOCK ENTRY</h4></div>
	<div class="panel-body">
	<form action="stock_entry.php" method="POST">
		<div class="form-group form-group-sm" style="padding-top: 20px">
			<div class="col-xs-6">	
				<label>Chalan no. :</label>
				<input type="number" name="chalan_no" class="form-control" required>
			</div>
			<div class="col-xs-6">
				<label>Chalan date:</label>
				<input type="date" name="chalan_date" class="form-control"  required>
			</div>
		</div>
		<div class="form-group form-group-sm" style="padding-top: 60px">
			<div class="col-xs-4">
			<label>Item group:&nbsp;</label>
			<select name="grp_name" class="form-control" required id="group" onchange="item_check()">
				<option selected hidden></option>
				<?php while($grp_row=mysqli_fetch_assoc($grp)){?>
				<option value="<?php echo $grp_row['id']?>"><?php echo $grp_row['group_name'] ?></option>
				<?php } ?>
			</select>
			</div>
			<div class="col-xs-4" id="item_parent">
			<label>Item name:&nbsp;</label>
			<select name="name" class="form-control" required id="item">
				<option></option>
			</select>
			</div>
			<div class="col-xs-4">
			<label >Company name:(optional)</label>
			<select name="company" class="form-control">
				<option value=""></option>
				<?php while($item2=mysqli_fetch_assoc($items2)){?>
				<option value="<?php echo $item2['company'] ?>"><?php echo $item2['company']?></option>
				<?php } ?>
			</select>
			</div>
			
		</div>
		<div class="form-group form-group-sm" style="padding-top: 60px">
			<div class="col-xs-4">
			<label >Supplier name:&nbsp;</label>
			<select name="supp_id" class="form-control" required>
				<option></option>
				<?php while($supp_row=mysqli_fetch_assoc($supp)){?>
				<option value="<?php echo $supp_row['id'] ?>"><?php echo $supp_row['name'] ?></option>
				<?php } ?>
			</select>
			</div>
			<div class="col-xs-4">
				<label>Batch no. :(optional)</label>
				<input type="number" name="batch_no" class="form-control" >
			</div>
			<div class="col-xs-4">
				<label >Expiry date:(optional)&nbsp;</label>
				<input type="date" name="exp_date" class="form-control" >
			</div>
		</div>
		<div class="form-group form-group-sm" style="padding-top: 60px">
			<div class="col-xs-6">
				<label>Date of receive:</label>
				<input type="date" name="dor" class="form-control"  required>
			</div>
			<div class="col-xs-6">
				<label>Quantity:</label>
				<input type="number" name="quantity" class="form-control"  required>
			</div>
		</div>
				<input type="submit" name="submit" class="btn btn-primary" value="SUBMIT" style="margin-top: 20px; margin-left: 15px">
				<input type="reset" name="reset" value="CLEAR" style="margin-top: 20px; margin-left: 20px" class="btn btn-warning">
	</form>
	</div>
	</div>
</div>
<?php echo $msg ?>
<script type="text/javascript">
	var item;
	var company;
	var supplier;
	parent=document.getElementById("item_parent");
	function item_check(){
		if (document.getElementById("item")!=null) {
			document.getElementById("item").parentNode.removeChild(document.getElementById("item"));
		}
		var group=document.getElementById("group").value;
		var select=document.createElement("select");
		select.setAttribute("class","form-control");
		select.setAttribute("id","item");
		select.setAttribute("name","name");
		select.required=true;
		option=document.createElement("option");
		option.setAttribute("value","");
		option.hidden=true;
		option.selected=true;
		select.appendChild(option);
		<?php 
		mysqli_data_seek($grp, 0);
		while($row=mysqli_fetch_assoc($grp)){?>
			if (group==<?php echo $row['id']?>) {
				<?php $item_group=mysqli_query($link,"SELECT * FROM item_master WHERE group_id=".$row['id']);
					while($item_row=mysqli_fetch_assoc($item_group)){?>
				option=document.createElement("option");
				option.setAttribute("value","<?php echo $item_row['id'];?>");
				option.innerHTML="<?php echo $item_row['name'];?>";
				select.appendChild(option);
				<?php } ?>
			}
			<?php } ?> 
			parent.appendChild(select);
	}
</script>
</body>
</html>