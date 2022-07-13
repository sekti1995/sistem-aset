<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	$peran = cekLogin();
	
	if($peran==MD5('3')) $id_sub = " AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub)) = '$_SESSION[idsubunit]'";
	else $id_sub = "";
	$unit = isset($_GET['id_unit']) ? " AND CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) ='$_GET[id_unit]'" : '';
	
	$all = isset($_GET['all']) ? "ya" : '';
	
	if($all=="ya") $where = "";
	else $where = "$unit $id_sub";
	
	$clause = "SELECT *, CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub) AS id_sub_unit 
				FROM ref_sub_unit WHERE kd_sub IS NOT NULL $where";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_sub_unit'];
		$row['text'] = $row['nm_sub_unit'];
		if($peran==MD5('3') AND $all=="" ) $row['selected'] = true;
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>