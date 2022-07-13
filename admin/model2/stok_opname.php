<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
	$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal']!="" ? balikTanggal($_POST['tanggal']) : '' : '';
	
	
	if($_SESSION['level']==md5('c')){
		$a = " AND MD5(k.uuid_skpd) = '$_SESSION[uidunit]'";
		$a1 = " AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	}else{
		$a = " AND k.uuid_skpd = '$idsub'";
		$a1 = " AND uuid_skpd = '$idsub'";
	}
	$b = " AND no_so = '$nomor'";
	$c = " AND DATE_FORMAT(tgl_so, '%Y-%m-%d')  = '$tanggal' ";
	
	$where = "$a $b $c";
	$id = mysql_fetch_row(mysql_query("SELECT id_so FROM so k WHERE k.soft_delete = 0 $where"));
	$id_so = $id[0];
	$tgl = mysql_fetch_row(mysql_query("SELECT MAX(tgl_transaksi) FROM kartu_stok WHERE id_stok IS NOT NULL $a1"));
	$tgl_akhir = balikTanggalIndo($tgl[0]);
	
	$result = array();
	if($id_so!=''){
		$clause = "SELECT jml_komp AS jml_admin, jml_fisik AS jml_so, harga_komp AS hrgsat_admin, harga_fisik AS hrgsat_so, 
					s.id_barang AS id_bar, IFNULL(nama_barang_kegiatan, nama_barang) nama_bar, nama_gudang AS nama_gud, 
					s.id_gudang AS id_gud, IFNULL(s1.nama_satuan, t.nama_satuan) sat_admin, IFNULL(s1.nama_satuan, t.nama_satuan) sat_so, 
					id_so AS id, id_so_detail AS id_det, d.nama_sumber AS nama_sumber, s.id_sumber_dana AS id_sum,
					IF(ISNULL(bk.id_barang_kegiatan), 'a', 'b') stat
					FROM so_detail s 
					LEFT JOIN ref_barang b ON b.id_barang=s.id_barang
					LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = s.id_barang
					LEFT JOIN ref_satuan t ON t.id_satuan=b.id_satuan
					LEFT JOIN ref_satuan s1 ON s1.id_satuan = bk.id_satuan
					LEFT JOIN ref_gudang g ON g.id_gudang = s.id_gudang
					LEFT JOIN ref_sumber_dana d ON d.id_sumber = s.id_sumber_dana
					LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
					WHERE s.soft_delete=0 AND id_so = '$id_so'
					ORDER BY stat, s.id_gudang, j.kd_kel, j.kd_sub, b.kd_sub2, s.id_sumber_dana";
	}else{
		$clause = "SELECT nama_gudang AS nama_gud, IFNULL(nama_barang_kegiatan, nama_barang) AS nama_bar, 
						IFNULL(s1.nama_satuan, s.nama_satuan) AS sat_admin, IFNULL(s1.nama_satuan, s.nama_satuan) AS sat_so, 
						k.id_barang AS id_bar, k.id_gudang AS id_gud, d.nama_sumber AS nama_sumber, k.id_sumber_dana AS id_sum,
						IF(ISNULL(bk.id_barang_kegiatan), 'a', 'b') stat,
						(SELECT (SUM(k1.jml_in)-SUM(k1.jml_out)) FROM kartu_stok k1
						WHERE k1.id_barang = k.id_barang AND DATE_FORMAT(k1.tgl_transaksi, '%Y-%m-%d') <= '$tanggal'
						AND k1.soft_delete = 0 AND k1.uuid_skpd = k.uuid_skpd AND k1.id_gudang = k.id_gudang
						AND k1.id_sumber_dana = k.id_sumber_dana AND k1.harga = k.harga) AS jml_admin,
						harga AS hrgsat_admin,
						j.kd_kel, j.kd_sub, IFNULL(b.kd_sub2,bk.kode) AS kd_sub2
					FROM kartu_stok k
					LEFT JOIN ref_barang b ON b.id_barang = k.id_barang
					LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang
					LEFT JOIN ref_satuan s ON s.id_satuan = b.id_satuan
					LEFT JOIN ref_satuan s1 ON s1.id_satuan = bk.id_satuan
					LEFT JOIN ref_gudang g ON g.id_gudang = k.id_gudang
					LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
					LEFT JOIN ref_sumber_dana d ON d.id_sumber = k.id_sumber_dana
					WHERE k.soft_delete = 0 AND k.id_barang IS NOT NULL $a
					GROUP BY k.id_barang, k.harga, k.id_gudang, k.id_sumber_dana
					ORDER BY stat, k.id_gudang, j.kd_kel, j.kd_sub, b.kd_sub2, k.id_sumber_dana
					";
	}
	
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$totadmin = $row['jml_admin']*$row['hrgsat_admin'];
		$row['jml_admin'] = number_format($row['jml_admin'], 0, ',', '.');
		$row['hrgsat_so'] = number_format($row['hrgsat_admin'], 0, ',', '.');
		$row['hrgsat_admin'] = number_format($row['hrgsat_admin'], 0, ',', '.');
		$row['hrgtot_admin'] = number_format($totadmin, 0, ',', '.');
			$row['jml_so'] = $row['jml_admin'];
			$row['hrgsat_so'] = $row['hrgsat_admin'];
			$row['hrgtot_so'] = $row['hrgtot_admin'];
		if($id_so!=''){
			$totso = $row['jml_so']*$row['hrgsat_so'];
			$row['jml_so'] = number_format($row['jml_so'], 0, ',', '.');
			$row['hrgsat_so'] = number_format($row['hrgsat_so'], 0, ',', '.');
			$row['hrgtot_so'] = number_format($totso, 0, ',', '.');
		}
		array_push($items, $row);
	}
	$result["rows"] = $items;
	$result["id"] = $id_so;
	$result["tgl_akhir"] = $tgl_akhir;
	echo json_encode($result);
	mysql_close();
?>