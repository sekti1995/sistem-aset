<?php
$saldo = '2815000,000000000000000';
	$ex = explode(",",$saldo);
	if($ex[1] > 0 ){
		$saldo = $ex[0].",".$ex[1];
	} else {
		$saldo = $ex[0];
	}
	
	echo number_format($saldo, 15, ',', '.'); 
	

	
?>