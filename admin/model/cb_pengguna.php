<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	$peran = cekLogin();
	$id = (isset($_GET['id'])&&$_GET['id']!='undefined') ? $_GET['id'] : '';
	
	if($id!="") $w = "WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub) = '$id'";
	else{
		if($peran==md5('3')) $w = " WHERE MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub)) = '$_SESSION[idsubunit]'";
		else $w="";
	}
	
	$clause = "SELECT id_pengelola AS id, nama_pengelola AS nama FROM ref_pengelola ";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>