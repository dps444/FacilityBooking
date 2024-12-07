<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Booking information</title>

		<!-- Custom fonts for this template-->
		<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<link
			href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
			rel="stylesheet">

		<!-- Custom styles for this template-->
		<link href="css/sb-admin-2.min.css" rel="stylesheet">
		<link href="resources/logo.jpg" rel="icon">
		<script type="text/javascript">
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
    <body>
        <div class="container border border-lg rounded p-3 shadow" style="background-color:#f0f0f0;margin-top:5%">
        <?php
            include_once("db.php");
			session_start();
            $db=new db();
			if(!isset($_SESSION["token"])) echo "<script>window.location.href='login.php'";
            if(isset($_GET["date"])){
				echo "<h3>".$_GET["date"]."</h2></br>";
				$hall="1";
				if(isset($_SESSION["hall"])) $hall=$_SESSION["hall"];
                $res=$db->exec_query(sprintf("select booking_id,uname,(select hall_name from hall where hall_id=booking.hall) as hall,fname,DATE(fromtime) as date,TIME(fromtime) as fromtime,TIME(totime) as totime,event_name,status from booking where DATE(fromtime)='%s' and hall='%s'",$_GET["date"],$_GET["hall"]));
                if(sizeof($res)<3 and (strtotime($_GET["date"])>strtotime(date("Y-m-d"))==1)){
				$var=explode("-",$_GET["date"]);
				$times=$db->exec_query(sprintf("select TIME(fromtime) as fromtime,TIME(totime) as totime from booking where YEAR(fromtime)='%s' and MONTH(fromtime)='%s' and hall=%d",$var[0],$var[1],$_GET["hall"]));
				if(sizeof($times)>0){
					echo "Please avoid booking the following slots- <br/>";
					foreach($times as $i) echo $i["fromtime"]." ".$i["totime"]."<br/>";
				}
        ?>
                <form action="bookevent.php" method="POST" class="form" style="margin-bottom:8%" osubmit="return confirm('are you sure?')">
                    <input type="text" name="date" value="<?php echo $_GET["date"]?>" hidden/>
					<input type="text" name="hall" value="<?php echo $_GET["hall"]?>" hidden/>
					<input type="text" name="pageurl" value="" hidden/>
                    <div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3">Event Name: </label>
                        </div>
                        <div class="col">
                            <input class="form-control w-50" type="text" name="ename" required="true"/>
                        </div>
                    </div>
					<div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3">Your name: </label>
                        </div>
                        <div class="col">
                            <input class="form-control w-50" type="text" name="yname" required="true"/>
                        </div>
                    </div>
					<div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3">Email: </label>
                        </div>
                        <div class="col">
                            <input class="form-control w-50" type="email" name="email" required="true"/>
                        </div>
                    </div>
					<div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3"
							>Department: </label>
                        </div>
                        <div class="col">
							<select name="dept" class="form-control w-25" required>
								<option value="MCA" selected>Computer applications</option>
								<option value="MBA">Business</option>
								<option value="BCOM">Commerce</option>
								<option value="PSY">Psychology</option>
							</select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3" for="from_time">From: </label>
                        </div>
                        <div class="col">
                            <input id="from_time" onchange="timesetter()" class="form-control w-25" type="time" min="08:00:00" max="19:00:00" name="from_time" required="true"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3" for="to_time">To: </label>
                        </div>
                        <div class="col">
                            <input id="to_time" class="form-control w-25" type="time" min="08:00:00" max="19:00:00" name="to_time"  required="true"/>
                        </div>
                    </div>
					
                    <button type="submit" name="action" value="book" class="btn btn-success mt-3">Book</button>
                </form>
        <?php				
                }
				else if(sizeof($res)==0 and (strtotime($_GET["date"])>strtotime(date("Y-m-d"))!=1)){
					echo "<h3>No events were booked on ".$_GET["date"]."</h3>";
				}
				else if(sizeof($res)!=0 and (strtotime($_GET["date"])>strtotime(date("Y-m-d"))!=1)){
					echo "<h3>The following events were booked on ".$_GET["date"]." </h3>";
					foreach($res as $i){
						echo sprintf("%s booked %s for %s<br/>",$i["fname"],$i["hall"],$i["event_name"]);
					}
				}
				if(sizeof($res)>0 and (strtotime($_GET["date"])>strtotime(date("Y-m-d"))==1)){
					echo "<h2>Bookings on this day</h2>";
					foreach($res as $i){

        ?>
				<div class="container border p-2">
				<h4><?php echo $i["hall"] ?> booked by <?php echo $i["fname"] ?> for <?php echo $i["event_name"] ?></h4>
                From: <?php echo $i["fromtime"] ?><br/>
                To: <?php echo $i["totime"] ?><br/>
				Status: <?php
					if($i["status"]==0) echo "<span class='bg-warning rounded p-1'>Awaiting approval</span>";
					else echo "<span class='bg-success rounded p-1' style='color:black'>Booked</span>";
				?>
        <?php
                if($_SESSION["token"]==$i["uname"]){
                    ?>
                <form class="mt-3" action="bookevent.php" onsubmit="return confirm('are you sure?')" method="POST">
                    <input type="text" name="booking_id" value="<?php echo $i["booking_id"]?>" hidden/>
                    <button class="btn btn-primary" type="submit" name="action" value="cancel">Cancel</button>
                </form>
                <?php                    
                }
				echo "</div>";
				}
                }
            }
        ?>
		</br></br>
		<a class="btn btn-secondary mt-3" href="index.php">Go Back</a>
        </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
