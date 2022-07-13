<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	error_reporting(E_ALL); ini_set('display_errors', 'on'); 
	
	//$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sumber = isset($_POST['id_sumber']) ? $_POST['id_sumber'] : '';
	$tgl_awal = isset($_POST['tgl_awal']) ? balikTanggal($_POST['tgl_awal']) : '';
	$tgl_akhir = isset($_POST['tgl_akhir']) ? balikTanggal($_POST['tgl_akhir']) : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	
	
	if($tgl_awal != '0000-00-00' && $tgl_akhir != '0000-00-00' ){
		$ft = " AND DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tgl_awal' AS DATE) AND CAST('$tgl_akhir' AS DATE) ";  	
	} else {
		$ft = "";
	}
	
	if($ta!="") $a = " AND ks.ta = '$ta'"; else $a = "";
	
	if($id_sub!="") $b = " AND ks.uuid_skpd = '$id_sub'";
	else $b = " AND MD5(ks.uuid_skpd) = '$_SESSION[uidunit]'";	
	if($_SESSION['peran_id']==MD5('1')) $b .= "";
	else{
		if($_SESSION['level']==MD5('a')) $b .= " AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==MD5('b')) $b .= "  AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub)) = '$_SESSION[peserta]'";
	}
	
	if($id_sumber!="") $c = " AND id_sumber_dana = '$id_sumber'";
	else $c = "";
	
	$where = " $a $b $c";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT *, jml_in AS jml_masuk, harga AS harga_masuk, nm_sub2_unit AS unit, no_pembayaran AS no_dpa FROM kartu_stok ks 
				LEFT JOIN masuk_detail md ON ks.id_transaksi_detail = md.id_masuk_detail
				LEFT JOIN ref_sub2_unit u ON ks.uuid_skpd = u.uuid_sub2_unit
				LEFT JOIN masuk m ON md.id_masuk = m.id_masuk
				LEFT JOIN ref_barang br ON ks.id_barang = br.id_barang
				WHERE ks.soft_delete = 0 AND ks.kode = 'i'  $where $ft ORDER BY tgl_transaksi ASC
	";
	
	// $clause = "SELECT IF(nama_barang_kegiatan IS NULL, nama_barang, nama_barang_kegiatan) AS nama_barang, jml_masuk, harga_masuk, nm_sub2_unit AS unit, 
	// 			tgl_pengadaan, no_kontrak, 
	// 			tgl_pembayaran, no_pembayaran AS no_dpa
	// 			FROM masuk_detail d
	// 			LEFT JOIN ref_sub2_unit u ON d.uuid_skpd = u.uuid_sub2_unit
	// 			LEFT JOIN masuk m ON m.id_masuk = d.id_masuk
	// 			LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
	// 			LEFT JOIN ref_barang_kegiatan bk ON d.id_barang = bk.id_barang_kegiatan 
	// 			WHERE d.soft_delete=0 $where $ft ORDER BY tgl_pengadaan ASC";
			
	// echo $clause;
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $ttotal = 0; $footer = array();
	while($row = mysql_fetch_assoc($rs)){
		$harga = $row['harga_masuk'] * $row['jml_masuk'];
		$row['tgl_kontrak'] = balikTanggalIndo($row['tgl_pengadaan']);
		$row['tgl_dpa'] = balikTanggalIndo($row['tgl_pembayaran']);
		$row['jml_barang'] = number_format($row['jml_masuk'], 2, ',', '.')." ";
		$row['hrg_barang'] = number_format($row['harga_masuk'], 2, ',', '.');
		$row['tot_harga'] = number_format($harga, 2, ',', '.');
		
		$ttotal += $harga;
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	 
	$foot['jml_barang'] = 'TOTAL'; 
	$foot['hrg_barang'] = number_format($ttotal, 2, ',', '.'); 
	array_push($footer, $foot);
	$result["footer"] = $footer;
	
	echo json_encode($result);
	mysql_close();
?>