<?php
	error_reporting(E_ALL); ini_set('display_errors', 'off'); 
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	//$peran = cekLogin();
	$opd = isset($_GET['opd']) ? $_GET['opd'] : '';
	$upt = isset($_GET['upt']) ? $_GET['upt'] : '';
	$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : '';
	$id_bar = isset($_GET['id_bar']) ? $_GET['id_bar'] : '';
	
	if($opd != ""){
		$cari = " AND kd_unit = '$opd' ";
	} else {
		$cari = " ";
	}
	
	if($_SESSION['jenis'] == ""){
	
		if($jenis != ""){
			$cari .= " AND kd_sub = 1 ";
		} else {
			$cari .= " AND kd_sub > 1 ";
		}
		
		$cari .= " AND kd_sub2 = 1 ";
		
	}
	
	
	$clause = "SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit IS NOT NULL $cari ORDER BY nm_sub2_unit ASC";
	
	$rs = mysql_query($clause) or die (mysql_error());
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		// $nm = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$row[b]' "));
		$row["c"] = $row["kd_unit"];
		$row["e"] = $row["kd_sub"];
		$row["g"] = $row["kd_sub2"];
		$row["b"] = $row["uuid_sub2_unit"];
		$row["h"] = $row["nm_sub2_unit"];
		$row["f"] = $row["nm_sub2_unit"];
		$row["d"] = $row["nm_sub2_unit"];
		
		$row['id'] = $row['id_bar'];
		array_push($items, $row);
	}
	//print_r( $items);
	//header('Content-type: application/json');
	echo json_encode($items);
	//echo  json_last_error();
	mysql_close();
	
?>