<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
	<link href="css/sb-admin-2.min.css" rel="stylesheet">
	<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="resources/logo.jpg" rel="icon">
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
                <div class="sidebar-brand-text mx-3">ADMIN PANEL</div>
            </span>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
			<li class="nav-item active">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Booking requests</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="hallmanage.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Facilities</span></a>
            </li>
			<?php
				if(isset($_SESSION["token"]) and $_SESSION["isadmin"]=="1"){
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
                    <h1 class="h3 mb-2 text-gray-800">Approve bookings</h1>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
											<th>Booked by</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Department</th>
                                            <th>Hall</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											$res=$db->exec_query(sprintf("select booking_id,event_name,email,fname,dept,(select hall_name from hall where hall_id=booking.hall) as hall,status,DATE(fromtime) as date,TIME(fromtime) as fromtime,TIME(totime) as totime from booking where status=0 order by date(fromtime) desc"));
											foreach($res as $i){
												echo "<tr><td>".$i["event_name"]."</td>";
												echo "<td>".$i["fname"]."</td>";
												echo "<td>".$i["date"]." ".$i["fromtime"]."</td>";
												echo "<td>".$i["totime"]."</td>";
												echo "<td>".$i["dept"]."</td>";
												echo "<td>".$i["hall"]."</td>";
												?>
												<td>
													<form action="approval.php" method="POST" onsubmit="return confirm('Are you sure?')">
														<input type="text" name="booking_id" value="<?php echo $i["booking_id"] ?>" hidden/>
														<input type="text" name="email" value="<?php echo $i["email"] ?>" hidden/>
														<button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
														<button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
													</form>
												</td>
												</tr>
												<?php
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

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Hall booking</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

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
	<script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/datatables.js"></script>
</body>

</html>