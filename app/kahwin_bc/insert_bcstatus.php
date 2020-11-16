<?php
	include 'db.php';
	
	$id=$_POST['id'];
	$transactionHash=$_POST['transactionHash'];
	$waitingTime=$_POST['waitingTime'];
	
	$sql = "UPDATE rekod_perkahwinan set tarikh_transaksi_bc = CURRENT_TIMESTAMP(), transactionhash =  '$transactionHash', waiting_time = $waitingTime where id = $id";
	
	if (mysqli_query($conn, $sql)) {
		echo json_encode(array("statusCode"=>200));
	} 
	else {
		echo json_encode(array("statusCode"=>201));
	}
	
	mysqli_close($conn);
	
?>
 