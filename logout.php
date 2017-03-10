<?php
require 'connection.php';
session_start();
date_default_timezone_set('Asia/Kolkata');
if(isset($_SESSION['user_name']) && isset($_SESSION['role'])){
	$time=date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
	$temp=mysqli_fetch_assoc(mysqli_query($link, "SELECT id,login_time FROM user_log WHERE logout_time IS NULL AND user_id='".$_SESSION['user_name']."' ORDER BY id DESC"));
	//echo "SELECT id FROM user_log WHERE logout_time=NULL AND user_id='".$_SESSION['user_name']."' ORDER BY id DESC";
	mysqli_query($link, "UPDATE user_log SET logout_time=now(),login_time='".$temp['login_time']."' WHERE id=".$temp['id']);
}
	unset($_SESSION['role']);
	unset($_SESSION['user_name']);
    session_destroy();
    header("Location: index.php");
?>