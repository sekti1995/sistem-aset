<?php
	//session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	error_reporting(E_ALL); ini_set('display_errors', 'off'); 
	//$peran = cekLogin();
	 
	$semua = isset($_GET['semua']) ? $_GET['semua'] : '';
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	
	$clause = "SELECT b.keterangan, id_barang AS id_bar, nama_barang AS nama_bar, simbol, s.id_satuan, FORMAT(harga_index, 0,'de_DE') AS hrgi,
    CONCAT_WS('.', kd_kel, kd_sub, kd_sub2) AS kode ,b.id_jenis, j.nama_jenis AS namajen
    FROM ref_barang b
    LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
    LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
    WHERE b.id_barang IS NOT NULL 
    ORDER BY j.kd_kel, j.kd_sub, b.kd_sub2";

	//$clause = "SELECT a.id_sumber AS id,(SELECT DISTINCT id_sumber_dana FROM log_import WHERE a.id_sumber=id_sumber_dana AND uuid_skpd='$id') AS id2,a.nama_sumber AS TEXT FROM ref_sumber_dana a ORDER BY id_sumber";
	//echo($clause);
	$rs = mysql_query($clause) or die (mysql_error());
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	$no =0;
	while($row = mysql_fetch_assoc($rs)){
		$row['nama_bar'] = $row['nama_bar']." ".$row['keterangan'];
		$row['id'] = $row['id_bar'];
        //$row['simbol'] = $row['simbol'];
        //$row['simbol'] = $row['simbol'];
		
		// if($no <= 135){
			array_push($items, $row);
		// }
		$no++;
	}
	//print_r( $items);
	//header('Content-type: application/json');
	echo json_encode($items);
	//echo  json_last_error();
	mysql_close();
	
?>