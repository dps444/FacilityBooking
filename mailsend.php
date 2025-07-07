<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require 'D:\Programs\XAMPP\php\pear\PHPMailer\src\Exception.php';
	require 'D:\Programs\XAMPP\php\pear\PHPMailer\src\PHPMailer.php';
	require 'D:\Programs\XAMPP\php\pear\PHPMailer\src\SMTP.php';
	class SendEmail{
		protected $mail;
		function __construct(){
			$this->mail->isSMTP();
			$this->mail->Host = 'smtp.gmail.com';
			$this->mail->SMTPAuth = true;
			$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$this->mail->Port = 587;
			$this->mail->Username = 'email'; // email address
			$this->mail->Password = 'password'; // app password
			$this->mail->setFrom('Facility booking manager','');
		}
		function send($address,$subject,$body){
			try{
				$this->mail->addAddress($address);
				$this->mail->isHTML(true);
				$this->mail->Subject =$subject;
				$this->mail->Body=$body;
				return $this->mail->send();
			}
			catch(Exception){
				return null;
			}
		}
	}	
?>