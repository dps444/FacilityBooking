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
	<script>
            window.onload=()=>{
                let cdate="";
                const monthselect=document.getElementById("monthselect");
                const date=new Date();
                cdate+=date.getFullYear().toString()+"-"
                cdate+=(date.getMonth()+1).toLocaleString("en-US",{"minimumIntegerDigits":2});
                if(new URLSearchParams(window.location.search).get("date")) cdate=new URLSearchParams(window.location.search).get("date");
                monthselect.setAttribute("value",cdate.split("-")[0]+"-"+cdate.split("-")[1]);
				if(sessionStorage.getItem("hall")) document.getElementById("hallselect").value=sessionStorage.getItem("hall");
                dateSelector();
            }
			async function dateSelector(){
				const dayselect=document.getElementById("dayselect");
				const hall=document.getElementById("hallselect").value;
				sessionStorage.setItem("hall",hall);
				let selected=document.getElementById("monthselect").value.split("-");
				let numdays=new Date(parseInt(selected[0]),parseInt(selected[1]),0).getDate();
				dayselect.innerHTML="";
				let res=await fetch(`getdates.php?date=${selected.join("-")}&hall=${hall}&gettimes=false`);
				res=await res.json();				
				for(let i=0;i<numdays;i++){
					let cl="btn m-2 ";
					let isdisabled="";
					let day=(new Date(selected.join("-")+`-${i+1}`).toLocaleString("en-US",{"weekday":"long"}));
					if(res.indexOf((i+1).toString())!=-1) cl+=" bg-danger";
					if(res.indexOf((i+1).toString())!=-1 && new Date(selected.join("-")+"-"+(i+1).toString()) <= new Date()) cl="btn bg-warning text-dark";
					if(new Date(selected.join("-")+"-"+(i+1).toString()) <= new Date() || day==="Sunday"){
						isdisabled="disabled";
						cl+=" btn-secondary disabled ";
					}
					else cl+=" btn-success";
					dayselect.innerHTML+=`
						<a href='showinfo.php?hall=${hall}&date=${selected.join("-")+"-"+(i+1).toLocaleString("en-US",{"minimumIntegerDigits":2})}' style="width:100px"   class="${cl}" ${isdisabled}>${(i+1).toLocaleString("en-US",{"minimumIntegerDigits":2})}<br/>${day.substring(0,3).toUpperCase()}</a>
					`;
				}
			}
			function datesetter(){
				const from_date=document.getElementById("from_date");
				const to_date=document.getElementById("to_date");
				to_date.setAttribute("min",from_date.value);
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
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Hall booking</div>
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
								<div class="row">
									<div class="col w-75">
										<small class="form-text text-muted">Please select a month</small><br/>
										<input class="form-control w-50 mt-2" type="month" id="monthselect" onchange="dateSelector()"/><br/>
									</div>
									<div class="col w-25">
										<small class="form-text text-muted">Select the hall to book</small><br/>
										<select id="hallselect" class="form-control mt-2" onchange="dateSelector()">
											<?php
												$res=$db->exec_query("select * from hall where status=1");
												$sel="selected";
												foreach($res as $i){
													echo sprintf("<option value='%s' %s>%s</option>",$i["hall_id"],$sel,$i["hall_name"]);
													$sel="";
												}
											?>
										</select><br/>
									</div>
								</div>
								<div style="display:inline-block" class="mt-5" id="dayselect"></div>
								<div class="modal" role="dialog" id="infomodal">
									<?php
									if(isset($_GET["date"]) and isset($_GET["hall"])){
										include("showinfo.php");
									}
								?>
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

    

</body>

</html>