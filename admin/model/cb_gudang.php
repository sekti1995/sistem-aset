<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	//$peran = cekLogin();
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$cek = isset($_GET['cek']) ? 'ya' : '';
	
	if($id!="" && $id!="undefined") $w = "WHERE g.uuid_skpd = '$id'";
	else{
		$w = " WHERE MD5(g.uuid_skpd) = '$_SESSION[uidunit]'";
	}
	
	if($cek=='ya'){ 
		$clause = "SELECT DISTINCT(k.id_gudang) AS id_gud, nama_gudang AS nama_gud 
					FROM kartu_stok k LEFT JOIN ref_gudang g ON k.id_gudang = g.id_gudang $w";
	}else{
		$clause = "SELECT id_gudang AS id_gud, nama_gudang AS nama_gud FROM ref_gudang g $w";
	}
	
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_gud'];
		if($r==1) $row['selected'] = true;
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>