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
                <div class="sidebar-brand-text mx-3">Facility booking</div>
            </span>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="booking_history.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Booking history</span></a>
            </li>
			<li class="nav-item active">
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
                        <h1 class="h3 mb-0 text-gray-800">Raise an issue</h1><br/>						
                    </div>

                    <!-- Content Row -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
							<form class="form w-50" action="raiseissue.php" method="POST" onsubmit="return confirm('are you sure?');">
								Issue<br/><textarea name="issue" class="form-control" required></textarea><br/>
								Requested facility<br/>
								<select name="hall" class="form-control" required>
								<option value="" selected disabled hidden>Choose here</option>
								<?php
									$res=$db->exec_query("select * from hall where status=1");
									foreach($res as $i)	echo sprintf("<option value='%s'>%s</option>",$i["hall_id"],$i["hall_name"]);									
								?>
								</select><br/>
								Your name<br/><input type="text" class="form-control" name="fname" required/><br/>
								Date<br/><input type="date" class="form-control w-25" name="date" required/><br/>
								<button class="btn btn-primary" type="submit" name="action" value="create"><i class="fa fa-plus mr-1"></i>Submit</button>
								<button class="btn btn-secondary" type="reset"><i class=""></i>Clear</button>
							</form>
                        </div>
                    </div>

                    <?php
						if($_SERVER["REQUEST_METHOD"]=="POST"){
							$st=$db->prepare_statement("insert into issue (descr,hall_id,uname,fname,req_date) values (?,?,?,?,?)");
							$st->bind_param("sdsss",$_POST["issue"],$_POST["hall"],$_SESSION["token"],$_POST["fname"],$_POST["date"]);
							$st->execute();
							echo "<script>alert('issue has been submitted');window.location.href='raiseissue.php'</script>";
						}
					?>

                    

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