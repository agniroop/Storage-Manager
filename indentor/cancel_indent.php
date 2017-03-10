<?php
	require '../connection.php';
	session_start();
	/*if ($_SESSION['role']!="indentor" || !isset($_SESSION['user_name'])) {
		# code...
		header("Location: ../index.php");
		exit();
	}*/
	if (!isset($_GET['indent_id'])) {
		# code...
		header("Location: approved_indents.php");
		exit();
	}
	$temp=mysqli_fetch_assoc(mysqli_query($link,"SELECT user_id FROM indent WHERE id=".$_GET['indent_id']));
	if ($_SESSION['user_name']!=$temp['user_id']) {
		# code...
		header("Location: approved_indents.php");
		exit();
	}

	mysqli_query($link,"UPDATE indent SET status=0 WHERE id=".$_GET['indent_id']); 
	header("Location: approved_indents.php");
	exit();
?>