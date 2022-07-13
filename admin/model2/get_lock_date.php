<?php
//session_start();
//if(!isset($_SESSION['namauser'])){
 //   die("Anda belum login, Bila login klik di <a href=../index.php>sini</a>");//jika belum login jangan lanjut..
//}

	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT * FROM kunci_entri ";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		
		$row['tgl_awal'] = balikTanggalIndo($row['tgl_mulai']);
		$row['tgl_akhir'] =  balikTanggalIndo($row['tgl_sampai']);
		
		$ex1 = explode('-',$row['tgl_mulai']);
		$row['tgl1'] = (float)$ex1[2];
		$row['bln1'] = (float)$ex1[1];
		$row['thn1'] = (float)$ex1[0];
		$row['bln1'] = $row['bln1']-1;
		
		$ex2 = explode('-',$row['tgl_sampai']);
		$row['tgl2'] = (float)$ex2[2];
		$row['bln2'] = (float)$ex2[1];
		$row['thn2'] = (float)$ex2[0];
		$row['bln2'] = $row['bln2']-1;
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($items);
	mysql_close();
?>