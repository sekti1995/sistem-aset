<?php
	//error_reporting(E_ALL); ini_set('display_errors', 'On'); 
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	$peran = cekLogin();
	$id_sub = isset($_GET['id']) ? $_GET['id'] : '';
	$jns = isset($_GET['jns']) ? $_GET['jns'] : '';
	
	if($id_sub=="" || $id_sub=="undefined"){
		$w = " AND MD5(k.uuid_skpd) = '$_SESSION[uidunit]'";
		$w1 = " AND MD5(k.uuid_sub2_unit) = '$_SESSION[uidunit]'";
	}else{
		$w = "AND k.uuid_skpd = '$id_sub'";
		$w1 = "AND k.uuid_sub2_unit = '$id_sub'";
	}
	if($jns==1){
		$que = mysql_fetch_assoc(mysql_query("SELECT a.uuid_sub2_unit AS id FROM ref_sub2_unit a 
				WHERE CONCAT_WS('.', a.kd_urusan, a.kd_bidang, a.kd_unit, a.kd_sub) = 
				(SELECT CONCAT_WS('.', k.kd_urusan, k.kd_bidang, k.kd_unit, '1') FROM ref_sub2_unit k 
				 WHERE k.uuid_sub2_unit IS NOT NULL $w1)"));
		$w = "AND k.uuid_skpd = '$que[id]'";
	}	
	
	if(isset($_GET['barang'])) $b = "AND b.id_barang IS NOT NULL";
	else $b = "";
	
	if(isset($_GET['stok'])){
		$clause = "SELECT k.id_barang AS id_bar, SUM(jml_in-jml_out) AS jml,
					IFNULL(b.nama_barang, bk.nama_barang_kegiatan) nama_bar, 
					IFNULL(s.simbol, s1.simbol) simbol, 
					IFNULL(b.id_satuan, bk.id_satuan) id_satuan
					FROM kartu_stok k 
					LEFT JOIN ref_barang b ON b.id_barang = k.id_barang 
					LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang 
					LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
					LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
					LEFT JOIN ref_satuan s1 ON bk.id_satuan = s1.id_satuan 
					WHERE k.soft_delete = 0 $w
					GROUP BY k.id_barang
					HAVING jml > 0
					ORDER BY j.kd_kel, j.kd_sub, b.kd_sub2";
	}else{
		$clause = "SELECT k.id_barang AS id_bar, SUM(jml_in-jml_out) AS jml,
					IFNULL(b.nama_barang, bk.nama_barang_kegiatan) nama_bar, 
					IFNULL(s.simbol, s1.simbol) simbol, 
					IFNULL(b.id_satuan, bk.id_satuan) id_satuan,
					IF(ISNULL(bk.id_barang_kegiatan), 'a', 'b') stat,
					IFNULL(j.kd_kel,j1.kd_kel) kd_kel, IFNULL(j.kd_sub,j1.kd_sub) kd_sub, 
					IFNULL(b.kd_sub2,bk.kode) AS kd_sub2
					FROM kartu_stok k 
					LEFT JOIN ref_barang b ON b.id_barang = k.id_barang 
					LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang 
					LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
					LEFT JOIN ref_jenis j1 ON j1.id_jenis = bk.id_jenis 
					LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
					LEFT JOIN ref_satuan s1 ON bk.id_satuan = s1.id_satuan 
					WHERE k.soft_delete = 0 $w $b
					GROUP BY k.id_barang
					ORDER BY stat, j.kd_kel, j.kd_sub, b.kd_sub2, j1.kd_kel, j1.kd_sub, bk.kode";
	}
	
	$rs = mysql_query($clause) or die (mysql_error());
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_bar'];
		$row['jml'] = number_format($row['jml'], 0, ',', '.');
		array_push($items, $row);
	}
	echo json_encode($items);
	mysql_close();
	
?>