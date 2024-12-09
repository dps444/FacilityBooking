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
	</head>
    <body>
        <div class="container border border-lg rounded p-3 shadow" style="background-color:#f0f0f0;margin-top:5%">
        <?php
            include_once("db.php");
			session_start();
            $db=new db();
			if(!isset($_SESSION["token"]) or (isset($_SESSION["isadmin"]) and $_SESSION["isadmin"]=="0")) echo "<script>window.location.href='login.php'</script>";
            if($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET["hall_id"])){
                $res=$db->exec_query(sprintf("select * from hall where hall_id=%s",$_GET["hall_id"]));
                if(sizeof($res)>0){
        ?>
                <form action="halledit.php" method="POST" class="form mt-3" osubmit="return confirm('are you sure?')">
					<input type="text" name="hall_id" value="<?php echo $_GET["hall_id"]?>" hidden/>
                    <div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3">Name </label>
                        </div>
                        <div class="col">
                            <input class="form-control w-50" type="text" name="hname" value="<?php echo $res[0]["hall_name"]?>" required="true"/>
                        </div>
                    </div>
					<div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3">Capacity </label>
                        </div>
                        <div class="col">
                            <input class="form-control w-50" type="number" name="capacity" value="<?php echo $res[0]["capacity"]?>" required="true"/>
                        </div>
                    </div>
					<div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3">Description </label>
                        </div>
                        <div class="col">
                            <textarea class="form-control w-50" name="descr" required="true"><?php echo $res[0]["description"]?></textarea>
                        </div>
                    </div>
                    <button type="submit" name="action" value="edit" class="btn btn-success mt-3"><i class="fa fa-save mr-1"></i>Save changes</button>
                </form>
			<?php
				}
			}
			else if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["action"])){
					$st=$db->prepare_statement("update hall set hall_name=?, description=?, capacity=? where hall_id=?");
					$st->bind_param("ssdd",$_POST["hname"],$_POST["descr"],$_POST["capacity"],$_POST["hall_id"]);
					$st->execute();
					echo "<script>alert('Facility updated');window.location.href='hallmanage.php';</script>";
			}
			?>
		</br></br>
		<a class="btn btn-secondary mt-3" href="hallmanage.php"><i class="fas fa-reply mr-1"></i>Go Back</a>
        </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
