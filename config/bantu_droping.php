<?php
	session_start();
	require_once "db.koneksi.php";
	require_once "db.function.php";
	require_once "library.php";
	
	$tgl = date("Y-m-d H:i:s");
	
/* 	$uuid_skpd = 'cfa563dd-5543-11e6-a2df-000476f4fa98';
	$no=1;
	echo "<table cellpadding='4'><tr><th>Unit</th><th>Barang</th><th>Sumber</th><th>Kode</th><th>Mutasi</th><th>Penerimaan</th></tr>";
	$q = mysql_query("SELECT * FROM kartu_stok t1 LEFT JOIN terima_keluar_detail t2 ON t1.id_transaksi_detail = t2.id_terima_keluar_detail AND t1.uuid_skpd = t2.uuid_skpd AND t1.id_barang = t2.id_barang WHERE t1.kode = 'r' AND t1.soft_delete = '0' AND t1.harga <> t2.harga_barang ");
	while($r = mysql_fetch_assoc($q)){
		$brg = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_barang WHERE id_barang = '$r[id_barang]' "));
		$sd = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber = '$r[id_sumber_dana]' "));
		$opd = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$r[uuid_skpd]' "));
		
		
		
		
		if($r["uuid_skpd"] != "5747e05f-abab-11ea-8b6f-aeec9527008b"){
			
			echo "<tr><td>".$opd["nm_sub2_unit"]."</td><td>".$brg["nama_barang"]."</td><td>".$sd["nama_sumber"]."</td><td>".$r["kode"]."</td><td>".$r["harga"]."</td><td>".$r["harga_barang"]."</td></tr>";
			
			// $upd = mysql_query("UPDATE terima_keluar_detail SET harga_barang = '$r[harga]', update_date = '$tgl', creator_id = '$r[harga_barang]' WHERE id_terima_keluar_detail = '$r[id_transaksi_detail]' AND uuid_skpd = '$r[uuid_skpd]' AND id_barang = '$r[id_barang]' ");
			
		}
		
		
	} */
	
	/* echo "<table cellpadding='4'><tr><th>Unit</th><th>Barang</th><th>Sumber</th><th>Kode</th><th>Mutasi</th><th>Buku Keluar</th></tr>";
	$q = mysql_query("SELECT * FROM kartu_stok t1 LEFT JOIN keluar_detail t2 ON t1.id_transaksi_detail = t2.id_keluar_detail WHERE t1.kode = 'os' AND t1.soft_delete = 0 AND t1.uuid_skpd = '91279abe-af57-11e9-b5e2-0e97cb36aab5' ");
	while($r = mysql_fetch_assoc($q)){
		$brg = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_barang WHERE id_barang = '$r[id_barang]' "));
		$sd = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber = '$r[id_sumber_dana]' "));
		$opd = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$r[uuid_skpd]' "));
		
		
		if($r["harga"] <> $r["harga_barang"]){
			echo "<tr><td>".$opd["nm_sub2_unit"]."</td><td>".$brg["nama_barang"]."</td><td>".$sd["nama_sumber"]."</td><td>".$r["kode"]."</td><td>".$r["harga"]."</td><td>".$r["harga_barang"]."</td></tr>";
			
			// $upd = mysql_query("UPDATE keluar_detail SET harga_barang = '$r[harga]', update_date = '$tgl', creator_id = '$r[harga_barang]' WHERE id_keluar_detail = '$r[id_transaksi_detail]' AND uuid_skpd = '$r[uuid_skpd]' AND id_barang = '$r[id_barang]' ");
		}
		
		
	} */
	
	$dari = '91279abe-af57-11e9-b5e2-0e97cb36aab5'; 
	$tglawal = '01-01-2020';
	$tglakhir = '31-12-2020';
	
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	 $d1 = $d2 = "";
	
	$clause = " 
				SELECT * FROM kartu_stok t1 LEFT JOIN keluar t2 ON t1.id_transaksi = t2.id_keluar WHERE t1.soft_delete = 0 AND t1.uuid_skpd = '$dari' AND t1.kode = 'os' AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' GROUP BY t1.id_barang, t1.harga  ORDER BY t2.uuid_untuk, t1.id_barang
				";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r; 
	$items = array(); $footer = array(); $ttotal = 0;
	while($row = mysql_fetch_assoc($rs)){
		$sumber = mysql_fetch_assoc(mysql_query("SELECT no_ba_out,SUM(jml_out) AS jml1, harga FROM kartu_stok t1 LEFT JOIN keluar t2 ON t1.id_transaksi = t2.id_keluar WHERE t2.uuid_untuk = '$row[uuid_untuk]' AND t1.soft_delete = 0 AND t1.uuid_skpd = '$dari' AND t1.kode = 'os' $d2 AND id_barang = '$row[id_barang]' AND harga = '$row[harga]'"));
		
		$brg = mysql_fetch_assoc(mysql_query("SELECT nama_barang,id_satuan FROM ref_barang WHERE id_barang = '$row[id_barang]' "));
		$sat = mysql_fetch_assoc(mysql_query("SELECT simbol FROM ref_satuan WHERE id_satuan = '$brg[id_satuan]' "));
		
		$opd = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$row[uuid_untuk]' "));
		$sd = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber = '$row[id_sumber_dana]' "));
		
		// $sumber = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out) AS jml1, harga FROM kartu_stok WHERE soft_delete = 0 AND uuid_skpd = '$dari' AND id_barang = '$row[id_barang]' AND kode = 'os' AND id_sumber_dana = '$id_sum' AND harga = '$row[harga]'  "));
		
		
		$salur = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in) AS jml2, harga, id_stok FROM kartu_stok WHERE soft_delete = 0 AND uuid_skpd = '$row[uuid_untuk]' AND id_barang = '$row[id_barang]' AND kode = 'r' $d1 AND harga = '$row[harga]' "));
		
		$row["selisih"] = $sumber["jml1"]-$salur["jml2"];
		if($row["selisih"] <> 0){
			echo $sumber["no_ba_out"]." || ".$row["tgl_transaksi"]." || ".$sd["nama_sumber"]." || ".$opd["nm_sub2_unit"]." || ".$brg["nama_barang"]." || KIRIM: ".$sumber["jml1"]." || TERIMA: ".$salur["jml2"]." || HARGA: ".$row["harga"]."<br>";
		// echo "<br>"."SELECT SUM(jml_in) AS jml2, harga, id_stok FROM kartu_stok WHERE soft_delete = 0 AND uuid_skpd = '$row[uuid_untuk]' AND id_barang = '$row[id_barang]' AND kode = 'r' $d1 AND harga = '$row[harga]' "."<br>";
		}
		
		/* $row["id_stok"] = $salur["id_stok"];
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
		// array_push($items, $row);
		} */
		
	}
	
	
	
	
?>