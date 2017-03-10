<?php
session_start();
	/*if (!isset($_SESSION['user_name']) || $_SESSION['role']!="indentor") {
		# code...
		header("Location: http://localhost/store/index.php");
	}*/

	require_once ('../dompdf/autoload.inc.php');
	use Dompdf\Dompdf;

	if (!isset($_POST['indent_id'])) {
		# code...
		echo "<script>alert('".$_POST['indent_id']."');</script>";
		header("Location: approved_indents.php");
	}
	require '../connection.php';
	mysqli_query($link,"UPDATE indent SET status=3 WHERE id=".$_POST['indent_id']);
	$indent=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM indent WHERE id=".$_POST['indent_id']." AND status=4 AND passed_by_id='".$_SESSION['user_name']."'"));
	$items=mysqli_query($link,"SELECT * FROM indent_item WHERE indent_id=".$_POST['indent_id']." AND delivered='N'");
	$name=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM users WHERE id='".$_SESSION['user_name']."'"));
	
	$html='<!DOCTYPE html>
	<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
	<body>
	<div class="row text-center" style="margin-top: 0px;">
		<span>
		<img src="../images/Emblem.png" style="width: 8%" class="img-responsive">
		</span>
		<span>
		<h2>Store and Inventory Management System</h2>
		<h3>E Aqua. Agro. R.M.C.M.S. Pvt. Ltd.</h3>
		<h4>(Government of West Bengal)</h4>
		</span>
		</div>
	</div>
		<div style="border: 1px solid;width: 200px;margin-top:30px">
			<h4 style="padding-left: 10px">Indent date:&nbsp; '.$indent['date'].'</h4>
			<h4 style="padding-left: 10px">Approval date:&nbsp; '.$indent['passing_date'].'</h4>
			<h4 style="padding-left: 10px">Indent id:&nbsp;'.$_POST['indent_id'].'</h4>
			<h4 style="padding-left: 10px">Approved By:&nbsp;'.$name['name'].'</h4>
		</div>
		<h4 style="margin-top:50px;" class="text-center"><u>Items</u></h4>
		<table class="table table-striped text-center table-bordered table-condensed" style="margin-bottom:160px;">
			<thead style="background-color: grey;">
				<tr>
					<th>Sl. No.</th>
					<th>Item name</th>
					<th>Item company</th>
					<th>Room</th>
					<th>Location</th>
					<th>Quantity</th>
					<th></th>
				</tr>
			</thead>
			<tbody style="background-color: #EEEEEE;">';
			$extra="";
			$sl=1;
				while ($item=mysqli_fetch_assoc($items)) {
					# code...
					$item_master=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM item_master WHERE id=".$item['item_id']));
					$extra=$extra."<tr><td>".$sl."</td><td>".$item_master['name']."</td><td>".$item_master['company']."</td><td>".$item_master['room']."</td><td>".$item_master['store_location']."</td><td>".$item['quantity']."</td><td><span style='width: 5px;height: 5px;border: 1px solid'></span></td></tr>";
					$sl=$sl+1;
				}
				$sgn='</tbody></table>
				<div style="margin-top:60px;">
					<span style="text-align: left: ">_______________________&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span><span style="text-align: right;">________________________</span><br>
					<span>Signature of Issuer with date&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span><span style="margin-left:100px;">Signature of receiver with date</span>
				</div>
				</body>
				</html>';

			$html=$html.$extra.$sgn;

	$dompdf= new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4','potrait');
	$dompdf->render();
	$dompdf->stream('codexworld',array('Attachment'=>0));

?>