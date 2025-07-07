<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
	<link href="resources/logo.jpg" rel="icon">
</head>

<body class="bg-gradient-primary">

    <div class="container">	
		
		<section class="container-fluid p-3 w-50 rounded shadow" style="margin-top:5%;background-color:white">
			<section class="row justify-content-center">
			  <section class="col-12">
				<form class="form-container" action="login.php" method="POST">
				<div class="form-group">
				  <h4 class="text-center font-weight-bold"> Login </h4>
				  <label>Username</label>
				   <input type="text" class="form-control" name="uname" placeholder="Enter username" required/>
				</div>
				<div class="form-group">
				  <label>Password</label>
				  <input type="password" class="form-control" name="pass" placeholder="Password" required/>
				</div>
				<button type="submit" name="action" value="login" class="btn btn-primary btn-block">Log in</button>
				</form>
			  </section>
			</section>
		</section>
        <?php
            session_start();
            include_once("db.php");
			if($_SERVER["REQUEST_METHOD"]=="GET" and !isset($_GET["action"])){
				if(isset($_SESSION["token"]) and $_SESSION["isadmin"]=="1") echo "<script>window.location.href='admin.php'</script>";
				else if(isset($_SESSION["token"]) and $_SESSION["isadmin"]=="0") echo "<script>window.location.href='index.php'</script>";
			}
			else if($_SERVER["REQUEST_METHOD"]=="GET" and isset($_GET["action"]) and $_GET["action"]=="logout"){
					unset($_SESSION["token"]);
					unset($_SESSION["isadmin"]);
					echo "<script>sessionStorage.clear();window.location.href='login.php'</script>";
			}
            else if($_SERVER["REQUEST_METHOD"]=="POST" and isset($_POST["action"]) and  $_POST["action"]=="login"){
                $db=new db();
                if($db->status==false) echo "<script>alert('database connection error');</script>";
                else{
					$st=$db->prepare_statement("select type from user where uname=? and password=?");					
					$st->bind_param("ss",$_POST["uname"],$_POST["pass"]);
					$st->execute();
					$res=$st->get_result()->fetch_all(MYSQLI_ASSOC);
					if(count($res)>0 and $res[0]["type"]==0){
                        $_SESSION["token"]=$_POST["uname"];
						$_SESSION["isadmin"]="0";
                        echo "<script>window.location.href='index.php';</script>";
                    }
					else if(count($res)>0 and $res[0]["type"]==1){
						$_SESSION["token"]=$_POST["uname"];
						$_SESSION["isadmin"]="1";
                        echo "<script>window.location.href='admin.php';</script>";
					}
                    else echo "<script>alert('Invalid username or password');</script>";
                }
                $db->close();
            }
        ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>