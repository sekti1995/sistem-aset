<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$id_sub = isset($_REQUEST['id_sub']) ? $_REQUEST['id_sub'] : '';
	$untuk = isset($_REQUEST['untuk']) ? $_REQUEST['untuk'] : '';
	$id_sumber = isset($_REQUEST['id_sumber']) ? $_REQUEST['id_sumber'] : '';
	$ta = isset($_REQUEST['ta']) ? $_REQUEST['ta'] : date('Y');
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	
	
	if($ta!="") $a = " AND t1.ta = '$ta'"; else $a = "";
	if($id_sub!="") $b = " AND t1.uuid_skpd = '$id_sub'";
	else $b = " AND MD5(t1.uuid_skpd) = '$_SESSION[uidunit]'";	
	if($_SESSION['peran_id']==MD5('1')) $b .= "";
	else{
		if($_SESSION['level']==MD5('a')) $b .= " AND MD5(CONCAT_WS('.', g.kd_urusan, g.kd_bidang, g.kd_unit)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==MD5('b')) $b .= "  AND MD5(CONCAT_WS('.', g.kd_urusan, g.kd_bidang, g.kd_unit, g.kd_sub)) = '$_SESSION[peserta]'";
	}	
	if($tglawal!="" AND $tglawal!="00") $c = " AND DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tglawal' AS DATE) AND CAST('$tglakhir' AS DATE)"; else $c = "";
	if($id_sumber!="") $d = "AND t1.id_sumber_dana = '$id_sumber'";
	else $d = "";
	
	
	if($untuk!="") $e = "AND t2.uuid_untuk = '$untuk'";
	else $e = "";
	
	$where = "$a $b $c $d $e";
	$result = array();
	$footer = array();
	$clause = "SELECT IFNULL(nama_barang_kegiatan,nama_barang) nama_barang, jml_barang, harga_barang, 
				IF(jenis_out='s', u.nm_sub2_unit, peruntukan) AS untuk, tgl_ba_out, no_ba_out AS nomor, 
				tgl_terima, uuid_untuk AS idsubt, d.keterangan AS ket
				FROM keluar_detail d
				LEFT JOIN keluar k ON k.id_keluar = d.id_keluar
				LEFT JOIN ref_sub2_unit u ON k.uuid_untuk = u.uuid_sub2_unit
				LEFT JOIN ref_sub2_unit g ON k.uuid_skpd = g.uuid_sub2_unit
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				LEFT JOIN ref_barang_kegiatan bk ON d.id_barang = bk.id_barang_kegiatan
				WHERE d.soft_delete=0 $where
				ORDER BY tgl_terima, nama_barang";
				
	$clause = "SELECT  tgl_transaksi, id_stok, id_barang, id_transaksi, id_transaksi_detail, kode, jml_out AS jml_barang, harga AS harga_barang FROM kartu_stok t1 LEFT JOIN keluar t2 ON t1.id_transaksi = t2.id_keluar LEFT JOIN ref_sub2_unit g ON t1.uuid_skpd = g.uuid_sub2_unit WHERE t1.soft_delete = 0 AND (kode = 'ok' OR kode = 'os') AND jml_out > 0 $where ";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array();
	$tot_bar = 0;
	$tot_har = 0;
	while($row = mysql_fetch_assoc($rs)){
		$hargatot = $row['harga_barang'] * $row['jml_barang'];
		/* $nm = mysql_fetch_assoc(mysql_query("SELECT nm_sub_unit FROM ref_sub_unit WHERE CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit, kd_sub) = '$row[idsubt]'"));
		$row['untuk'] = $nm['nm_sub_unit']; */
		//$row['untuk'] = $row['unit'];
		$barang = mysql_fetch_assoc(mysql_query("SELECT nama_barang FROM ref_barang WHERE id_barang = '$row[id_barang]' "));
		$row["nama_barang"] = $barang["nama_barang"];
		
		if($row["kode"] == "ok"){
			$dt = mysql_fetch_assoc(mysql_query("SELECT * FROM keluar t2 LEFT JOIN ref_sub2_unit t3 ON t2.uuid_skpd = t3.uuid_sub2_unit
															WHERE id_keluar = '$row[id_transaksi]' "));
		
			$row["nomor"] = $dt["no_ba_out"];
			$row["untuk"] = $dt["peruntukan"];
			// $row["tgl_terima"] = $dt["tgl_transaksi"];
			$row["tgl_ba_out"] = $dt["tgl_ba_out"];
		}
		else if($row["kode"] == "os"){
			$dt = mysql_fetch_assoc(mysql_query("SELECT * FROM keluar t2 LEFT JOIN ref_sub2_unit t3 ON t2.uuid_untuk = t3.uuid_sub2_unit
															WHERE id_keluar = '$row[id_transaksi]' "));
		
			$row["nomor"] = $dt["no_ba_out"];
			$row["untuk"] = $dt["nm_sub2_unit"];
			// $row["tgl_terima"] = $dt["tgl_transaksi"];
			$row["tgl_ba_out"] = $dt["tgl_ba_out"];
		}
		
		// $row["untuk"] = $row["id_stok"];
		
		
		$tot_bar += $row['jml_barang'];
		$tot_har += $hargatot;
		
		
		$row['tgl_keluar'] = balikTanggalIndo($row['tgl_ba_out']);
		$row['tgl_serah'] = balikTanggalIndo($row['tgl_transaksi']);
		$row['jml_barang'] = number_format($row['jml_barang'], 6, ',', '.') ;
		$row['hrg_barang'] = number_format($row['harga_barang'], 6, ',', '.');
		$row['tot_harga'] = number_format($hargatot, 6, ',', '.');
		
		
		$ex4 = explode(",", $row['hrg_barang']);
		if($ex4[1] > 0){
			$row['hrg_barang'] = $ex4[0].",".$ex4[1];
		} else {
			$row['hrg_barang'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['jml_barang']);
		if($ex4[1] > 0){
			$row['jml_barang'] = $ex4[0].",".$ex4[1];
		} else {
			$row['jml_barang'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['tot_harga']);
		if($ex4[1] > 0){
			$row['tot_harga'] = $ex4[0].",".$ex4[1];
		} else {
			$row['tot_harga'] = $ex4[0];
		}

		array_push($items, $row);
	}
	$tot_bar = number_format($tot_bar, 6, ',', '.');
	$tot_har = number_format($tot_har, 6, ',', '.');
	
	$ex4 = explode(",", $tot_bar);
	if($ex4[1] > 0){
		$tot_bar = $ex4[0].",".$ex4[1];
	} else {
		$tot_bar = $ex4[0];
	}
	
	$ex4 = explode(",", $tot_har);
	if($ex4[1] > 0){
		$tot_har = $ex4[0].",".$ex4[1];
	} else {
		$tot_har = $ex4[0];
	}
	
	$foot["jml_barang"] = $tot_bar;
	$foot["tot_harga"] = $tot_har;
	
	$result["rows"] = $items;
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>