<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	error_reporting(E_ALL); ini_set('display_errors', 'on'); 
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$bln = isset($_POST['bln']) ? str_pad($_REQUEST['bln'],2,'0', STR_PAD_LEFT) : str_pad(date('m'),2,'0', STR_PAD_LEFT);
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	
	if($id_sub!=""){
		$ids = "AND k.uuid_skpd = '$id_sub'";
		$idsub = "AND uuid_skpd = '$id_sub'";
	}else{
		$ids = "AND MD5(k.uuid_skpd) = '$_SESSION[uidunit]'"; 
		$idsub = "AND MD5(uuid_skpd) = '$_SESSION[uidunit]'"; 
	}
	if($id_sum!=""){
		$idsum = "AND k.id_sumber_dana = '$id_sum'";
	}else{
		$idsum = "";
	}
	$thnbln = $ta."-".$bln;
	
	$result = array();
	
	$clause = "SELECT k.id_barang, IFNULL(nama_barang_kegiatan, nama_barang) nama_barang, k.harga,
					IF(ISNULL(bk.id_barang_kegiatan), 'a', 'b') stat, IFNULL(s1.nama_satuan, s2.nama_satuan) satuan,
					(SUM(jml_in)-SUM(jml_out)) AS saldo
				FROM kartu_stok k 
				LEFT JOIN ref_barang b ON b.id_barang = k.id_barang 
				LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang
				LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis
				LEFT JOIN ref_satuan s1 ON s1.id_satuan = b.id_satuan
				LEFT JOIN ref_satuan s2 ON s2.id_satuan = bk.id_satuan
				WHERE k.kode <> 'm' AND k.soft_delete = 0 $ids $idsum
				AND DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tglawal' AS DATE) AND CAST('$tglakhir' AS DATE)
				GROUP BY k.id_barang, harga
				HAVING saldo <> 0
				ORDER BY stat, j.kd_kel, j.kd_sub, b.kd_sub2";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r; 
	$items = array(); $footer = array(); $ttotal = 0;
	while($row = mysql_fetch_assoc($rs)){
		
		$total = $row['harga']*$row['saldo'];
		$ttotal += $total;
		$row['harga'] = number_format($row['harga'], 15, ',', '.');
		$row['jumlah'] = number_format($row['saldo'], 15, ',', '.');
		$row['total'] = number_format($total, 15, ',', '.');
	
		$ex1 = explode(",", $row['harga']);
		if($ex1[1] > 0){
			$row['harga'] = $ex1[0].",".$ex1[1];
		} else {
			$row['harga'] = $ex1[0];
		}
		$ex1 = explode(",", $row['jumlah']);
		if($ex1[1] > 0){
			$row['jumlah'] = $ex1[0].",".$ex1[1];
		} else {
			$row['jumlah'] = $ex1[0];
		}
		$ex1 = explode(",", $row['total']);
		if($ex1[1] > 0){
			$row['total'] = $ex1[0].",".$ex1[1];
		} else {
			$row['total'] = $ex1[0];
		}
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	
	$foot['jumlah'] = 'Total';
	$foot['total'] = number_format($ttotal, 15, ',', '.');
		$ex1 = explode(",", $foot['total']);
		if($ex1[1] > 0){
			$foot['total'] = $ex1[0].",".$ex1[1];
		} else {
			$foot['total'] = $ex1[0];
		}
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>