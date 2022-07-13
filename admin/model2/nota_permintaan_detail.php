<?php

	require_once "../../config/db.koneksi.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	$id_sp = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT IFNULL(b.nama_barang, bk.nama_barang_kegiatan) nama_bar, o.id_barang AS id_bar, jumlah, 
				IFNULL(s.simbol, s1.simbol) nama_sat, ket,
				IFNULL(b.id_satuan, bk.id_satuan) id_sat, id_nota_minta_detail AS id
				FROM nota_minta_detail o
				LEFT JOIN ref_barang b ON o.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				LEFT JOIN ref_barang_kegiatan bk ON o.id_barang = bk.id_barang_kegiatan 
				LEFT JOIN ref_satuan s1 ON bk.id_satuan = s1.id_satuan 
				WHERE id_nota_minta = '$id_sp' AND o.soft_delete=0";
	
	$nota = mysql_query("SELECT IF(stat_untuk=0, unit_peminta, unit_dituju) AS id FROM nota_minta WHERE id_nota_minta = '$id_sp'");
	$n = mysql_fetch_assoc($nota);
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $total = 0;
	while($row = mysql_fetch_assoc($rs)){
		$st = mysql_fetch_row(mysql_query("SELECT SUM(jml_in-jml_out) AS stok FROM kartu_stok WHERE uuid_skpd = '$n[id]' 
					AND id_barang = '$row[id_bar]' AND soft_delete=0 "));
		$row['jumlah_stok'] = number_format($st[0], 0, ',', '.');			
		$row['jumlah'] = number_format($row['jumlah'], 0, ',', '.');
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>