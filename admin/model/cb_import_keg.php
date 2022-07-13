<?php
	//session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	error_reporting(E_ALL); ini_set('display_errors', 'off'); 
	//$peran = cekLogin();
	 
	$semua = isset($_GET['semua']) ? $_GET['semua'] : '';
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$id_sbr = isset($_GET['idsbr']) ? $_GET['idsbr'] : '';
	
	//$clause = "SELECT a.id_sumber AS id,(SELECT DISTINCT id_sumber_dana FROM log_import WHERE a.id_sumber=id_sumber_dana AND uuid_skpd='$id') AS id2, a.nama_sumber AS text FROM ref_sumber_dana a ORDER BY id_sumber";

	$clause = "SELECT distinct a.uuid_skpd,a.kd_kegiatan as id,a.nm_kegiatan as text FROM log_import a WHERE a.uuid_skpd = '$id' and a.id_sumber_dana='$id_sbr'";
	
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){ 
		if($r==1) $row['selected'] = true;
		
		
		array_push($items, $row);
	}
	
	
	echo json_encode($items);
	mysql_close();
	
?>