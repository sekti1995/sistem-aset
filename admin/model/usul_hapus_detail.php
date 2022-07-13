<?php

	require_once "../../config/db.koneksi.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	$id = isset($_POST['id']) ? $_POST['id'] : 'd437c33e-bc57-11e6-a47d-002637bd3942';
		
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nama_barang AS nama_bar, d.id_barang AS id_bar, SUM(jml_barang) AS jumlah, simbol AS nama_sat, 
				SUM(baik) AS baik, SUM(ringan) AS ringan, SUM(berat) AS berat, SUM(kadaluarsa) AS kadaluarsa,
				id_usul_hapus_detail AS id
				FROM usul_hapus_detail d
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				WHERE id_usul_hapus = '$id' AND d.soft_delete=0
				GROUP BY d.id_barang";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $total = 0;
	while($row = mysql_fetch_assoc($rs)){
		//$total = $row['jumlah']*$row['harga'];
		$row['jumlah'] = number_format($row['jumlah'], 0, ',', '.');
		//$row['harga'] = number_format($row['harga'], 0, ',', '.');
		//$row['harga_total'] = number_format($total, 0, ',', '.');
		$row['baik'] = number_format($row['baik'], 0, ',', '.');
		$row['ringan'] = number_format($row['ringan'], 0, ',', '.');
		$row['berat'] = number_format($row['berat'], 0, ',', '.');
		$row['kadaluarsa'] = number_format($row['kadaluarsa'], 0, ',', '.');
		
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>