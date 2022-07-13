<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sumber = isset($_POST['id_sumber']) ? $_POST['id_sumber'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	// $bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : str_pad(date('m'),2,'0', STR_PAD_LEFT);
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	
	
	if($ta!="") $a = " AND ta = '$ta'"; else $a = "";
	if($id_sub!="") $b = " AND uuid_skpd = '$id_sub'";
	else $b = " AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";	
	if($_SESSION['peran_id']==MD5('1')) $b .= "";
	else{
		if($_SESSION['level']==MD5('a')) $b .= " AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==MD5('b')) $b .= "  AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub)) = '$_SESSION[peserta]'";
	}	
	if($tglawal!="" and $tglakhir!="") $c = "AND DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tglawal' AS DATE) AND CAST('$tglakhir' AS DATE)"; else $c = "";
	if($id_sumber!="") $d = " AND id_sumber_dana = '$id_sumber'"; else $d = "";
	
	$where = " $a $b $c $d";
	$offset = ($page-1)*$rows;
	$result = array();
	$footer = array();
	
	$clause = " SELECT id_stok, id_barang, id_transaksi, id_transaksi_detail, kode, jml_in AS jml_masuk, harga AS harga_masuk, tgl_transaksi AS tgl_terima FROM kartu_stok u
						 LEFT JOIN ref_sub2_unit s
						 ON s.uuid_sub2_unit = u.uuid_skpd 
						 WHERE kode != 'a' AND kode != 'm' AND soft_delete = 0 AND jml_in > 0 $where ";
	
	$rs = mysql_query($clause);
	// $r = mysql_num_rows($rs);
	// $result["total"] = $r;
	//$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	$tot_bar = 0;
	$tot_har = 0;
	while($row = mysql_fetch_assoc($rs)){
		
		$barang = mysql_fetch_assoc(mysql_query("SELECT nama_barang FROM ref_barang WHERE id_barang = '$row[id_barang]' "));
		$row["nama_barang"] = $barang["nama_barang"];
		if($row["kode"] == "i"){
			$dari = mysql_fetch_assoc(mysql_query("SELECT * FROM masuk WHERE id_masuk = '$row[id_transaksi]' "));
			$row["dari"] = $dari["nama_pengadaan"];
			$row["no_dok"] = $dari["no_dok_penerimaan"];
			$row["tgl_dok"] = $dari["tgl_dok_penerimaan"];
			$row["no_ba"] = $dari["no_ba_penerimaan"];
			$row["tgl_ba"] = $dari["tgl_penerimaan"];
		}
		else if($row["kode"] == "r"){
			$dari = mysql_fetch_assoc(mysql_query("SELECT * FROM terima_keluar t1 
															LEFT JOIN keluar t2 ON t1.id_keluar = t2.id_keluar  
															LEFT JOIN ref_sub2_unit t3 ON t2.uuid_skpd = t3.uuid_sub2_unit
															WHERE id_terima_keluar = '$row[id_transaksi]' "));
			$row["dari"] = $dari["nm_sub2_unit"];
			$row["no_dok"] = "";
			$row["tgl_dok"] = "";
			$row["no_ba"] = $dari["no_ba_out"];
			$row["tgl_ba"] = $dari["tgl_ba_out"];
		}
		
		
		$harga = $row['harga_masuk'] * $row['jml_masuk'];
		$tot_bar += $row['jml_masuk'];
		$tot_har += $harga;
		$row['tgl_terima'] = balikTanggalIndo($row['tgl_terima']);
		$row['tgl_ba'] = balikTanggalIndo($row['tgl_ba']);
		$row['tgl_dok'] = balikTanggalIndo($row['tgl_dok']);
		// $row['jml_barang'] = number_format($row['jml_masuk'], 0, ',', '.')." ";
		// $row['hrg_barang'] = number_format($row['harga_masuk'], 0, ',', '.');
		$row['tot_harga'] = number_format($harga, 6, ',', '.');
		
		
		
		
		$row['hrg_barang'] = number_format($row['harga_masuk'], 6, ',', '.');
		$ex1 = explode(",", $row['hrg_barang']);
		if($ex1[1] > 0){
			$row['hrg_barang'] = $ex1[0].",".$ex1[1];
		} else {
			$row['hrg_barang'] = $ex1[0];
		}
		
		$row['jml_barang'] = number_format($row['jml_masuk'], 6, ',', '.');
		$ex1 = explode(",", $row['jml_barang']);
		if($ex1[1] > 0){
			$row['jml_barang'] = $ex1[0].",".$ex1[1];
		} else {
			$row['jml_barang'] = $ex1[0];
		}
		
		array_push($items, $row);
	}
	$tot_bar = number_format($tot_bar, 6, ',', '.');
	$tot_har = number_format($tot_har, 6, ',', '.');
	
	$ex4 = explode(",", $tot_bar);
	if($ex4[1] > 0){
		$tot_bar = $ex4[0].",".$ex4[1];
	} else {
		$tot_bar = $ex4[0];
	}
	
	$ex4 = explode(",", $tot_har);
	if($ex4[1] > 0){
		$tot_har = $ex4[0].",".$ex4[1];
	} else {
		$tot_har = $ex4[0];
	}
	
	$foot["jml_barang"] = $tot_bar;
	$foot["tot_harga"] = $tot_har;
	
	$result["rows"] = $items;
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>