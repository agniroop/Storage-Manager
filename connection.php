<?php  
	// Turn off all error reporting
	error_reporting(0);
	$url="localhost";
	$user="root";
	$pass="";
	$database="store";
	$link=mysqli_connect($url,$user,$pass,$database);
	// Check connection
	if (!$link) {
		echo "error";
    die("Connection failed: " . mysqli_connect_error());
	} 
?> 