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
 
	$clause = "SELECT id_barang AS id_barang, nama_barang AS nama_barang, kd_sub2, id_satuan
				FROM ref_barang 
				WHERE b.soft_delete = '0'";
				
	$rs = $DBcon->prepare($clause);
	$rs->execute();
	$r = $rs->rowCount();
	$rs2 = $DBcon->prepare($clause);
	$rs2->execute();
	$items = array();
	while($row = $rs2->fetch(PDO::FETCH_ASSOC)){
		$row['id_barang'] = $row['id_barang'];
		// $row["hrgi2"] = number_format($row['harga_index'], 0, '.', ',');;
		$row["nama_barang"] = str_replace("  ","",$row["nama_barang"]);
		$row["nama_barang"] = str_replace('"'," inc ",$row["nama_barang"]);
		//echo $row["nama_bar"]."<br>";
		array_push($items, $row);
	}
	//print_r( $items);
	header('Content-type: application/json');
	echo json_encode($items);
	//echo  json_last_error();
	//mysql_close();
	
?>