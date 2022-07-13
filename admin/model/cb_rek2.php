<?php
	//error_reporting(E_ALL); ini_set('display_errors', 'off'); 
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	//$peran = cekLogin();
	$kel = isset($_GET['kel']) ? $_GET['kel'] : '';
	$idsub = isset($_GET['idsub']) ? $_GET['idsub'] : '';
	$idsppb = isset($_GET['idsppb']) ? $_GET['idsppb'] : '';
	$jenis_keluar = isset($_GET['jenis_keluar']) ? $_GET['jenis_keluar'] : '';
	$search = isset($_GET['search']) ? $_GET['search'] : '';
	
	
	if($search != "" && strlen($search) > 4 ){
		$cari = " WHERE b.nama_barang LIKE '%$search%' ";
	} else {
		$cari = " "; 
	} 
	
	$clause = "SELECT *, nama_jenis AS nama_jns
				FROM ref_jenis
				WHERE kd_sub = '0'
				ORDER BY kd_kel";
						
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array(); $no = 1;
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_jenis'];
		$row['id_jns'] = $row['kd_kel'].'.'.$row['kd_sub'];
		array_push($items, $row);
	}
	//print_r( $items);
	//header('Content-type: application/json');
	echo json_encode($items);
	//echo  json_last_error();
	mysql_close();
	
?>