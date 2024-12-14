<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Booking history</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
	<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="resources/logo.jpg" rel="icon">
</head>

<body id="page-top">
	
	<?php		
		include_once("db.php");
		session_start();
		$db=new db();
		if(!isset($_SESSION["token"])) echo "<script>window.location.href='login.php'</script>";
	?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <img src="resources/logo.jpg" style="width:50px;height:50px"/>
                </div>
                <div class="sidebar-brand-text mx-3">Facilities Booking</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
			<li class="nav-item active">
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
                    <h1 class="h3 mb-2 text-gray-800">Your previous bookings</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Department</th>
                                            <th>Hall</th>
                                            <th>Status</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											$res=$db->exec_query(sprintf("select booking_id,event_name,dept,(select hall_name from hall where hall_id=booking.hall) as hall,status,DATE(fromtime) as date,TIME(fromtime) as fromtime,TIME(totime) as totime from booking where uname='%s' order by date(fromtime) desc",$_SESSION["token"]));
											foreach($res as $i){
												echo "<tr><td>".$i["event_name"]."</td>";
												echo "<td>".$i["date"]." ".$i["fromtime"]."</td>";
												echo "<td>".$i["totime"]."</td>";
												echo "<td>".$i["dept"]."</td>";
												echo "<td>".$i["hall"]."</td>";
												$status="Awaiting approval";
												if($i["status"]==1) $status="Booked";
												if(strtotime($i["date"])<strtotime(date("Y-m-d"))==1) $status="Event over";												
												echo "<td>".$status."</td>";
												if(strtotime($i["date"])<strtotime(date("Y-m-d"))!=1){
													?>
													<td>
													<form action="bookevent.php" onsubmit="return confirm('are you sure?')" method="POST">
														<input type="text" name="booking_id" value="<?php echo $i["booking_id"]?>" hidden/>
														<button class="btn btn-danger" type="submit" name="action" value="cancel"><i class="far fa-trash-alt mr-1"></i>Cancel</button>
													</form>
													</td></tr>
													<?php
												}
												else echo "<td><button class='btn btn-secondary disabled' disabled><i class='far fa-trash-alt mr-1'></i>Cancel</button></td></tr>";
											}
										?>
                                    </tbody>
                                </table>
                            </div>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
	<script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/datatables.js"></script>
    

</body>

</html>