<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$thn = isset($_POST['thn']) ? $_POST['thn'] : '';
	$bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : '';
	$id_bar = isset($_POST['id_bar']) ? $_POST['id_bar'] : '';
	
	
	if($thn!=""){ 
		$a1 = " AND m.ta = '$thn'"; 
		$a2 = " AND k.ta = '$thn'"; 
	}else{ $a1 = ""; $a2 = ""; }
	if($peran==md5('3')){
		$b1 = " AND MD5(CONCAT_WS('.',m.kd_urusan, m.kd_bidang, m.kd_unit, m.kd_sub)) = '$_SESSION[idsubunit]'";
		$b2 = " AND MD5(CONCAT_WS('.',k.kd_urusan, k.kd_bidang, k.kd_unit, k.kd_sub)) = '$_SESSION[idsubunit]'";
	}else{
		$b1 = " AND CONCAT_WS('.',m.kd_urusan, m.kd_bidang, m.kd_unit, m.kd_sub) = '$id_sub'";
		$b2 = " AND CONCAT_WS('.',k.kd_urusan, k.kd_bidang, k.kd_unit, k.kd_sub) = '$id_sub'";
	}
	if($bln!="" AND $bln!="00"){
		$c1 = " AND DATE_FORMAT(tgl_penerimaan, '%m') = '$bln'"; 
		$c2 = " AND DATE_FORMAT(tgl_terima, '%m') = '$bln'"; 
	}else{ $c1 = ""; $c2 = ""; }
	
	$offset = ($page-1)*$rows;
	$result = array();
	
	$clause = "SELECT jml_masuk AS masuk, '0' AS keluar, tgl_penerimaan AS tanggal, keterangan
				FROM masuk_detail md
				LEFT JOIN masuk m ON m.id_masuk = md.id_masuk
				WHERE md.soft_delete=0 AND m.status_proses = 3 AND id_barang = '$id_bar' $a1 $b1 $c1
				UNION ALL
				SELECT '0' AS masuk, jml_barang AS keluar, tgl_terima AS tanggal, kd.keterangan
				FROM keluar_detail kd
				LEFT JOIN keluar k ON k.id_keluar = kd.id_keluar
				WHERE kd.soft_delete=0 AND id_barang = '$id_bar' $a2 $b2 $c2
				ORDER BY tanggal";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $sisa = 0;
	while($row = mysql_fetch_assoc($rs)){
		$sisa = $sisa + $row['masuk'] - $row['keluar'];
		$row['tanggal'] = balikTanggalIndo($row['tanggal']);
		$row['masuk'] = number_format($row['masuk'], 0, ',', '.');
		$row['keluar'] = number_format($row['keluar'], 0, ',', '.');
		$row['sisa'] = number_format($sisa, 0, ',', '.');
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>