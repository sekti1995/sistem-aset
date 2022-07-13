<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$bln = isset($_POST['bln']) ? $_POST['bln'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
	
	
	if($id_sub!=""){
		$wh = "WHERE uuid_sub2_unit = '$id_sub'";
		$s = "AND uuid_skpd = '$id_sub'";
	}else{
		$wh = "WHERE MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$s = "AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	}
	$kd = mysql_fetch_row(mysql_query("SELECT CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit) FROM ref_sub2_unit $wh"));
	$sel = "(SELECT uuid_sub2_unit FROM ref_sub2_unit WHERE CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit) = '$kd[0]')";
	$ids = "AND k.uuid_skpd IN $sel";
	$idsub = "AND uuid_skpd IN $sel";
	
	$kode = $kd[0];
	
	if($id_sum!=""){
		$idsum = "AND id_sumber_dana = '$id_sum'";
	}else{
		$idsum = "";
	}
	if($ta=="") $ta = date('Y');
	
	if($bln!=""){
		$bln = str_pad($bln,2,'0', STR_PAD_LEFT);
		$thnblnawal = $thnblnharga = $ta."-".$bln; 
	}else{
		if($smstr!=""){
			if($smstr==1){ $thnblnawal = $ta."-01"; $thnblnharga = $ta."-06"; }
			elseif($smstr==2){ $thnblnawal = $ta."-07"; $thnblnharga = $ta."-12"; }
		}else{ 
			$thnblnawal = $ta."-01"; $thnblnharga = $ta."-12";
		}
	}
	
	$result = array();
	
	$clause = "SELECT k.id_barang, IFNULL(nama_barang_kegiatan, nama_barang) nama, IF(ISNULL(bk.id_barang_kegiatan), 'a', 'b') stat
				FROM kartu_stok k 
				LEFT JOIN ref_barang b ON b.id_barang = k.id_barang
				LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang
				LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis
				WHERE k.soft_delete = 0 $ids $idsum 
				GROUP BY k.id_barang ORDER BY stat, j.kd_kel, j.kd_sub, b.kd_sub2";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $ttotal = 0; $footer = array();
	while($row = mysql_fetch_assoc($rs)){
		$nilaiRekap = 0;
		$awal = mysql_fetch_row(mysql_query("SELECT SUM(jml_in-jml_out) FROM kartu_stok 
											WHERE id_barang = '$row[id_barang]'
											AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
											AND soft_delete = 0 $idsub $idsum"));
		$nilai = mysql_query("SELECT SUM(jml_in-jml_out) AS jml, harga FROM kartu_stok
							WHERE id_barang = '$row[id_barang]'
							AND soft_delete = 0 $idsub $idsum
							AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
							GROUP BY harga");
		while($n = mysql_fetch_assoc($nilai)){
			$nilaiHar = $n['jml']*$n['harga'];
			$nilaiRekap += $nilaiHar;
		}
		
		$ttotal += $nilaiRekap;
		$row['saldo'] = number_format($awal[0], 0, ',', '.');
		$row['nilai'] = number_format($nilaiRekap, 0, ',', '.');
		array_push($items, $row);
	}
	$result["rows"] = $items;
	$foot['saldo'] = "Total";
	$foot['nilai'] = number_format($ttotal, 0, ',', '.');
	$foot['kode'] = $kode;
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>