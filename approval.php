<?php
	session_start();
	include_once("db.php");
	include_once("mailsend.php");
	$db=new db();
	$res=$db->exec_query("select type from user where uname='".$_SESSION["token"]."'");
	$booking_info=$db->exec_query("select * from booking where booking_id=".$_POST["booking_id"]);
	if($_SERVER["REQUEST_METHOD"]=="POST" and isset($_POST["action"]) and $res[0]["type"]==1){
		if($_POST["action"]=="approve"){
			$db->exec_update("update booking set status=1 where booking_id=".$_POST["booking_id"]);
			(new SendEmail())->send($_POST["email"],"Booking accepted","Your booking for ".$booking_info[0]["hall"]." on ".$booking_info[0]["fromtime"]." has been accepted");
			echo "<script>alert('Booking approved');window.location.href='admin.php';</script>";
		}
		else if($_POST["action"]=="reject"){
			$db->exec_update("delete from booking where booking_id=".$_POST["booking_id"]);
			(new SendEmail())->send($_POST["email"],"Booking rejected","Your booking for ".$booking_info[0]["hall"]." on ".$booking_info[0]["fromtime"]." has been rejected");
			echo "<script>alert('Booking rejected');window.location.href='admin.php';</script>";
		}		
	}
?>