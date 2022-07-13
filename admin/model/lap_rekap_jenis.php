<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	error_reporting(E_ALL); ini_set('display_errors', 'on'); 
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
	$akses = isset($_POST['akses']) ? $_POST['akses'] : '';
	
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	$kode = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_sub' "));

	$tl = $ta;
	$tl = $tl-1;
	$talalu = $tl."-12-31";

	if($akses == 2){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]' ";
				
	} else if($akses == 3){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]'
				AND t4.kd_sub = '$kode[kd_sub]' ";
				
	} else if($akses == 4){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]'
				AND t4.kd_sub = '$kode[kd_sub]'
				AND t4.kd_sub2 = '$kode[kd_sub2]' ";
	} else if($akses == 5){
		$e1 = "	AND t1.uuid_skpd = '$id_sub' ";
	} else {
		$id_sub = $_SESSION["uidunit_plain"];
		$e1 = "	AND t1.uuid_skpd = '$_SESSION[uidunit_plain]' ";
	}
	
	if($id_sub!=""){
		$wh = "WHERE uuid_sub2_unit = '$id_sub'";
		$sub = "AND uuid_skpd = '$id_sub'";
	}else{
		$wh = "WHERE MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$sub = "AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	}

	if($id_sum!=""){
		$idsum = "AND id_sumber_dana = '$id_sum'";
	}else{
		$idsum = "";
	}
	if($ta=="") $ta = date('Y');
	
	$thnblnharga = "";
	$bl = " AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
	$bl_lalu = " AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') < '$tglawal' ";
	
	$result = array();
	if($id_sub!=""){
	
		$clause = "SELECT id_jenis, nama_jenis AS nama
					FROM ref_jenis j 
					WHERE kd_sub <> 0
					ORDER BY j.kd_kel, j.kd_sub";
	
	} else {
	
		$clause = " ";
	}

	$rs = mysql_query($clause);
	if($rs === FALSE) { 
		$r = 0;
	}else{
	$r = mysql_num_rows($rs);}
	$result["total"] = $r;
	$items = array(); $ttotal = 0; $footer = array();
	
	$t1 = 0;
	$t2 = 0;
	$t3 = 0;
	$t4 = 0;
	$t5 = 0;
	$t6 = 0;
	if($rs === FALSE) {
		$row = 0;
	}else{
	while($row = mysql_fetch_assoc($rs)){
		
			$in_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
						FROM kartu_stok t1 LEFT JOIN ref_barang t2
						ON t1.id_barang = t2.id_barang
						LEFT JOIN ref_jenis t3
						ON t2.id_jenis = t3.id_jenis
						LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
					WHERE 
						t3.id_jenis = '$row[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl_lalu"));
						
			
			$out_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
						FROM kartu_stok t1 LEFT JOIN ref_barang t2
						ON t1.id_barang = t2.id_barang
						LEFT JOIN ref_jenis t3
						ON t2.id_jenis = t3.id_jenis
						LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
					WHERE 
						t3.id_jenis = '$row[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0'  $e1 $bl_lalu"));
		
		$sisa_lalu = $in_lalu["ttl"]-$out_lalu["ttl"] ;
		$row["saldo_awal"] = $sisa_lalu;
		
			$in = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
						FROM kartu_stok t1 LEFT JOIN ref_barang t2
						ON t1.id_barang = t2.id_barang
						LEFT JOIN ref_jenis t3
						ON t2.id_jenis = t3.id_jenis
						LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
					WHERE 
						t3.id_jenis = '$row[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl"));
		
			$out = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
						FROM kartu_stok t1 LEFT JOIN ref_barang t2
						ON t1.id_barang = t2.id_barang
						LEFT JOIN ref_jenis t3
						ON t2.id_jenis = t3.id_jenis
						LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
					WHERE 
						t3.id_jenis = '$row[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0' $e1 $bl"));
		
		
		$jml_masuk = $in["ttl"]+$sisa_lalu;
		
		$sisa = $sisa_lalu+$in["ttl"]-$out["ttl"];
		$row["masuk"] = $in["ttl"];
		$row["keluar"] = $out["ttl"];
		$row["saldo_akhir"] = $sisa;
		
		$sel = "(SELECT id_barang FROM ref_barang WHERE id_jenis = '$row[id_jenis]')";
		$sel1 = "(SELECT id_barang_kegiatan FROM ref_barang_kegiatan WHERE id_jenis = '$row[id_jenis]')";
		$ids = "AND ( id_barang IN $sel OR id_barang IN ($sel1) )";
		
		$nilaiPers = 0; $saldoKom = 0;
		
		$saldo = mysql_query("SELECT SUM(jml_in-jml_out) AS saldo, harga, id_barang, uuid_skpd FROM kartu_stok 
											WHERE DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
											AND soft_delete = 0 $ids $sub $idsum GROUP BY id_barang, uuid_skpd, harga");
											
		while($s = mysql_fetch_assoc($saldo)){
			$nilaiBar = $s['saldo']*$s['harga'];
			$saldoKom += $s['saldo'];
			$nilaiPers += $nilaiBar;
		}
		
		$t1 += $row["masuk"];
		$t2 += $row["keluar"];
		$t3 += $row["saldo_awal"];
		$t4 += $row["saldo_akhir"];
		$t5 += $jml_masuk;
		
		$row['saldo'] = number_format($saldoKom, 15, ',', '.');
		$row['nilai'] = number_format($nilaiPers, 15, ',', '.');
		$row['masuk'] = number_format($row["masuk"], 15, ',', '.');
		$row['keluar'] = number_format($row["keluar"], 15, ',', '.');
		$row['saldo_awal'] = number_format($row["saldo_awal"], 15, ',', '.');
		$row['saldo_akhir'] = number_format($row["saldo_akhir"], 15, ',', '.');
		$row['jml_masuk'] = number_format($jml_masuk, 15, ',', '.');
		
		$ex1 = explode(",", $row['saldo']);
		if($ex1[1] > 0){
			$row['saldo'] = $ex1[0].",".$ex1[1];
		} else {
			$row['saldo'] = $ex1[0];
		}
		
		$ex1 = explode(",", $row['nilai']);
		if($ex1[1] > 0){
			$row['nilai'] = $ex1[0].",".$ex1[1];
		} else {
			$row['nilai'] = $ex1[0];
		}
		
		$ex1 = explode(",", $row['masuk']);
		if($ex1[1] > 0){
			$row['masuk'] = $ex1[0].",".$ex1[1];
		} else {
			$row['masuk'] = $ex1[0];
		}
		
		$ex1 = explode(",", $row['keluar']);
		if($ex1[1] > 0){
			$row['keluar'] = $ex1[0].",".$ex1[1];
		} else {
			$row['keluar'] = $ex1[0];
		}
		
		$ex1 = explode(",", $row['saldo_awal']);
		if($ex1[1] > 0){
			$row['saldo_awal'] = $ex1[0].",".$ex1[1];
		} else {
			$row['saldo_awal'] = $ex1[0];
		}
		
		$ex1 = explode(",", $row['saldo_akhir']);
		if($ex1[1] > 0){
			$row['saldo_akhir'] = $ex1[0].",".$ex1[1];
		} else {
			$row['saldo_akhir'] = $ex1[0];
		}
		
		$ex1 = explode(",", $row['jml_masuk']);
		if($ex1[1] > 0){
			$row['jml_masuk'] = $ex1[0].",".$ex1[1];
		} else {
			$row['jml_masuk'] = $ex1[0];
		}
		
		
		
		
		
		// if($saldoKom!=0){
			$ttotal += $nilaiPers;
			array_push($items, $row);
		// }	
	}}
	$result["rows"] = $items;
	$foot['nama'] = "TOTAL";
	$foot['saldo_awal'] = number_format($t3, 15, ',', '.');
	$foot['masuk'] = number_format($t1, 15, ',', '.');
	$foot['keluar'] = number_format($t2, 15, ',', '.');
	$foot['saldo_akhir'] = number_format($t4, 15, ',', '.');
	$foot['jml_masuk'] = number_format($t5, 15, ',', '.');
	
	
		
	$ex1 = explode(",", $foot['saldo_awal']);
	if($ex1[1] > 0){
		$foot['saldo_awal'] = $ex1[0].",".$ex1[1];
	} else {
		$foot['saldo_awal'] = $ex1[0];
	}
	$ex1 = explode(",", $foot['masuk']);
	if($ex1[1] > 0){
		$foot['masuk'] = $ex1[0].",".$ex1[1];
	} else {
		$foot['masuk'] = $ex1[0];
	}
	$ex1 = explode(",", $foot['keluar']);
	if($ex1[1] > 0){
		$foot['keluar'] = $ex1[0].",".$ex1[1];
	} else {
		$foot['keluar'] = $ex1[0];
	}
	$ex1 = explode(",", $foot['saldo_akhir']);
	if($ex1[1] > 0){
		$foot['saldo_akhir'] = $ex1[0].",".$ex1[1];
	} else {
		$foot['saldo_akhir'] = $ex1[0];
	}
	$ex1 = explode(",", $foot['jml_masuk']);
	if($ex1[1] > 0){
		$foot['jml_masuk'] = $ex1[0].",".$ex1[1];
	} else {
		$foot['jml_masuk'] = $ex1[0];
	}
	
	
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>