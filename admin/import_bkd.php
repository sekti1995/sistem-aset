<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
include_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();

/* $q = mysql_query("SELECT * FROM tmp_data");
while($row = mysql_fetch_assoc($q)){
	$barr = mysql_fetch_assoc(mysql_query("SELECT id_barang FROM ref_barang WHERE REPLACE(nama_barang,' ','') = '$row[b]'"));
	if($barr['id_barang']==""){
	echo " =================================================== ".$row['a']." ".$row['b']."<br>";
	} else {
	echo $barr['id_barang']."<br>";
	}
}
 */
 
// SALDO AWAL

$uuid_skpd = 'cfa58008-5543-11e6-a2df-000476f4fa98';

$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
$uuid = $u[0];

$datime = date("Y-m-d H:i:s");
$q = mysql_query("SELECT * FROM tmp_data");
while($row = mysql_fetch_assoc($q)){
	$barr = mysql_fetch_assoc(mysql_query("SELECT id_barang FROM ref_barang WHERE REPLACE(nama_barang,' ','') = '$row[b]'"));
	$id_barang = $barr['id_barang'];
	
	$u2 = mysql_fetch_row(mysql_query("SELECT UUID()"));
	$uuid2 = $u2[0];
	/* 
	mysql_query("INSERT INTO adjust_detail (id_adjust_detail, id_adjust, uuid_skpd, id_barang, jumlah, harga, 
											create_date, creator_id)
									VALUES ('$uuid2', '$uuid', '$uuid_skpd', '$id_barang', '$row[f]', '$row[e]',
											'$datime', 'IMPORT-BKD-17092018')");
	 */	
	 
	 /* SALDO AWAL
	$que = mysql_query("INSERT INTO kartu_stok 	(id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
											id_sumber_dana,
											id_transaksi, id_transaksi_detail,
											tgl_transaksi, ta, jml_in, jml_out, harga, kode,
											create_date, soft_delete, creator_id)
									VALUES	(UUID(), '$uuid_skpd', '$id_barang', '1', '24bd5f0e-a1ac-11e6-b5b7-6cae8b5fc378', 
											'28',
											'$uuid', '$uuid2',
											'2017-12-30', '2018', '$row[f]', 0, '$row[e]', 'a',
											'$datime', 0, 'IMPORT-BKD-17092018')");
	 */
	$que = mysql_query("INSERT INTO kartu_stok 	(id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
											id_sumber_dana,
											id_transaksi, id_transaksi_detail,
											tgl_transaksi, ta, jml_in, jml_out, harga, kode,
											create_date, soft_delete, creator_id)
									VALUES	(UUID(), '$uuid_skpd', '$id_barang', '1', '24bd5f0e-a1ac-11e6-b5b7-6cae8b5fc378', 
											'28',
											'$uuid', '$uuid2',
											'2018-06-30', '2018', '0', '$row[l]', '$row[e]', 'ok',
											'$datime', 0, 'IMPORT-OUT-BKD-17092018')");
											
											
	if($que){
		echo "OK<br>";
	} else {
		echo "GAGAL<br>";
	}
}
?>