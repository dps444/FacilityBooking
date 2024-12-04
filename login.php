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

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="container">
            <div style="margin-top:5%">
                <form class="form-group w-100 shadow p-4 mb-5 bg-white rounded"  action="login.php" method="POST">
				<h1>Login</h1><br/>
                    <input class="form-control w-50" type="text" name="uname" placeholder="Username" required="true"/><br/>
                    <br/>
                    <input class="form-control w-50" type="password" name="pass" placeholder="Password" required="true"/><br/>
                    <br/>
                    <button class="btn btn-primary" name="action" type="submit" value="login">Log in</button>
                </form>
            </div>
        </div>
        <?php
            session_start();
            include_once("db.php");
            if($_SERVER["REQUEST_METHOD"]=="POST" and isset($_POST["action"]) and  $_POST["action"]=="login"){
                $db=new db();
                if($db->status==false) echo "<script>alert('database connection error');</script>";
                else{
					$st=$db->prepare_statement("select type from user where uname=? and password=?");					
					$st->bind_param("ss",$_POST["uname"],$_POST["pass"]);
					$st->execute();
					$res=$st->get_result()->fetch_all(MYSQLI_ASSOC);
					if(count($res)>0 and $res[0]["type"]==0){
                        $_SESSION["token"]=$_POST["uname"];
                        echo "<script>window.location.href='index.php';</script>";
                    }
					else if(count($res)>0 and $res[0]["type"]==1){
						$_SESSION["token"]=$_POST["uname"];
                        echo "<script>window.location.href='admin.php';</script>";
					}
                    else echo "<script>alert('Invalid username or password');</script>";
                }
                $db->close();
            }
			else if($_SERVER["REQUEST_METHOD"]=="GET" and isset($_GET["action"]) and $_GET["action"]=="logout"){
					unset($_SESSION["token"]);
					echo "<script>alert('logged out succesfully');window.location.href='login.php'</script>";
			}
        ?>
                        </div>
                    </div>
                </div>

            

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>