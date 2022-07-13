<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_bar = isset($_POST['id_bar']) ? $_POST['id_bar'] : '';
	$id_gud = isset($_POST['id_gud']) ? $_POST['id_gud'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$id_surat = isset($_POST['id_surat']) ? $_POST['id_surat'] : '';
	$id_sp = isset($_POST['id_sp']) ? $_POST['id_sp'] : '';
	$jenis = isset($_POST['jenis']) ? $_POST['jenis'] : '';
	$tanggal = isset($_POST['tanggal']) ? balikTanggal($_POST['tanggal']) : '';
	$jumlah = isset($_POST['jumlah']) ?  $_POST['jumlah']  : '';
	
	
	if($jenis=='so'){ $smd = ""; }
	else{ 
		if($id_sp!='') $ids = "AND so.id_sp_out <> '$id_sp' "; else $ids = "";
		$smd = " ,IFNULL((SELECT SUM(jml_barang) FROM sp_out_detail od, sp_out so WHERE od.id_sp_out = so.id_sp_out
						AND od.id_barang ='$id_bar' AND so.uuid_skpd = '$id_sub' $ids
						AND od.soft_delete = 0 AND so.soft_delete = 0 AND so.status = 1), 0) AS pesan";
	}
	if($id_gud!='') $g = "AND id_gudang = '$id_gud'"; else $g = "";
	if($id_sum!='') $h = "AND id_sumber_dana = '$id_sum'"; else $h = "";
	if($tanggal!='') $t = "AND tgl_transaksi <= '$tanggal'"; else $t = "";
	
	$r1 = mysql_fetch_assoc(mysql_query("SELECT IFNULL(SUM(jml_in-jml_out),0) AS saldo $smd
										FROM kartu_stok
										WHERE uuid_skpd = '$id_sub' $g 
										AND id_barang = '$id_bar' AND soft_delete = 0"));
	/* $r2 = mysql_fetch_assoc(mysql_query("SELECT IFNULL(harga,0) AS harga FROM kartu_stok 
										WHERE uuid_skpd = '$id_sub' AND (jml_in <> 0 OR kode = 's')
										AND id_barang = '$id_bar' AND soft_delete = 0 AND kode <> 'm' $h $t
										ORDER BY tgl_transaksi DESC LIMIT 1")); */
								

								
	if($smd!='') $saldo = $r1['saldo'] - $r1['pesan'];
	else $saldo = $r1['saldo'];
	
	//$saldo = $r1['saldo'];
	//$harga = is_null($r2['harga']) ? 0 : $r2['harga'];
	//$total = number_format(($saldo * $harga), 0, ',', '.');
	$saldo = substr($saldo,0,-16);
	$saldo = str_replace(".","",$saldo);
	$response = array( 'saldo' => number_format($saldo, 0, ',', '.')); 
	//, 'harga'=>number_format($harga, 0, ',', '.'), 'total'=>$total);
	
	header('Content-type: application/json');
	echo json_encode($response);
	
	mysql_close();
?>	