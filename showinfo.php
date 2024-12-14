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
                <div class="sidebar-brand-text mx-3">Facilities Booking</div>
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
            
			if(!isset($_SESSION["token"])) echo "<script>window.location.href='login.php'";
            if(isset($_GET["date"])){
				echo "<h3>".$_GET["date"]."</h2></br>";
				$hall="1";
				if(isset($_SESSION["hall"])) $hall=$_SESSION["hall"];
                $res=$db->exec_query(sprintf("select booking_id,uname,(select hall_name from hall where hall_id=booking.hall) as hall,fname,DATE(fromtime) as date,TIME(fromtime) as fromtime,TIME(totime) as totime,event_name,status from booking where DATE(fromtime)='%s' and hall='%s'",$_GET["date"],$_GET["hall"]));
                if(sizeof($res)<3 and (strtotime($_GET["date"])>strtotime(date("Y-m-d"))==1)){
				$var=explode("-",$_GET["date"]);
				$times=$db->exec_query(sprintf("select TIME(fromtime) as fromtime,TIME(totime) as totime from booking where YEAR(fromtime)='%s' and MONTH(fromtime)='%s' and DAY(fromtime)='%s' and hall=%d",$var[0],$var[1],$var[2],$_GET["hall"]));
				if(sizeof($times)>0){
					echo "Please avoid booking the following slots- <br/>";
					foreach($times as $i) echo $i["fromtime"]." to ".$i["totime"]."<br/>";
				}
        ?>
                <form action="bookevent.php" method="POST" class="form" onsubmit="return confirm('are you sure?')">
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
					
                    <button type="submit" name="action" value="book" class="btn btn-success mt-3"><i class="fa fa-plus mr-1"></i>Book</button>
					<a class="btn btn-secondary mt-3" href="index.php"><i class="fas fa-reply mr-1"></i>Back</a>
					<button type="reset" class="btn btn-secondary mt-3"><i class=""></i>Clear</button>
                </form>
        <?php				
                }
            }
        ?>
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