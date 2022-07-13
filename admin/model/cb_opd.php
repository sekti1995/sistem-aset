<?php
	error_reporting(E_ALL); ini_set('display_errors', 'off'); 
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	
	$idopd = isset($_GET['idopd']) ? $_GET['idopd'] : '';
	
	//$peran = cekLogin();
	
	if($idopd != ""){
		$a = " AND b = '$idopd' ";
	} else {
		$a = " ";
	}
	
	// $clause = "SELECT * FROM opd WHERE e = 1 GROUP BY c ORDER BY c ASC";
	$clause = "SELECT * FROM ref_sub2_unit WHERE kd_sub = 1 ORDER BY nm_sub2_unit ASC";
	
	$rs = mysql_query($clause) or die (mysql_error());
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		// $nm = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$row[b]' "));
		// $row["d"] = $nm["nm_sub2_unit"];
		// $row['id'] = $row['id_bar'];
		$row["c"] = $row["kd_unit"];
		$row["e"] = $row["kd_sub"];
		$row["g"] = $row["kd_sub2"];
		$row["b"] = $row["uuid_sub2_unit"];
		$row["h"] = $row["nm_sub2_unit"];
		$row["f"] = $row["nm_sub2_unit"];
		$row["d"] = $row["nm_sub2_unit"];
		
		
		array_push($items, $row);
	}
	//print_r( $items);
	//header('Content-type: application/json');
	echo json_encode($items);
	//echo  json_last_error();
	mysql_close();
	
?>