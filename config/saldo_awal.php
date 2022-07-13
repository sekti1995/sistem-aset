<?php
error_reporting(E_ALL); ini_set('display_errors', 'on'); 

	//DATABASE PERTAMA 
	$db_host1 = 'localhost';
	$db_user1 = 'sql_sbpr';
	$db_pass1 = 'Sql.sbpr.2019';
	$database1 = 'persedian_2019';
	// $db1 = new mysqli($db_host1, $db_user1, $db_pass1, $database1);1
 
		
	//DATABASE KEDUA
	$db_host2 = 'localhost';
	$db_user2 = 'root';
	$db_pass2 = 'Sql.root.2018';
	$database2 = 'persediaan_2020';
		
	try{  
		$db1 = new PDO("mysql:host=$db_host1;dbname=$database1",$db_user1,$db_pass1);
		$db1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db2 = new PDO("mysql:host=$db_host2;dbname=$database2",$db_user2,$db_pass2);
		$db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $ex){
		die($ex->getMessage());
	}

	  
	$ta = 2019;
	$a1 = " AND k1.ta < '$ta'"; 
	$a2 = " AND k2.ta = '$ta'"; 
	
	$query = "SELECT * FROM ref_sub2_unit_pindah WHERE urut >= 1001 AND urut <= 1100 " ;
	$q1 = $db1->prepare($query);
	$q1->execute();	 
	while ($r = $q1->fetch(PDO::FETCH_ASSOC)) {
	 
		echo $r["uuid_sub2_unit"]; 
	    $b1 = " AND k1.uuid_skpd = '$r[uuid_sub2_unit]'";
		$b2 = " AND k2.uuid_skpd = '$r[uuid_sub2_unit]'";
		
		$clause = "SELECT a.id_sumber_dana, a.id_gudang, a.id_kelompok, a.id_barang, a.kode_trans, IFNULL(b.nama_barang, bk.nama_barang_kegiatan) AS nama_barang, 
						 IFNULL(b.id_jenis, bk.id_jenis) AS jenis, 
						 CONCAT_WS('.', j1.kd_kel, j1.kd_sub) AS kode_bar, j1.nama_jenis,
						SUM(saldo) AS jml_lalu, SUM(jml_in) AS jml_in, SUM(jml_out) AS jml_out, harga, 
						IF(b.id_barang IS NOT NULL, 1, 2) AS j, IFNULL(s1.nama_satuan, s2.nama_satuan) AS satuan
					FROM (
					   SELECT k1.id_sumber_dana, k1.id_gudang, k1.id_kelompok, k1.id_barang, k1.kode AS kode_trans, 0 AS jml_in, k1.harga, (SUM(jml_in)-SUM(jml_out)) AS saldo, 0 AS jml_out
						FROM kartu_stok k1 WHERE k1.soft_delete = 0 $a1 $b1 
						GROUP BY k1.id_barang, k1.harga HAVING saldo <> 0
					   UNION ALL
					   SELECT k2.id_sumber_dana, k2.id_gudang, k2.id_kelompok, k2.id_barang, k2.kode AS kode_trans, SUM(k2.jml_in), k2.harga, 0 AS saldo, SUM(k2.jml_out)
						FROM kartu_stok k2 WHERE k2.soft_delete = 0  AND k2.kode <> 'm' $a2 $b2 
						GROUP BY k2.id_barang, k2.harga
					) AS a
					LEFT JOIN ref_barang b ON b.id_barang = a.id_barang 
					LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = a.id_barang
					LEFT JOIN ref_jenis j1 ON j1.id_jenis = b.id_jenis
					LEFT JOIN ref_jenis j2 ON j2.id_jenis = bk.id_jenis
					LEFT JOIN ref_satuan s1 ON s1.id_satuan = b.id_satuan
					LEFT JOIN ref_satuan s2 ON s2.id_satuan = bk.id_satuan
					GROUP BY a.id_barang, a.harga
					ORDER BY j, j1.kd_kel, j2.kd_kel, j1.kd_sub, j2.kd_sub, b.kd_sub2, bk.kode";
					
					
		$q2 = $db1->prepare($clause);
		$q2->execute();	 
		$items = array(); $footer = array(); $ttotal = 0; $ttotal_brg = 0;
		while ($row = $q2->fetch(PDO::FETCH_ASSOC)) {
		// while($row = mysql_fetch_assoc($rs)){
			$tot_lalu = $row['harga']*$row['jml_lalu'];
			$tot_in = $row['harga']*$row['jml_in'];
			$tot_out = $row['harga']*$row['jml_out'];
			$jml_ini = $row['jml_lalu']+$row['jml_in'];
			$tot_ini = $jml_ini*$row['harga'];
			$jumlah = $jml_ini - $row['jml_out'];
			$total = $jumlah * $row['harga'];
			$ttotal += $total;
			$ttotal_brg += $jumlah; 
			if($jumlah > 0){
				echo $row["id_barang"]." | ";
				echo $row["id_kelompok"]." | ";
				echo $row["id_gudang"]." | ";
				echo $row["id_sumber_dana"]." | ";
				echo $row["nama_barang"]." | ";
				echo $jumlah." | ";
				echo $row["harga"]."<br>";
				
				
				$clause2 = "INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
													id_sumber_dana,
													id_transaksi, id_transaksi_detail,
													tgl_transaksi, ta, jml_in, jml_out, harga, kode,
													create_date, soft_delete, creator_id)
											VALUES	(UUID(), '$r[uuid_sub2_unit]', '$row[id_barang]', '$row[id_kelompok]', '$row[id_gudang]', 
													'$row[id_sumber_dana]',
													UUID(), UUID(),
													'2019-12-31', '2019', '$jumlah', 0, '$row[harga]', 'a',
													NOW(), 0, 'admin')" ;
				$q3 = $db2->prepare($clause2);
				$q3->execute();	
			}
			// echo $jumlah."<br>";
		}  
	}
	
?>