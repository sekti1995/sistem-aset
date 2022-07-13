<?php

	require_once "../../config/db.koneksi.php";
	require_once "../../config/library.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$id_sp = isset($_POST['id']) ? $_POST['id'] : '';
		
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nama_barang AS nama_bar, k.id_barang AS id_bar, jml_barang AS jumlah, simbol AS nama_sat, 
				b.id_satuan AS id_sat, harga_barang AS harga, k.keterangan AS ket, id_mutasi_detail AS id,
				id_sumber_dana AS id_sum, nama_sumber
				FROM mutasi_detail k
				LEFT JOIN ref_barang b ON k.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				LEFT JOIN ref_sumber_dana d ON k.id_sumber_dana = d.id_sumber
				WHERE id_mutasi = '$id_sp' AND k.soft_delete=0";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $total = 0;
	while($row = mysql_fetch_assoc($rs)){
		$hrg = $row['harga']*$row['jumlah'];
		$row['harga_asli'] = $hrg;
		$row['harga_sat'] = $row['harga'];
		$row['jumlah'] = number_format($row['jumlah'], 0, ',', '.');
		$row['harga'] = number_format($hrg, 0, ',', '.');
		
		$total += $row['harga_asli'];
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>