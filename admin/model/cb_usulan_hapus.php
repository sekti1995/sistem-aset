<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";

	// $clause = "SELECT * FROM usul_hapus WHERE uuid_skpd = '$_GET[id_sub]' AND soft_delete = 0";
	$clause = "SELECT t1.* FROM usul_hapus t1
				LEFT JOIN hapus_barang t2 ON t1.no_ba_usulan = t2.no_ba_hapus AND t1.tgl_ba_usulan = t2.tgl_ba_hapus 
				WHERE t1.uuid_skpd = '$_GET[id_sub]' AND t1.soft_delete = 0 AND t2.id_hapus_barang IS NULL";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row["tgl_ba_usulan"] = balikTanggalIndo($row["tgl_ba_usulan"]);
		$row['id'] = $row['id_usul_hapus'];
		$row['text'] = $row['no_ba_usulan'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>