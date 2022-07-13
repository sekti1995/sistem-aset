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
	
	//if($idsub == ""){
	//	$idsub = $_SESSION['uidunit_plain'];
	//}
	
	if($search != "" && strlen($search) > 4 ){
		$cari = " AND b.nama_barang LIKE '%$search%' ";
	} else {
		$cari = " "; 
	} 
	
	if($idsubj != ""){
		$cari .= " AND b.id_jenis = '$idsubj' ";
	}else {
		$cari .= " ";
	}
	if($idsppb!=""){
			$cek = mysql_fetch_assoc(mysql_query("SELECT b.keterangan, sd.id_barang AS id_bar, nama_barang AS nama_bar, simbol, s.id_satuan,
					(SELECT IFNULL(DATE_FORMAT(MAX(tgl_terima), '%d-%m-%Y'), '00-00-0000') FROM keluar_detail kd 
					WHERE kd.id_barang = sd.id_barang AND kd.uuid_skpd = sd.uuid_skpd AND kd.soft_delete = '0') AS tgl_akhir
						FROM sp_out_detail sd
						LEFT JOIN ref_barang b ON sd.id_barang = b.id_barang
						LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan
						WHERE sd.id_sp_out = '$idsppb' AND sd.soft_delete = 0 GROUP BY sd.id_barang LIMIT 1"));
			if($cek['nama_bar'] == ''){
				$clause = "SELECT b.keterangan, sd.id_barang AS id_bar, nama_barang_kegiatan AS nama_bar, simbol, s.id_satuan,
						(SELECT IFNULL(DATE_FORMAT(MAX(tgl_terima), '%d-%m-%Y'), '00-00-0000') FROM keluar_detail kd 
						WHERE kd.id_barang = sd.id_barang AND kd.uuid_skpd = sd.uuid_skpd AND kd.soft_delete = '0') AS tgl_akhir
							FROM sp_out_detail sd
							LEFT JOIN ref_barang_kegiatan b ON sd.id_barang = b.id_barang_kegiatan
							LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan
							WHERE sd.id_sp_out = '$idsppb' AND sd.soft_delete = 0 GROUP BY sd.id_barang";
			} else { 
				$clause = "SELECT b.keterangan, sd.id_barang AS id_bar, nama_barang AS nama_bar, simbol, s.id_satuan,
					(SELECT IFNULL(DATE_FORMAT(MAX(tgl_terima), '%d-%m-%Y'), '00-00-0000') FROM keluar_detail kd 
					WHERE kd.id_barang = sd.id_barang AND kd.uuid_skpd = sd.uuid_skpd AND kd.soft_delete = '0') AS tgl_akhir
						FROM sp_out_detail sd
						LEFT JOIN ref_barang b ON sd.id_barang = b.id_barang
						LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan
						WHERE sd.id_sp_out = '$idsppb' AND sd.soft_delete = 0 GROUP BY sd.id_barang";
			}
	}else{
		if($kel=='3' || $kel=='4'){
			$clause = "SELECT b.keterangan, id_barang_kegiatan AS id_bar, nama_barang_kegiatan AS nama_bar, simbol, s.id_satuan 
						FROM ref_barang_kegiatan b
						LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
						LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
						WHERE uuid_skpd = '$idsub'
						ORDER BY j.kd_kel, j.kd_sub, b.kode";
		}else{
			$clause = "SELECT b.keterangan, id_barang AS id_bar, nama_barang AS nama_bar, simbol, s.id_satuan, FORMAT(harga_index, 0,'de_DE') AS hrgi,
						CONCAT_WS('.', kd_kel, kd_sub, kd_sub2) AS kode 
						FROM ref_barang b
						LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
						LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
						WHERE b.id_barang IS NOT NULL $cari
						ORDER BY j.kd_kel, j.kd_sub, b.kd_sub2";
		}	
	}	
	
	$rs = mysql_query($clause) or die (mysql_error());
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	$no =0;
	while($row = mysql_fetch_assoc($rs)){
		$row['nama_bar'] = $row['nama_bar']." ".$row['keterangan'];
		$row['id'] = $row['id_bar'];
		
		// if($no <= 135){
			array_push($items, $row);
		// }
		$no++;
	}
	//print_r( $items);
	//header('Content-type: application/json');
	echo json_encode($items);
	//echo  json_last_error();
	mysql_close();
	
?>