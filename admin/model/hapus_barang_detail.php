<?php

	require_once "../../config/db.koneksi.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$id = isset($_POST['id']) ? $_POST['id'] : '';
		
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nama_barang AS nama_bar, d.id_barang AS id_bar, SUM(jml_barang) AS jumlah, simbol AS nama_sat, 
				harga_barang AS harga, SUM(baik) AS baik, SUM(ringan) AS ringan, SUM(berat) AS berat, SUM(kadaluarsa) AS kadaluarsa, 
				d.id_gudang AS id_gud, nama_gudang AS nama_gud, 
				d.id_sumber_dana AS id_sum, nama_sumber AS nama_sumber,
				id_hapus_barang_detail AS id,
				d.uuid_skpd
				FROM hapus_barang_detail d
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				LEFT JOIN ref_gudang g ON d.id_gudang = g.id_gudang 
				LEFT JOIN ref_sumber_dana m ON d.id_sumber_dana = m.id_sumber
				WHERE id_hapus_barang = '$id' AND d.soft_delete=0
				GROUP BY d.id_barang, d.id_gudang, d.id_sumber_dana";
	/* $clause = "SELECT nama_barang AS nama_bar, d.id_barang AS id_bar, SUM(jml_barang) AS jumlah, simbol AS nama_sat, 
				SUM(baik) AS baik, SUM(ringan) AS ringan, SUM(berat) AS berat, SUM(kadaluarsa) AS kadaluarsa,
				id_usul_hapus_detail AS id,
				d.uuid_skpd
				FROM usul_hapus_detail d
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				WHERE id_usul_hapus = '$id' AND d.soft_delete=0
				GROUP BY d.id_barang"; */
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $total = 0;
	while($row = mysql_fetch_assoc($rs)){
		$smd = " ,IFNULL((SELECT SUM(jml_barang) FROM sp_out_detail od, sp_out so WHERE od.id_sp_out = so.id_sp_out
							AND od.id_barang ='$row[id_bar]' AND so.uuid_skpd = '$row[uuid_skpd]'
							AND od.soft_delete = 0 AND so.soft_delete = 0 AND so.status = 1), 0) AS pesan";	
		$r1 = mysql_fetch_assoc(mysql_query("SELECT IFNULL(SUM(jml_in-jml_out),0) AS saldo $smd
							FROM kartu_stok
							WHERE uuid_skpd = '$row[uuid_skpd]'
							AND id_barang = '$row[id_bar]' AND soft_delete = 0"));
		if($smd!='') $saldo = $r1['saldo'] - $r1['pesan'];
		
		$total = $row['jumlah']*$row['harga'];
		$row['jumlah'] = number_format($row['jumlah'], 0, ',', '.');
		$row['jumlah_stok'] = number_format($saldo, 0, ',', '.');
		$row['jumlah_usul'] = $row['jumlah'];
		$row['harga'] = number_format($row['harga'], 0, ',', '.');
		$row['harga_total'] = number_format($total, 0, ',', '.');
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