<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$bln = isset($_POST['bln']) ? str_pad($_REQUEST['bln'],2,'0', STR_PAD_LEFT) : str_pad(date('m'),2,'0', STR_PAD_LEFT);
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	
	if($id_sub!=""){
		$ids = "AND k.uuid_skpd = '$id_sub'";
		$idsub = "AND uuid_skpd = '$id_sub'";
	}else{
		$ids = "AND MD5(k.uuid_skpd) = '$_SESSION[uidunit]'"; 
		$idsub = "AND MD5(uuid_skpd) = '$_SESSION[uidunit]'"; 
	}
	if($id_sum!=""){
		$idsum = "AND id_sumber_dana = '$id_sum'";
	}else{
		$idsum = "";
	}
	$thnbln = $ta."-".$bln;
	
	$result = array();
	
	$clause = "SELECT k.id_barang, IFNULL(nama_barang_kegiatan, nama_barang) nama_barang, IF(ISNULL(bk.id_barang_kegiatan), 'a', 'b') stat,
				CONCAT_WS('.', j.kd_kel, j.kd_sub) AS kode_bar, nama_jenis AS jenis
				FROM kartu_stok k 
				LEFT JOIN ref_barang b ON b.id_barang = k.id_barang
				LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang
				LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis
				WHERE k.soft_delete = 0 $ids $idsum
				GROUP BY k.id_barang ORDER BY stat, j.kd_kel, j.kd_sub, b.kd_sub2";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r; 
	$items = array(); $footer = array(); $ttotal = 0;
	while($row = mysql_fetch_assoc($rs)){
		$awal = mysql_fetch_row(mysql_query("SELECT SUM(jml_in-jml_out) FROM kartu_stok 
											WHERE id_barang = '$row[id_barang]' AND DATE_FORMAT(tgl_transaksi, '%Y-%m') < '$thnbln'
											AND soft_delete = 0 $idsub $idsum"));
		$ini = mysql_fetch_row(mysql_query("SELECT SUM(jml_in), SUM(jml_out) FROM kartu_stok 
											WHERE id_barang = '$row[id_barang]' AND DATE_FORMAT(tgl_transaksi, '%Y-%m') = '$thnbln'
											AND soft_delete = 0 AND kode<>'m' $idsub $idsum"));
		$harga = mysql_fetch_row(mysql_query("SELECT harga FROM kartu_stok 
											WHERE id_barang = '$row[id_barang]' AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnbln'
											AND soft_delete = 0 $idsub $idsum AND jml_in <> 0 AND kode<>'m' 
											ORDER BY tgl_transaksi DESC, create_date DESC LIMIT 1"));
		
		$saldo_akhir = $awal[0] + $ini[0] - $ini[1];	
		$total = $saldo_akhir * $harga[0];
		$ttotal += $total;
		$row['saldo_awal'] = number_format($awal[0], 0, ',', '.');
		$row['jml_masuk'] = number_format($ini[0], 0, ',', '.');
		$row['jml_keluar'] = number_format($ini[1], 0, ',', '.');
		$row['saldo_akhir'] = number_format($saldo_akhir, 0, ',', '.');
		$row['harga'] = number_format($harga[0], 0, ',', '.');
		$row['total'] = number_format($total, 0, ',', '.');
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	
	$foot['harga'] = 'Total';
	$foot['total'] = number_format($ttotal, 0, ',', '.');
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>