<?php
	error_reporting(E_ALL); ini_set('display_errors', 'On'); 
	require_once "../../config/db.koneksi.php";
	require_once "../../config/library.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
	$id_sp = isset($_POST['id']) ? $_POST['id'] : '';
	$id_sp = isset($_GET['id']) ? $_GET['id'] : '';
		
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nama_barang AS nama_bar, k.id_barang AS id_bar, FORMAT(jml_barang, 0,'de_DE') AS jumlah, 
				simbol AS nama_sat, b.id_satuan AS id_sat, FORMAT(harga_barang, 0,'de_DE') AS harga, 
				id_keluar_detail AS id, (harga_barang*jml_barang) AS jmlhrg_asli,
				harga_barang AS harga_asli,	FORMAT((harga_barang*jml_barang), 0,'de_DE') AS jmlhrg
				FROM keluar_detail k
				LEFT JOIN ref_barang b ON k.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				WHERE id_keluar = '$id_sp' AND k.soft_delete=0";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $total = 0;
	while($row = mysql_fetch_assoc($rs)){
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>