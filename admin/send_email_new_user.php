<?php
session_start();
require '../connection.php';
if (!isset($_SESSION['user_name']) || $_SESSION['role']!="admin") {
	# code...
	header("Location: ../index.php");
	exit();
}
if (!isset($_POST['id']) || !isset($_POST['pass'])) {
	# code...
	header("Location: ../index.php");
	exit();
}

$my_user=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM users WHERE id='".$_POST['id']."'"));
//echo $my_user['email'];
require '../PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer;

//$mail->isSMTP();// Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';
$mail->Mailer="smtp";                    // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                            // Enable SMTP authentication
$mail->Username = '@gmail.com';          // SMTP username
$mail->Password = ''; // SMTP password
$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                 // TCP port to connect to

$mail->setFrom('jusl.it18@gmail.com', 'JU');
//$mail->addReplyTo('jusl.it18@gmail.com', 'JU');
$mail->addAddress($my_user['email'],'user');   // Add a recipient
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

$mail->isHTML(true);  // Set email format to HTML

$bodyContent = '<h2>Hi '.$my_user['name'].'</h2>';
$bodyContent .= '<p>Your account is now active</p>';
$bodyContent .='<p>Username: '.$my_user['id'].'<br>Password: '.$_POST['pass'].'</p>';

$mail->Subject = 'Account details.';
$mail->Body    = $bodyContent;

	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} 
	header("Location: add_user.php");
	exit();

?>
