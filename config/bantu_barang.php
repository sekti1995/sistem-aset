<?php
	session_start();
	require_once "db.koneksi.php";

	$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
	$uuid = $u[0];
	$datime = date("Y-m-d");
	$tgl_awal = '2020-12-31';
	
	$q = mysql_query("SELECT *,  e AS harga , f AS jumlah FROM awal WHERE e > 0");
	while($r = mysql_fetch_assoc($q)){
		
		if(substr($r["a"],0,15) == "SKB Karanganyar" || substr($r["a"],0,34) == "Subbag Umum dan Kepegawaian Disdik"){
			$id_sumber = 28;
		} else {
			$id_sumber = 32;
		}
		
		$brg = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang WHERE REPLACE(LOWER('$r[b]'),' ','') = REPLACE(LOWER(nama_barang),' ','') "));
		$jns = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_jenis WHERE REPLACE(LOWER('$r[h]'),' ','') = REPLACE(LOWER(nama_jenis),' ','') "));
		$opd = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_sub2_unit WHERE REPLACE(LOWER('$r[a]'),' ','') = REPLACE(LOWER(nm_sub2_unit),' ','') "));
		$gud = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_gudang WHERE uuid_skpd = '$opd[uuid_sub2_unit]' "));
		
		if($brg["id_barang"] == ""){
			$brg = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang_2020 WHERE REPLACE(LOWER('$r[b]'),' ','') = REPLACE(LOWER(nama_barang),' ','') "));
			
			$ksub = mysql_fetch_assoc(mysql_query(" SELECT MAX(kd_sub2) AS jml FROM ref_barang WHERE id_jenis = '$jns[id_jenis]' "));
			$kd_sub2 = $ksub["jml"]+1;
		
			// $ins = mysql_query("INSERT INTO ref_barang(id_barang, id_jenis, kd_sub2, nama_barang, id_satuan, harga_index, keterangan, status, create_date, update_date, soft_delete, creator_id ) VALUES (UUID(), '$jns[id_jenis]', '$kd_sub2', '$brg[nama_barang]', '$brg[id_satuan]', '$brg[harga_index]', '$brg[keterangan]', '$brg[status]', NOW(), NOW(), 0, 'DEV 2')");
			
			echo "<b style='color:#ef2222'> ".$brg["id_barang"]." - ".$brg["nama_barang"]." - ".$jns["nama_jenis"]."</b><br>";
		} else {
			
			echo $brg["id_barang"]." - ".$brg["nama_barang"]." - ".$jns["id_jenis"]." - ".$jns["nama_jenis"]." - ".$opd["uuid_sub2_unit"]." - ".$opd["nm_sub2_unit"]." - ".$r["harga"]." - ".$r["jumlah"]."<br>";
			
			
					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuidet = $u[0];
					
					/* mysql_query("INSERT INTO adjust_detail (id_adjust_detail, id_adjust, uuid_skpd, id_barang, jumlah, harga, 
															create_date, creator_id)
													VALUES ('$uuidet', '$uuid', '$opd[uuid_sub2_unit]', '$brg[id_barang]', '$r[jumlah]', '$r[harga]',
															'$datime', 'DEV 2')");
					mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, id_sumber_dana,
														id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$opd[uuid_sub2_unit]', '$brg[id_barang]', '$jns[kd_kel]', '$gud[id_gudang]', '$id_sumber',
														'$uuid', '$uuidet',
														'$tgl_awal', '2020', '$r[jumlah]', 0, '$r[harga]', 'a',
														'$datime', 0, 'DEV 2')"); */
			
			// $ins = mysql_query("INSERT INTO ref_barang(id_barang, id_jenis, kd_sub2, nama_barang, id_satuan, harga_index, keterangan, status, create_date, update_date, soft_delete, creator_id ) VALUES (UUID(), '$jns[id_jenis]', '$kd_sub2', '$brg[nama_barang]', '$brg[id_satuan]', '$brg[harga_index]', '$brg[keterangan]', '$brg[status]', NOW(), NOW(), 0, 'DEV')");
		}
		
	
	}
	
?>



