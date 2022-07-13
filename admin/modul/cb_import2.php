<?php

error_reporting(E_ALL); ini_set('display_errors', 'off'); 
session_start();
require_once "../../config/db.koneksi.php";
require_once "../../config/db.function.php";

//$peran = cekLogin();
$kel = isset($_GET['kel']) ? $_GET['kel'] : '';
$idsub = isset($_GET['idsub']) ? $_GET['idsub'] : '';
$idsppb = isset($_GET['idsppb']) ? $_GET['idsppb'] : '';
$jenis_keluar = isset($_GET['jenis_keluar']) ? $_GET['jenis_keluar'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$idsubj = isset($_GET['idsubj']) ? $_GET['idsubj'] : '';
$nmkeg = isset($_GET['nmkeg']) ? $_GET['nmkeg'] : '';

//if($idsub == ""){
//	$idsub = $_SESSION['uidunit_plain'];
//}




	//$clause = "SELECT  a.id_barang AS id_bar,a.nm_barang AS nama_bar,simbol, b.id_satuan, jumlah_barang-jumlah_barang_isi AS jumlahren,harga AS hrgi FROM log_import a LEFT JOIN ref_satuan b ON a.id_satuan = b.id_satuan WHERE uuid_skpd = '$idsubj' and kd_kegiatan='$nmkeg'";

	$clause = "SELECT  a.id_barang AS id_bar,a.nm_barang AS nama_bar,simbol, b.id_satuan, jumlah_barang-jumlah_barang_isi AS jumlahren,harga AS hrgi ,
	c.kd_kel AS id_kel,nama_kelompok AS nama_kel,c.kd_kel AS  id_jns,nama_kelompok AS nama_jns,c.id_jenis AS id_jen,nama_jenis AS nama_jen
	FROM log_import a
	LEFT JOIN ref_satuan b ON a.id_satuan = b.id_satuan
	LEFT JOIN ref_jenis c ON a.id_subrek = c.id_jenis
	LEFT JOIN ref_kelompok d ON c.kd_kel = d.id_kelompok
	 WHERE uuid_skpd = '$idsubj' and nm_kegiatan like '%$nmkeg%'";

/* $clause = "SELECT id_barang AS id_bar, nama_barang AS nama_bar, simbol, s.id_satuan
FROM ref_barang b
LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
 
WHERE b.id_barang IS NOT NULL
ORDER BY  b.kd_sub2"; */

	print_r($clause);
	$rs = mysql_query($clause) or die (mysql_error());
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	$no =0;
	while($row = mysql_fetch_assoc($rs)){

		$row['nama_bar'] = $row['nama_bar'];
		$row['id'] = $row['id_bar'];
		$row['id_satuan'] = $row['id_satuan'];
		//$row['id_kelompok'] = $row['id_kelompok'];
		//$row['id_rekening'] = $row['id_kelompok'].".0";
		///$row['id_sub_rekening'] = $row['id_jenis'];
		$row['hrgi'] = number_format($row['hrgi'], 0, ',', '.');
		$row['jumlahren'] = number_format($row['jumlahren'], 0, ',', '.');
		//$row['jumlahren'] = $row['jumlahren'];
		array_push($items, $row);
		$no++;
	}
	echo json_encode($items);
	//echo  json_last_error();
	mysql_close();
?>