<?php
    include_once("db.php");
    $db=new db();
	if($_GET["gettimes"]=="false"){
		$days=[];
		$hall=str_replace("%"," ",$_GET["hall"]);
		$var= explode("-",$_GET["date"]);
		$res=$db->exec_query(sprintf("select DAY(fromtime) as day,uname from booking where YEAR(fromtime)='%s' and MONTH(fromtime)='%s' and hall=%d",$var[0],$var[1],$hall));
		foreach($res as $i) array_push($days,$i["day"]);
		header('Content-Type: application/json');
		echo json_encode($days);
	}
?>