<?php
require 'connection.php';
if(isset($_POST['submit'])){

$my_user=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM users WHERE id='".$_POST['id']."'"));
echo $my_user['id'];
require 'PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer;

//$mail->isSMTP();                                   // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';
$mail->Mailer="smtp";                    // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                            // Enable SMTP authentication
$mail->Username = 'jusl.it18@gmail.com';          // SMTP username
$mail->Password = 'jadavpur99'; // SMTP password
$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                 // TCP port to connect to

$mail->setFrom('jusl.it18@gmail.com', 'JU');
//$mail->addReplyTo('jusl.it18@gmail.com', 'JU');
$mail->addAddress($my_user['email'],'user');   // Add a recipient
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

$mail->isHTML(true);  // Set email format to HTML

$bodyContent = '<h1>Hi '.$my_user['name'].'</h1>';
$bodyContent .= '<p><a href="http://localhost/myStore/forgot_change_password.php?encrypt='.md5(90*13+$my_user['mobile']).'">CLICK HERE</a> to change your password</p>';

$mail->Subject = 'Password reset.';
$mail->Body    = $bodyContent;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo "<script>alert('Check your email for change password link.');</script>";
    header("Location: index.php");
}
}
?>
<a href=""></a>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="css/navigate.css" rel="stylesheet" type="text/css"/>
	<title>Forgot Password</title>
</head>
<body>
<?php require 'header.html';?>
<div style="padding-top: 230px">
<center>
	<div class="panel panel-default text-center" style="margin: 50px;width: 50%">
		<div class="panel-heading">Forgot Password</div>
		<div class="panel-body">
			<form action="send_email.php" method="POST">
				<div class="col-xs-12 form-group-sm" style="margin-top: 20px">
					<input type="text" name="id" class="form-control" placeholder="Username" style="width: 40%;margin-left: 30%" required>
				</div>
				<div class="col-xs-12 form-group-sm" style="margin-top: 30px">
					<input type="submit" name="submit" class="btn btn-primary" value="SEND EMAIL" >
				</div>
			</form>
		</div>
	</div>
</center>
</div>
<script type="text/javascript">
	
</script>
</body>
</html>
