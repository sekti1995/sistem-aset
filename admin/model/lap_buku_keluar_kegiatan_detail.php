<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	//$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$id_masuk_detail = isset($_GET['id_masuk_detail']) ? $_GET['id_masuk_detail'] : '2aba9b41-3a03-11e7-9301-6cae8b5fc378';
	 
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT *
				FROM masuk_detail_rinci d
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				WHERE d.id_masuk_detail = '$id_masuk_detail' ";
			
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$total = $row['harga'] * $row['jumlah']; 
		$row['jml_barang'] = number_format($row['jumlah'], 0, ',', '.')." ";
		$row['hrg_barang'] = number_format($row['harga'], 0, ',', '.');
		$row['tot_harga'] = number_format($total, 0, ',', '.');
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>