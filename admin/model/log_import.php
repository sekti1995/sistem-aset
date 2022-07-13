<?php

	require_once "../../config/db.koneksi.php";
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$cek = isset($_GET['cek']) ? 'ya' : '';

	$cari = isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
	$offset = ($page-1)*$rows;
	$result = array();

	$where = " where nm_kegiatan like '%$cari%' OR b.nm_sub2_unit like '%$cari%' OR a.nm_barang like '%$cari%' ";

	if($id!="" && $id!="undefined"){ 
	$clause = "SELECT distinct uuid_skpd as id_ren ,id_sumber_dana as id_sumber FROM log_import a
	 WHERE a.uuid_skpd = '$id'";
	}else{
		$clause = "SELECT a.id_rencana,a.uuid_skpd AS id_sub,b.nm_sub2_unit AS uuid_skpd, a.nm_kegiatan,a.nm_barang,c.nama_satuan,a.jumlah_barang,a.jumlah_barang_isi,a.harga,a.create_date,a.hasil  FROM log_import a
		INNER JOIN ref_sub2_unit b ON a.uuid_skpd=b.uuid_sub2_unit
		INNER JOIN ref_satuan c ON a.id_satuan=c.id_satuan $where";
	}
	// print_r($clause);
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id_ren'] = $row['id_ren'];		
		$row['id_sumber'] = $row['id_sumber'];		
		if($r==1) $row['selected'] = true;
		$row['jumlah_barang'] = number_format($row['jumlah_barang'], 0, ',', '.');
		$row['jumlah_barang_isi'] = number_format($row['jumlah_barang_isi'], 0, ',', '.');
		$row['harga'] = number_format($row['harga'], 0, ',', '.');
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($items);
	mysql_close();
?>