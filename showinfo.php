<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <div class="fs-4 p-3">
        <?php
            include_once("db.php");
            $db=new db();
            if(isset($_GET["date"])){
				$hall="seminar";
				if(isset($_SESSION["hall"])) $hall=$_SESSION["hall"];
                $res=$db->exec_query(sprintf("select booking_id,uname,hall,fname,DATE(fromtime) as date,TIME(fromtime) as fromtime,TIME(totime) as totime,event_name,status from booking where DATE(fromtime)='%s' and hall='%s'",$_GET["date"],$_GET["hall"]));
                if(sizeof($res)>0 and (strtotime($_GET["date"])>strtotime(date("Y-m-d"))==1)){                    
        ?>
                <h3><?php echo $res[0]["date"] ?></h2></br>
				<h3><?php echo $res[0]["hall"] ?> booked by <?php echo $res[0]["fname"] ?> for <?php echo $res[0]["event_name"] ?></h3>
                From: <?php echo $res[0]["fromtime"] ?><br/>
                To: <?php echo $res[0]["totime"] ?><br/>
				Status: <?php
					if($res[0]["status"]==0) echo "<span class='bg-warning rounded p-1'>Awaiting approval</span>";
					else echo "<span class='bg-success rounded p-1' style='color:black'>Booked</span>";
				?>
        <?php
                if($_SESSION["token"]==$res[0]["uname"]){
                    ?>
                <form class="mt-3" action="bookevent.php" onsubmit="return confirm('are you sure?')" method="POST">
                    <input type="text" name="booking_id" value="<?php echo $res[0]["booking_id"]?>" hidden/>
                    <button class="btn btn-primary" type="submit" name="action" value="cancel">Cancel</button>
                </form>
                <?php
                    
                }
                }
                else if(sizeof($res)==0 and (strtotime($_GET["date"])>strtotime(date("Y-m-d"))==1)){
					echo "No bookings on ".$_GET["date"].", you may claim this spot<br/>";
        ?>
                <form action="bookevent.php" method="POST" class="form mt-3" osubmit="return confirm('are you sure?')">
                    <input type="text" name="date" value="<?php echo $_GET["date"]?>" hidden/>
					<input type="text" name="hall" value="<?php echo $_GET["hall"]?>" hidden/>
                    <div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3" for="ename">Event Name: </label>
                        </div>
                        <div class="col">
                            <input class="form-control w-50" type="text" name="ename" required="true"/>
                        </div>
                    </div>
					<div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3" for="yname">Your name: </label>
                        </div>
                        <div class="col">
                            <input class="form-control w-50" type="text" name="yname" required="true"/>
                        </div>
                    </div>
					<div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3" for="email">Email: </label>
                        </div>
                        <div class="col">
                            <input class="form-control w-50" type="email" name="email" required="true"/>
                        </div>
                    </div>
					<div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3" for="dept">Department: </label>
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
                            <input id="from_date" class="form-control w-25" type="time" min="08:00:00" max="19:00:00" name="from_time" required="true"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-2">
                            <label class="form-text ml-3" for="to_time">To: </label>
                        </div>
                        <div class="col">
                            <input id="to_date" onchange="datesetter()" class="form-control w-25" type="time" min="08:00:00" max="19:00:00" name="to_time"  required="true"/>
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
            }
        ?>
        </div>
    </body>
</html>
