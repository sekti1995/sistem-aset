<?php
	error_reporting(E_ALL); ini_set('display_errors', 'on'); 
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	//$peran = cekLogin();
	$kel = isset($_GET['kel']) ? $_GET['kel'] : '';
	$idsub = isset($_GET['idsub']) ? $_GET['idsub'] : '';
	$idsppb = isset($_GET['idsppb']) ? $_GET['idsppb'] : '';
	$jenis_keluar = isset($_GET['jenis_keluar']) ? $_GET['jenis_keluar'] : '';
	$search = isset($_GET['search']) ? $_GET['search'] : '';
 
	$clause = "SELECT id_barang AS id_bar, nama_barang AS nama_bar, simbol, s.id_satuan, FORMAT(harga_index, 0,'de_DE') AS hrgi,
				CONCAT_WS('.', kd_kel, kd_sub, kd_sub2) AS kode, j.nama_jenis
				FROM ref_barang b
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
				WHERE b.soft_delete = '0'
				ORDER BY j.kd_kel, j.kd_sub, b.kd_sub2";
				
	
	$rs = mysql_query($clause) or die (mysql_error());
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_bar'];
		$row["hrgi2"] = str_replace(".","",$row["hrgi"]);
		$row["nama_bar"] = str_replace("  ","",$row["nama_bar"]);
		$row["nama_bar"] = str_replace('"'," inc ",$row["nama_bar"]);
		//echo $row["nama_bar"]."<br>";
		array_push($items, $row);
	}
	//print_r( $items);
	header('Content-type: application/json');
	echo json_encode($items);
	//echo  json_last_error();
	mysql_close();
	
?>