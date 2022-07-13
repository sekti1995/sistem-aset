<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$dari = isset($_POST['dari']) ? $_POST['dari'] : '';
	$ke = isset($_POST['ke']) ? $_POST['ke'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	
	if($id_sum!=""){
		$d1 = "AND id_sumber_dana = '$id_sum'";
		$d2 = "AND t1.id_sumber_dana = '$id_sum'";
	}else $d1 = $d2 = "";
	
	$result = array();
	
	// $clause = " 
				// SELECT * FROM kartu_stok t1 LEFT JOIN keluar t2 ON t1.id_transaksi = t2.id_keluar WHERE t2.uuid_untuk = '$ke' AND t1.soft_delete = 0 AND t1.uuid_skpd = '$dari' AND t1.kode = 'os' $d2 AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' GROUP BY t1.id_barang, t1.harga
				// ";
	
	$clause = " 
				SELECT * FROM kartu_stok t1 LEFT JOIN keluar t2 ON t1.id_transaksi = t2.id_keluar WHERE t1.soft_delete = 0 AND t1.uuid_skpd = '$dari' AND t1.kode = 'os' $d2 AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'  ORDER BY t2.uuid_untuk, t1.id_barang
				";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r; 
	$items = array(); $footer = array(); $ttotal = 0;
	while($row = mysql_fetch_assoc($rs)){
		$brg = mysql_fetch_assoc(mysql_query("SELECT nama_barang,id_satuan FROM ref_barang WHERE id_barang = '$row[id_barang]' "));
		$sat = mysql_fetch_assoc(mysql_query("SELECT simbol FROM ref_satuan WHERE id_satuan = '$brg[id_satuan]' "));
		
		$opd = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$row[uuid_untuk]' "));
		$sd = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber = '$row[id_sumber_dana]' "));
		
		// $sumber = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out) AS jml1, harga FROM kartu_stok WHERE soft_delete = 0 AND uuid_skpd = '$dari' AND id_barang = '$row[id_barang]' AND kode = 'os' AND id_sumber_dana = '$id_sum' AND harga = '$row[harga]'  "));
		
		// $sumber = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out) AS jml1, harga FROM kartu_stok t1 LEFT JOIN keluar t2 ON t1.id_transaksi = t2.id_keluar WHERE t2.uuid_untuk = '$ke' AND t1.soft_delete = 0 AND t1.uuid_skpd = '$dari' AND t1.kode = 'os' $d2 AND id_barang = '$row[id_barang]' AND harga = '$row[harga]'"));
		
		$salur = mysql_fetch_assoc(mysql_query("SELECT jml_in AS jml2, harga, id_stok FROM kartu_stok WHERE soft_delete = 0 AND uuid_skpd = '$row[uuid_untuk]' AND id_barang = '$row[id_barang]' AND kode = 'r' $d1 AND harga = '$row[harga]' AND jml_in = '$row[jml_out]' "));
		
		$row["id_stok"] = $salur["id_stok"];
		// $row["id_stok"] = $row["id_stok"];
		$row["sumber_dana"] = $sd["nama_sumber"];
		$row["penerima"] = $opd["nm_sub2_unit"];
		$row["nama_barang"] = $brg["nama_barang"];
		$row["sat1"] = $sat["simbol"];
		$row["sat2"] = $sat["simbol"];
		
		$row["jml1"] = $row["jml_out"];
		$row["harga1"] = $row["harga"];
		
		$row["jml2"] = $salur["jml2"];
		$row["harga2"] = $salur["harga"];
		
		$row["selisih"] = $row["jml1"]-$row["jml2"];
		if($row["selisih"] <> 0){
			$row["selisih"] = "<b style='color:#ef2222'>".$row["selisih"]."</b>";
			$row["nama_barang"] = "<b style='color:#ef2222'>".$brg["nama_barang"]."</b>";
			
			$row["sat1"] = "<b style='color:#ef2222'>".$row["sat1"]."</b>";
			$row["sat2"] = "<b style='color:#ef2222'>".$row["sat2"]."</b>";
			$row["jml1"] = "<b style='color:#ef2222'>".$row["jml1"]."</b>";
			$row["harga1"] = "<b style='color:#ef2222'>".$row["harga1"]."</b>";
			$row["jml2"] = "<b style='color:#ef2222'>".$row["jml2"]."</b>";
			$row["harga2"] = "<b style='color:#ef2222'>".$row["harga2"]."</b>";
		array_push($items, $row);
		}
		
	}
	$result["rows"] = $items;
	
	// $foot['harga'] = 'Total';
	// $foot['total'] = number_format($ttotal, 0, ',', '.');
	// array_push($footer, $foot);
	// $result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>