<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hall Booking</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
	<link href="resources/logo.jpg" rel="icon">
	<script>
            window.onload=()=>{
				document.forms[0]["pageurl"].value=window.location.href;
			}
			function timesetter(){
				const starttime=document.getElementById("from_time");
				const endtime=document.getElementById("to_time");
				endtime.setAttribute("min",starttime.value);
			}
    </script>
</head>

<body id="page-top">
	<?php		
		include_once("db.php");
		session_start();
		$db=new db();
	?>
	
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <span class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon">
                    <img src="resources/logo.jpg" style="width:50px;height:50px"/>
                </div>
                <div class="sidebar-brand-text mx-3">Facility Booking</div>
            </span>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="booking_history.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Booking history</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="raiseissue.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Raise issue</span></a>
            </li>
			<?php
				if(isset($_SESSION["token"])){					
			?>
			<li class="nav-item">
                <a class="nav-link" href="login.php?action=logout">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Logout</span></a>
            </li>
			<?php
				}
				else echo("<script>window.location.href='login.php'</script>");
			?>
        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="mt-3">

                

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Book an event</h1><br/>						
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        
                        <div class="col">
                            <div class="container mt-5">
								
								<?php
include_once("mailsend.php");
try{
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $db=new db();
        if($_POST["action"]=="book"){
            $_POST["from_time"]=$_POST["date"]." ".$_POST["from_time"].":00";
            $_POST["to_time"]=$_POST["date"]." ".$_POST["to_time"].":00";
			$hallinfo=$db->exec_query("select * from hall where hall_id=".$_POST["hall"])[0];
			if($hallinfo["capacity"]<$_POST["capacity"]){
				$res=$db->exec_query(sprintf(
					"select * from hall where
					hall_id not in (select hall from booking where TIME(fromtime)<='%s' and TIME(totime)>='%s' and DATE(fromtime)=DATE('%s'))
					and capacity>=%s and status=1
					",$_POST["to_time"],$_POST["from_time"],$_POST["from_time"],$_POST["capacity"]
				));
				echo "<h4>Expected occupants exceed the capacity of the premise, please choose another.</h4><br/>";
				if(sizeof($res)>0){
					echo "<h6>The following premises are free on this day- </h6><br/>";
					foreach($res as $i)	echo $i["hall_name"]."<br/>";
					echo "<form action='bookevent.php' method='POST'>";
					unset($_POST["hall"],$_POST["action"],$_POST["pageurl"]);
					foreach($_POST as $i=>$j) echo sprintf("<input type='text' name='%s' value='%s' hidden/>",$i,$j);
					echo "<select class='form-control w-50' name='hall'>";
					foreach($res as $i) echo sprintf("<option value='%s'>%s</option>",$i["hall_id"],$i["hall_name"]);
					echo "</select><br/><button type='submit' class='btn btn-primary' name='action' value='book'>Book</button></form>";
				}				
				else echo "There are no premises available on this day<br/>";
			}
			else{
				$res=$db->exec_query(sprintf("select * from booking where TIME(fromtime)<='%s' and TIME(totime)>='%s' and DATE(fromtime)=DATE('%s') and hall=%s",$_POST["to_time"],$_POST["from_time"],$_POST["from_time"],$_POST["hall"]));
				if(sizeof($res)==0){					
					$st=$db->prepare_statement("insert into booking (event_name,fromtime,totime,uname,fname,dept,email,hall) values(?,?,?,?,?,?,?,?)");
					$st->bind_param("ssssssss",$_POST["ename"],$_POST["from_time"],$_POST["to_time"],$_SESSION["token"],$_POST["yname"],$_POST["dept"],$_POST["email"],$_POST["hall"]);
					$st->execute();
					(new SendEmail())->send($_POST["email"],"Booking request",
						sprintf("You have requested to book the %s on %s",$_POST["hall"],$_POST["from_time"])
					);
					echo sprintf("
						<h4>Booking has been requested on %s for %s</h4>
						<br/>
						<h6>Event name: %s</h6>
						<h6>Name: %s (%s)</h6>
						<h6>From %s to %s</h6>
						<h6>Department: %s</h6>
						",explode(" ",$_POST["from_time"])[0],$hallinfo["hall_name"],$_POST["ename"],$_POST["yname"],$_POST["email"],explode(" ",$_POST["from_time"])[1],explode(" ",$_POST["to_time"])[1],$_POST["dept"]);
					echo "<script>sessionStorage.removeItem('form_cache')</script>";
				}
				else echo "<script>alert('The slot you chose was already booked by ".$res[0]["fname"].", please choose another slot');window.location.href='".$_POST["pageurl"]."'</script>";
			}
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
            echo "<script>alert('booking cancelled');window.location.href='booking_history.php';</script>";
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
?>


								<a class="btn btn-secondary mt-3" href="index.php"><i class="fas fa-reply mr-1"></i>Back</a>
							</div>							
                        </div>

                        

                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    

</body>

</html>