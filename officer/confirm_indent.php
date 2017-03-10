<?php
	require '../connection.php';
	session_start();
	/*if (!isset($_SESSION['user_name']) || $_SESSION['role']!="officer") {
		# code...
		header("Location: http://localhost/store/index.php");
		exit;
	}*/
	$result=mysqli_query($link,"SELECT * FROM indent_item WHERE indent_id=".$_GET['id']);

	mysqli_autocommit($link,FALSE);
	$cur_date=date('Y-m-d');

	while ($row=mysqli_fetch_assoc($result)) {
		# code...
		$res=mysqli_query($link,"SELECT * FROM stock WHERE item_id=".$row['item_id']);
		$stock=mysqli_fetch_assoc($res);
		//echo $row['item_id']."    ".$_GET[$row['item_i']]."     ".$['quantity'];
		if($row['quantity']>=$_GET[$row['item_id']] && $_GET[$row['item_id']]!=0){
			if ($_GET[$row['item_id']]<=$stock['current_stock']) {
				# code...
				if($_GET['purpose'.$row['item_id']]==""){
					$flag=mysqli_query($link,"UPDATE indent_item SET quantity=".$_GET[$row['item_id']]." WHERE item_id=".$row['item_id']." AND indent_id=".$_GET['id']);
					//echo "UPDATE indent_item SET quantity=".$_GET[$row['item_id']]." WHERE item_id=".$row['item_id']." AND indent_id=".$_GET['id'];
				}
				else{
					$flag=mysqli_query($link,"UPDATE indent_item SET quantity=".$_GET[$row['item_id']].",officer_purpose_id=".$_GET['purpose'.$row['item_id']]." WHERE item_id=".$row['item_id']." AND indent_id=".$_GET['id']);
					//echo "**************UPDATE indent_item SET quantity=".$_GET[$row['item_id']].",officer_purpose_id=".$_GET['purpose'.$row['item_id']]." WHERE item_id=".$row['item_id']." AND indent_id=".$_GET['id'];
				}
				if($flag)
					mysqli_query($link,"UPDATE stock SET current_stock=current_stock-".$_GET[$row['item_id']]." WHERE item_id=".$row['item_id']);
				else{
					echo mysqli_error($link);
					mysqli_rollback($link);

					//header("Location: http://localhost/store/officer/indent_details.php?id=".$_GET['id']);
					exit;
				}

			} else {
				# code...
				mysqli_rollback($link);
				$_SESSION['msg']="Quantity cannot be greater than stock.";
				header("Location: indent_details.php?id=".$_GET['id']);
				exit;
			}
		} elseif($_GET[$row['item_id']]==0){
			mysqli_query($link,"UPDATE indent_item SET delivered='C',officer_purpose_id=".$_GET["purpose".$row['item_id']]." WHERE indent_id =".$_GET['id']." AND item_id =".$row['item_id']);
		}else{
			mysqli_rollback($link);
			$_SESSION['msg']="Approved quantity cannot be greater than the required quantity.";
			header("Location: indent_details.php?id=".$_GET['id']);
			exit;
		}
	}
	$result=mysqli_query($link,"SELECT * FROM indent_item WHERE indent_id=".$_GET['id']." AND delivered='N'");
	if (mysqli_num_rows($result)<1) {
		# code...
		$flag=mysqli_query($link,"UPDATE indent SET status = 0,passed_by_id='".$_SESSION['user_name']."',passing_date='".$cur_date."' WHERE id=".$_GET['id']);
	} else{
	$flag=mysqli_query($link,"UPDATE indent SET status = 2,passed_by_id='".$_SESSION['user_name']."',passing_date='".$cur_date."' WHERE id =".$_GET['id']);
	echo mysqli_error($link);
	}
	if (!mysqli_commit($link) || !$flag) {
		# code...
		mysqli_rollback($link);
		echo mysqli_error($link);
	}else{
		header("Location: form.php?indent_id=".$_GET['id']);
	}
	
?>
