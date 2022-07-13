<?php
	error_reporting(E_ALL); ini_set('display_errors', 'off'); 
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	//$peran = cekLogin();
	$opd = isset($_GET['opd']) ? $_GET['opd'] : '';
	
	if($opd != ""){
		$cari = " AND c = '$opd' ";
	} else {
		$cari = " ";
	}
	
	// $clause = "SELECT * FROM opd WHERE e > 1 AND g = 1 $cari GROUP BY e ORDER BY f ASC";
	$clause = "SELECT * FROM opd WHERE g = 1 $cari GROUP BY e ORDER BY f ASC";
	
	$rs = mysql_query($clause) or die (mysql_error());
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$nm = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$row[b]' "));
		$row["f"] = $nm["nm_sub2_unit"];
		
		$row['id'] = $row['id_bar'];
		array_push($items, $row);
	}
	//print_r( $items);
	//header('Content-type: application/json');
	echo json_encode($items);
	//echo  json_last_error();
	mysql_close();
	
?>