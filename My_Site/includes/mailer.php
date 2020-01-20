<?php
    /*require_once('PHPMailer/class.phpmailer.php'); //library added in download source.
	require_once('PHPMailer/class.smtp.php');*/
	echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n"; 
	if(!isset($msg))
    $msg  = 'Ez egy tesztüzenet!';
	if(!isset($subj))
    $subj = 'Tesztüzenet SG';
	if(!isset($to))
    $to   = 'robert.weinber@hotmail.com';
	
   /* $from = 'robiteszt666@gmail.com';*/

	
require_once "phpmailer/PHPMailerAutoload.php";


$mail = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth   = true;

/*$message = "Send email with <strong style='color: #ff0000'>PHPMailer</strong> from my SMTP account.<br />";*/

try {

	 $mail->SMTPSecure = 'tls';
	//SMTP server
	$mail->Host = "smtp.sendgrid.net";
	//SMTP port, for example, 25, 587, 2525, ...
	$mail->Port = 587;
	//SMTP account username
	$mail->Username = "azure_26a2523edf8fd9ce970567719a6d060f@azure.com";
	//SMTP account password
	$mail->Password = "Master93";
	$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
				)
			);
		
	//mail from
	$mail->SetFrom($mail->Username, "Teszthonlap");
	//replay to
	/*$mail->AddReplyTo($mail->Username, "your name");*/

	$recipient_address = $to; 
	/*$recipient_name = "recipient name";	*/
	$mail->AddAddress($recipient_address/*, $recipient_name*/);

	//adding carbon copy CC recipients 
	//$mail->AddCC("recipient1@domain.com", "First Person");  

	//adding blind carbon copy BCC recipients
	//$mailer->AddBCC("recipient1@domain.com", "First Person");
 
	$mail->Subject = $subj;  
	//create an alternate automatically
	//$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; 
   
	$mail->MsgHTML($msg);
	
	$mail->CharSet = 'UTF-8';

	$mail->Send();

	echo "<br />Üzenet Elküldve!";

} catch (phpmailerException $e) 
{
	echo $e->errorMessage();

} catch (Exception $e) 
{
	echo $e->getMessage();
}


?>