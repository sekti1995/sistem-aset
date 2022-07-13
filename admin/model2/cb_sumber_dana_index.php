<?php
	//session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	error_reporting(E_ALL); ini_set('display_errors', 'off'); 
	//$peran = cekLogin();
	 
	$semua = isset($_GET['semua']) ? $_GET['semua'] : '';
	
		$clause = "SELECT id_sumber AS id, nama_sumber AS text FROM ref_sumber_dana ORDER BY id_sumber";
	
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){ 
		if($r==1) $row['selected'] = true;
		
		
		array_push($items, $row);
	}
	if($semua != 'no'){
		$row['id'] = '';
		$row['text'] = 'Semua Sumber Dana';
	}
	array_push($items, $row);
	
	echo json_encode($items);
	mysql_close();
	
?>