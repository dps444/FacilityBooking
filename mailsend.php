<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require 'D:\Programs\XAMPP\php\pear\PHPMailer\src\Exception.php';
	require 'D:\Programs\XAMPP\php\pear\PHPMailer\src\PHPMailer.php';
	require 'D:\Programs\XAMPP\php\pear\PHPMailer\src\SMTP.php';
	class SendEmail{
		protected $mailer;
		function __construct(){
			$this->mailer=new PHPMailer();
			$this->mailer->isSMTP();
			$this->mailer->Host="127.0.0.1";
			$this->mailer->SMTPAuth=false;
			$this->mailer->setFrom("user1@localhost.net");
		}
		function send($address,$subject,$body){
			try{
				$this->mailer->addAddress($address);
				$this->mailer->isHTML(true);
				$this->mailer->Subject =$subject;
				$this->mailer->Body=$body;
				return $this->mailer->send();
			}
			catch(Exception){
				return null;
			}
		}
	}	
?>