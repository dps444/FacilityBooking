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
		if(!isset($_SESSION["token"]) or (isset($_SESSION["isadmin"]) and $_SESSION["isadmin"]=="0")) echo "<script>window.location.href='login.php'</script>";
		if($_SERVER["REQUEST_METHOD"]=="POST" and isset($_POST["action"])){
			if($_POST["action"]=="create"){
				$st=$db->prepare_statement("insert into hall (hall_name,capacity,description,status) values (?,?,?,true)");
				$st->bind_param("sss",$_POST["hallname"],$_POST["hallcap"],$_POST["halldescr"]);
				$st->execute();
				echo "<script>alert('New facility created');window.location.href='hallmanage.php'</script>";
			}
			else if($_POST["action"]=="remove"){
				$res=$db->exec_query("select count(*) from booking where hall_id=".$_POST["hall_id"]);
				if(sizeof($res)>0) echo "<script>alert('This hall has been booked. It cannot be removed right now');window.location.href='hallmanage.php'</script>";
				else{
					$st=$db->prepare_statement("delete from hall where hall_id=?");
					$st->bind_param("d",$_POST["hall_id"]);
					$st->execute();
					echo "<script>alert('Facility removed');window.location.href='hallmanage.php'</script>";
				}
			}
			else if($_POST["action"]=="toggle"){
				$status="1";
				if($_POST["status"]=="1") $status="0";
				$st=$db->prepare_statement("update hall set status=? where hall_id=?");
				$st->bind_param("sd",$status,$_POST["hall_id"]);
				$st->execute();
				echo "<script>alert('Status of the facility has been changed');window.location.href='hallmanage.php'</script>";
			}
		}
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
			<li class="nav-item">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Booking requests</span></a>
            </li>
			<li class="nav-item active">
                <a class="nav-link" href="hallmanage.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Facilities</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="issues.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Issues</span></a>
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
					<h1 class="h3 mb-2 text-gray-800">Manage facilities</h1>
                    <div class="card shadow mb-4">                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Hall ID</th>
											<th>Hall Name</th>
                                            <th>Capacity</th>
                                            <th>Description</th>
											<th>Status</th>
											<th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											$res=$db->exec_query(sprintf("select * from hall"));
											foreach($res as $i){
												echo "<tr><td>".$i["hall_id"]."</td>";
												echo "<td>".$i["hall_name"]."</td>";
												echo "<td>".$i["capacity"]."</td>";
												echo "<td>".$i["description"]."</td>";
												if($i["status"]==1) echo "<td>Available</td>";
												else echo "<td>Disabled</td>";
												?>
												<td>
													<form method="POST" onsubmit="return confirm('Are you sure?')">
														<input type="text" name="hall_id" value="<?php echo $i["hall_id"] ?>" hidden/>
														<input type="text" name="status" value="<?php echo $i["status"] ?>" hidden/>
														<a href="halledit.php?hall_id=<?php echo $i["hall_id"]?>" class="btn btn-danger"><i class="fas fa-pen mr-1"></i>Edit</a>
														<button type="submit" name="action" value="remove" class="btn btn-danger"><i class="far fa-trash-alt mr-1"></i>Remove</button>
														<button type="submit" name="action" value="toggle" class="btn btn-primary"><i class="fas fa-sync mr-1"></i>Change status</button>
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
					<a class="btn btn-primary" href="hallcreate.php"><i class="fa fa-plus mr-1"></i>Add new facility</a>

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
	<script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/datatables.js"></script>
</body>

</html>