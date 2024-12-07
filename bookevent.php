<?php
include_once("db.php");
include_once("mailsend.php");
session_start();
try{
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $db=new db();    
        if($_POST["action"]=="book"){
            $_POST["from_time"]=$_POST["date"]." ".$_POST["from_time"].":00";
            $_POST["to_time"]=$_POST["date"]." ".$_POST["to_time"].":00";
			$res=$db->exec_query(sprintf("select * from booking where TIME(fromtime)<='%s' and TIME(totime)>='%s'",$_POST["to_time"],$_POST["from_time"]));
			if(sizeof($res)==0){
				$st=$db->prepare_statement("insert into booking (event_name,fromtime,totime,uname,fname,dept,email,hall) values(?,?,?,?,?,?,?,?)");
				$st->bind_param("ssssssss",$_POST["ename"],$_POST["from_time"],$_POST["to_time"],$_SESSION["token"],$_POST["yname"],$_POST["dept"],$_POST["email"],$_POST["hall"]);
				$st->execute();
				(new SendEmail())->send($_POST["email"],"Booking request",
					sprintf("You have requested to book the %s on %s",$_POST["hall"],$_POST["from_time"])
				);
				echo "<script>alert('booking requested');window.location.href='index.php';</script>";
			}
			else echo "<script>alert('The slot you chose conflicts with other bookings, please choose another slot');window.location.href='".$_POST["pageurl"]."'</script>";
			
        }
        else if($_POST["action"]=="cancel"){
			$res=$db->exec_query(sprintf("select * from booking where booking_id=%s",$_POST["booking_id"]));
            $st=$db->prepare_statement("delete from booking where booking_id=? and uname=?");
            $st->bind_param("is",$_POST["booking_id"],$_SESSION["token"]);
            $st->execute();
			$mailer=new SendEmail();
			$mailer->send($res["email"],"Booking cancellation",
				sprintf("You have cancelled the booking request for %s on %s",$res["hall"],$res["from_time"])
			);
            echo "<script>alert('booking cancelled');window.location.href='index.php';</script>";
        }
        $db->close();
    }
    else{
        echo "<script>alert('invalid request');window.location.href='index.php';</script>";
    }
}
catch(Exception $e){
    echo "<script>alert('".$e."');window.location.href='index.php';</script>";
}

