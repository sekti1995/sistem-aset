<?php
	session_start();
	require_once "db.koneksi.php";
	
	echo "<table>";
	$q = mysql_query("SELECT *, FORMAT(jml_in, 2,'de_DE') AS masuk, FORMAT(harga, 2,'de_DE') AS harga_satuan FROM kartu_stok t1 LEFT JOIN ref_sub2_unit t2 ON t1.uuid_skpd = t2.uuid_sub2_unit LEFT JOIN ref_sumber_dana t3 ON t1.id_sumber_dana = t3.id_sumber LEFT JOIN ref_kelompok t4 ON t1.id_kelompok = t4.id_kelompok
	
	WHERE t2.kd_unit = '2' AND kd_sub2 = '1' AND kd_sub >= 3 AND kd_sub <= 23 AND jml_in > 0 AND t1.soft_delete = 0");
	while($r = mysql_fetch_assoc($q)){
		$bar = mysql_fetch_assoc(mysql_query("SELECT nama_barang, nama_jenis, simbol FROM ref_barang t1 LEFT JOIN ref_jenis t2 ON t1.id_jenis = t2.id_jenis LEFT JOIN ref_satuan t3 ON t1.id_satuan = t3.id_satuan WHERE id_barang = '$r[id_barang]' "));
		$msk = mysql_fetch_assoc(mysql_query("SELECT * FROM masuk WHERE id_masuk = '$r[id_transaksi]' "));
		if($r["kode"] == 'a'){
			$msk["nama_pengadaan"] = "Saldo Awal 2020";
			$rek = "";
		} else if($r["kode"] == 'r'){
			$msk["nama_pengadaan"] = "Penyaluran Dari DINKES";
			$rek = "";
		} else {
			$rek = $msk["id_prog"].".".$msk["kd_keg"].".".$msk["kd_rek_1"].".".$msk["kd_rek_2"].".".$msk["kd_rek_3"].".".$msk["kd_rek_4"].".".$msk["kd_rek_5"];
		}
		
		echo "<tr>
					<td>".$r["id_stok"]."</td>
					<td>".$r["nm_sub2_unit"]."</td>
					<td>".$r["tgl_transaksi"]."</td>
					
					<td>".$rek."</td>
					<td>".$msk["nama_pengadaan"]."</td>
					<td>".$msk["nama_penyedia"]."</td>
					<td>".$msk["no_kontrak"]."</td>
					<td>".$msk["no_pembayaran"]."</td>
					<td>".$r["nama_kelompok"]."</td>
					<td>".$r["nama_sumber"]."</td>
					
					<td>".$bar["id_jenis"]."</td>
					<td>".$r["id_barang"]."</td>
					<td>".$bar["nama_barang"]."</td>
					<td>".$bar["simbol"]."</td>
					<td>".$bar["nama_jenis"]."</td>
					<td>".$r["masuk"]."</td>
					<td>".$r["harga_satuan"]."</td>
					<td>".$r["kode"]."</td>
					<td>".$r["keterangan"]."</td>
					</tr>";
		
	}
	echo "</table>";
	
	
	
	/* 
	echo "<table>";
	$q = mysql_query("SELECT *, FORMAT(jml_out, 2,'de_DE') AS keluar, FORMAT(harga, 2,'de_DE') AS harga_satuan FROM kartu_stok t1 LEFT JOIN ref_sub2_unit t2 ON t1.uuid_skpd = t2.uuid_sub2_unit WHERE t2.kd_unit = '2' AND kd_sub2 = '1' AND kd_sub >= 3 AND kd_sub <= 23 AND jml_out > 0 AND t1.soft_delete = 0");
	while($r = mysql_fetch_assoc($q)){
		$bar = mysql_fetch_assoc(mysql_query("SELECT nama_barang, nama_jenis, simbol FROM ref_barang t1 LEFT JOIN ref_jenis t2 ON t1.id_jenis = t2.id_jenis LEFT JOIN ref_satuan t3 ON t1.id_satuan = t3.id_satuan WHERE id_barang = '$r[id_barang]' "));
		echo "<tr><td>".$r["nm_sub2_unit"]."</td><td>".$bar["nama_barang"]."</td><td>".$bar["simbol"]."</td><td>".$bar["nama_jenis"]."</td><td>".$r["keluar"]."</td><td>".$r["harga_satuan"]."</td><td>".$r["kode"]."</td><td>".$r["keterangan"]."</td><td>".$r["id_barang"]."</td></tr>";
		
	}
	echo "</table>"; */
	
?>