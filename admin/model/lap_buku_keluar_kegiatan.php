<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$id_sub = isset($_REQUEST['id_sub']) ? $_REQUEST['id_sub'] : '';
	$id_sumber = isset($_REQUEST['id_sumber']) ? $_REQUEST['id_sumber'] : '';
	$ta = isset($_REQUEST['ta']) ? $_REQUEST['ta'] : date('Y');
	$bln = isset($_REQUEST['bln']) ? str_pad($_REQUEST['bln'],2,'0', STR_PAD_LEFT) : str_pad(date('m'),2,'0', STR_PAD_LEFT);
	
	
	if($ta!="") $a = " AND d.ta = '$ta'"; else $a = "";
	if($id_sub!="") $b = " AND d.uuid_skpd = '$id_sub'";
	else $b = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'";	
	if($_SESSION['peran_id']==MD5('1')) $b .= "";
	else{
		if($_SESSION['level']==MD5('a')) $b .= " AND MD5(CONCAT_WS('.', g.kd_urusan, g.kd_bidang, g.kd_unit)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==MD5('b')) $b .= "  AND MD5(CONCAT_WS('.', g.kd_urusan, g.kd_bidang, g.kd_unit, g.kd_sub)) = '$_SESSION[peserta]'";
	}	
	if($bln!="" AND $bln!="00") $c = " AND DATE_FORMAT(tgl_ba_out, '%m') = '$bln'"; else $c = "";
	if($id_sumber!="") $d = "AND d.id_sumber_dana = '$id_sumber'";
	else $d = "";
	
	$where = "$a $b $c $d";
	$result = array();
	$clause = "SELECT nama_barang_kegiatan nama_barang, jml_barang, harga_barang, 
				IF(jenis_out='s', u.nm_sub2_unit, peruntukan) AS untuk, tgl_ba_out, no_ba_out AS nomor, 
				tgl_terima, uuid_untuk AS idsubt, d.keterangan AS ket, s.simbol, md.id_masuk_detail
				FROM keluar_detail d
				LEFT JOIN keluar k ON k.id_keluar = d.id_keluar
				LEFT JOIN ref_sub2_unit u ON k.uuid_untuk = u.uuid_sub2_unit
				LEFT JOIN ref_sub2_unit g ON k.uuid_skpd = g.uuid_sub2_unit 
				LEFT JOIN ref_barang_kegiatan bk ON d.id_barang = bk.id_barang_kegiatan
				LEFT JOIN ref_satuan s ON s.id_satuan = bk.id_satuan
				LEFT JOIN masuk_detail md ON md.id_barang = d.id_barang
				WHERE d.soft_delete=0 $where";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$hargatot = $row['harga_barang'] * $row['jml_barang'];
		/* $nm = mysql_fetch_assoc(mysql_query("SELECT nm_sub_unit FROM ref_sub_unit WHERE CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit, kd_sub) = '$row[idsubt]'"));
		$row['untuk'] = $nm['nm_sub_unit']; */
		//$row['untuk'] = $row['unit'];
		$row['tgl_keluar'] = balikTanggalIndo($row['tgl_ba_out']);
		$row['tgl_serah'] = balikTanggalIndo($row['tgl_terima']);
		$row['jml_barang'] = number_format($row['jml_barang'], 0, ',', '.')." ";
		$row['hrg_barang'] = number_format($row['harga_barang'], 0, ',', '.');
		$row['tot_harga'] = number_format($hargatot, 0, ',', '.');
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>