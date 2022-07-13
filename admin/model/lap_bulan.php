<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$ta_lalu = $ta - 1;
	if($ta!=""){ 
		$a1 = " AND LEFT(k1.tgl_transaksi,4) <= '$ta'"; 
		$a2 = " AND LEFT(k2.tgl_transaksi,4) = '$ta'"; 
		
		$ak1 = " AND k2.ta <= '$ta'"; 
		$ak2 = " AND k2.ta = '$ta'"; 
	}else{ $a1 = $a2 = $ak1 = $ak2 = ""; }
	if($_SESSION['level']==md5('c')){
		$b1 = " AND MD5(k1.uuid_skpd) = '$_SESSION[uidunit]'";
		$b2 = " AND MD5(k2.uuid_skpd) = '$_SESSION[uidunit]'";
	}else{
		$b1 = " AND k1.uuid_skpd = '$id_sub'";
		$b2 = " AND k2.uuid_skpd = '$id_sub'";
	}
	$c1 = " AND DATE_FORMAT(k1.tgl_transaksi, '%Y-%m-%d') < '$tglawal'"; 
	$c2 = " AND DATE_FORMAT(k2.tgl_transaksi, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
	
	$c3 = " AND DATE_FORMAT(k1.tgl_masuk_rinci, '%Y-%m-%d') < '$tglawal'"; 
	$c4 = " AND DATE_FORMAT(k1.tgl_masuk_rinci, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
	if($id_sum!=""){
		$d1 = "AND k1.id_sumber_dana = '$id_sum'";
		$d2 = "AND k2.id_sumber_dana = '$id_sum'";
	}else $d1 = $d2 = "";
	
	
	// $koder1 = "  AND k1.kode != 'r' ";
	// $koder2 = "  AND k2.kode != 'r' ";
	
	$koder1 = " ";
	$koder2 = " ";
	$result = array();
	$clause = "SELECT a.id_transaksi_detail, a.id_barang, a.kode_trans, IFNULL(b.nama_barang, bk.nama_barang_kegiatan) AS nama_barang, 
					 IFNULL(b.id_jenis, bk.id_jenis) AS jenis, 
					 CONCAT_WS('.', j1.kd_kel, j1.kd_sub) AS kode_bar, j1.nama_jenis,
					SUM(saldo) AS jml_lalu, SUM(jml_in) AS jml_in, SUM(jml_out) AS jml_out, harga, 
					IF(b.id_barang IS NOT NULL, 1, 2) AS j, IFNULL(s1.nama_satuan, s2.nama_satuan) AS satuan
				FROM (
				   SELECT k1.id_transaksi_detail, k1.id_barang, k1.kode AS kode_trans, 0 AS jml_in, k1.harga, (SUM(jml_in)-SUM(jml_out)) AS saldo, 0 AS jml_out
					FROM kartu_stok k1 WHERE k1.soft_delete = 0 $koder1 $a1 $b1 $c1 $d1
					GROUP BY k1.id_barang, k1.harga HAVING saldo <> 0
				   UNION ALL
				   SELECT k2.id_transaksi_detail, k2.id_barang, k2.kode AS kode_trans, SUM(k2.jml_in), k2.harga, 0 AS saldo, SUM(k2.jml_out)
				    FROM kartu_stok k2 WHERE k2.soft_delete = 0  AND k2.kode <> 'm' $koder2 $a2 $b2 $c2 $d2
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
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r; 
	$items = array(); $footer = array(); $ttotal = 0; $ttotal_brg = 0;
	while($row = mysql_fetch_assoc($rs)){
		
		//LALU
		$cek_keg_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(k1.jumlah*k1.harga) AS tot_harga FROM masuk_detail_rinci k1 LEFT JOIN masuk_detail k2 ON k1.id_masuk_detail = k2.id_masuk_detail WHERE k1.id_masuk_detail = '$row[id_transaksi_detail]' $ak1 $c3"));
		//INI
		$cek_keg_ini = mysql_fetch_assoc(mysql_query("SELECT SUM(k1.jumlah*k1.harga) AS tot_harga FROM masuk_detail_rinci k1 LEFT JOIN masuk_detail k2 ON k1.id_masuk_detail = k2.id_masuk_detail WHERE k1.id_masuk_detail = '$row[id_transaksi_detail]' $ak2 $c4"));
		
		$tot_keg = $cek_keg_lalu['tot_harga']+$cek_keg_ini['tot_harga'];
		
		if($tot_keg>0){
			$row['harga'] = $tot_keg;
		}
		
		$tot_lalu = $row['harga']*$row['jml_lalu'];
		$tot_in = $row['harga']*$row['jml_in'];
		$tot_out = $row['harga']*$row['jml_out'];
		$jml_ini = $row['jml_lalu']+$row['jml_in'];
		$tot_ini = $jml_ini*$row['harga'];
		$jumlah = $jml_ini - $row['jml_out'];
		$total = $jumlah * $row['harga'];
		$ttotal += $total;
		$ttotal_brg += $jumlah;
		
		$row['harga'] = number_format($row['harga'], 6, ',', '.');
		$row['jml_lalu'] = number_format($row['jml_lalu'], 6, ',', '.');
		$row['tot_lalu'] = number_format($tot_lalu, 6, ',', '.');
		$row['jml_in'] = number_format($row['jml_in'], 6, ',', '.');
		$row['tot_in'] = number_format($tot_in, 6, ',', '.');
		$row['jml_ini'] = number_format($jml_ini, 6, ',', '.');
		$row['tot_ini'] = number_format($tot_ini, 6, ',', '.');
		$row['jml_out'] = number_format($row['jml_out'], 6, ',', '.');
		$row['tot_out'] = number_format($tot_out, 6, ',', '.');
		$row['jumlah'] = number_format($jumlah, 6, ',', '.');
		$row['total'] = number_format($total, 6, ',', '.');
		
		$ex1 = explode(",", $row['harga']);
		if($ex1[1] > 0){
			$row['harga'] = $ex1[0].",".$ex1[1];
		} else {
			$row['harga'] = $ex1[0];
		}
		
		$ex2 = explode(",", $row['jml_lalu']);
		if($ex2[1] > 0){
			$row['jml_lalu'] = $ex2[0].",".$ex2[1];
		} else {
			$row['jml_lalu'] = $ex2[0];
		}
		
		$ex3 = explode(",", $row['tot_lalu']);
		if($ex3[1] > 0){
			$row['tot_lalu'] = $ex3[0].",".$ex3[1];
		} else {
			$row['tot_lalu'] = $ex3[0];
		}
		
		$ex4 = explode(",", $row['jml_in']);
		if($ex4[1] > 0){
			$row['jml_in'] = $ex4[0].",".$ex4[1];
		} else {
			$row['jml_in'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['tot_in']);
		if($ex4[1] > 0){
			$row['tot_in'] = $ex4[0].",".$ex4[1];
		} else {
			$row['tot_in'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['jml_ini']);
		if($ex4[1] > 0){
			$row['jml_ini'] = $ex4[0].",".$ex4[1];
		} else {
			$row['jml_ini'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['tot_ini']);
		if($ex4[1] > 0){
			$row['tot_ini'] = $ex4[0].",".$ex4[1];
		} else {
			$row['tot_ini'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['jml_out']);
		if($ex4[1] > 0){
			$row['jml_out'] = $ex4[0].",".$ex4[1];
		} else {
			$row['jml_out'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['tot_out']);
		if($ex4[1] > 0){
			$row['tot_out'] = $ex4[0].",".$ex4[1];
		} else {
			$row['tot_out'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['jumlah']);
		if($ex4[1] > 0){
			$row['jumlah'] = $ex4[0].",".$ex4[1];
		} else {
			$row['jumlah'] = $ex4[0];
		}
		
		$ex4 = explode(",", $row['total']);
		if($ex4[1] > 0){
			$row['total'] = $ex4[0].",".$ex4[1];
		} else {
			$row['total'] = $ex4[0];
		}
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	
	$foot['tot_ini'] = 'Total';
	$foot['jumlah'] = number_format($ttotal_brg, 6, ',', '.');
	$foot['total'] = number_format($ttotal, 6, ',', '.');
	
		
		$ex4 = explode(",", $foot['jumlah']);
		if($ex4[1] > 0){
			$foot['jumlah'] = $ex4[0].",".$ex4[1];
		} else {
			$foot['jumlah'] = $ex4[0];
		}
		
		$ex4 = explode(",", $foot['total']);
		if($ex4[1] > 0){
			$foot['total'] = $ex4[0].",".$ex4[1];
		} else {
			$foot['total'] = $ex4[0];
		}
	
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>