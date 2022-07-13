<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$bln = isset($_POST['bln']) ? $_POST['bln'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	
	if($ta=="") $ta = date('Y');
	if($peran==md5('1')){
		$kdsub = "AND (kd_sub = '1' OR uuid_skpd = 'cfa58008-5543-11e6-a2df-000476f4fa98')";
	}else $kdsub = "AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	
	if($id_sum!=""){
		$idsum = "AND id_sumber_dana = '$id_sum'";
	}else{
		$idsum = "";
	}
	
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
	
	$clause = "SELECT uuid_skpd, nm_sub2_unit AS nama_skpd, kd_urusan, kd_bidang, kd_unit FROM kartu_stok
				LEFT JOIN ref_sub2_unit ON uuid_sub2_unit = uuid_skpd
				WHERE soft_delete = 0 
				GROUP BY uuid_skpd
				ORDER BY nm_sub2_unit ASC";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $totalPers = 0; $footer = array(); $no=1; $masuk = 0; $keluar = 0;
	while($row = mysql_fetch_assoc($rs)){
		
		$row["kode_skpd"] = $row["kd_urusan"].".".$row["kd_bidang"].".".$row["kd_unit"];
		
		$ids = "AND uuid_skpd = '$row[uuid_skpd]'";
		$nilaiPers = 0;
		
		$saldo = mysql_query("SELECT SUM(jml_in-jml_out) AS saldo, harga, id_barang, uuid_skpd FROM kartu_stok 
											WHERE DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tglawal' AS DATE) AND CAST('$tglakhir' AS DATE)
											AND soft_delete = 0 $ids $idsum GROUP BY id_barang, harga");
											
		while($s = mysql_fetch_assoc($saldo)){
			
			$nilaiBar = $s['saldo']*$s['harga'];		
			$nilaiPers += $nilaiBar;
		}
		
		
		$nilainya = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in*harga) AS pengadaan, SUM(jml_out*harga) AS pengeluaran FROM kartu_stok 
											WHERE DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tglawal' AS DATE) AND CAST('$tglakhir' AS DATE)
											AND soft_delete = 0 AND uuid_skpd = '$row[uuid_skpd]' $idsum "));
		
		$masuk = $nilainya['pengadaan'];
		$keluar = $nilainya['pengeluaran'];
		$sisa = $masuk-$keluar;
		
		
		
		// ========================================================================================
		
		
		// $nilainya2 = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in*harga) AS pengadaan, SUM(jml_out*harga) AS pengeluaran FROM kartu_stok 
											// WHERE DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tglawal' AS DATE) AND CAST('$tglakhir' AS DATE) AND soft_delete = 0 AND uuid_skpd = '$row[uuid_skpd]' $idsum "));
		
		// $masuk2 = $nilainya2['pengadaan'];
		// $keluar2 = $nilainya2['pengeluaran'];
		
		// ========================================================================================
		
		
		
		
		
		

		$no++;
		$totalPers += $nilaiPers;
		
		
		$saldo_awal = mysql_fetch_assoc(mysql_query("SELECT (SUM(jml_in*harga)-SUM(jml_out*harga)) AS saldo, harga, id_barang, uuid_skpd FROM kartu_stok 
											WHERE YEAR(tgl_transaksi) < '$ta' AND soft_delete = 0 AND uuid_skpd = '$row[uuid_skpd]' $idsum "));
		$sisa = $sisa+$saldo_awal['saldo'];
		$row['saldo_awal'] = number_format($saldo_awal['saldo'], 0, ',', '.');
		$row['nilai'] = number_format($nilaiPers, 0, ',', '.');
		$row['masuk'] = number_format($masuk, 0, ',', '.');
		$row['keluar'] = number_format($keluar, 0, ',', '.');
		$row['sisa'] = number_format($sisa, 0, ',', '.');
		
		// $row['masuk2'] = number_format($masuk2, 0, ',', '.');
		// $row['keluar2'] = number_format($keluar2, 0, ',', '.');
		if($row['nama_skpd'] == ""){
		
		} else {
		array_push($items, $row);
		}
	}
	$result["rows"] = $items;
	$foot['nama_skpd'] = "Total";
	$foot['sisa'] = number_format($totalPers, 0, ',', '.');
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>