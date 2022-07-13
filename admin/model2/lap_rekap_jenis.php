<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$bln = isset($_POST['bln']) ? $_POST['bln'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
	
	
	if($id_sub!=""){
		$wh = "WHERE uuid_sub2_unit = '$id_sub'";
		$sub = "AND uuid_skpd = '$id_sub'";
	}else{
		$wh = "WHERE MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$sub = "AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	}

	if($id_sum!=""){
		$idsum = "AND id_sumber_dana = '$id_sum'";
	}else{
		$idsum = "";
	}
	if($ta=="") $ta = date('Y');
	
	if($bln!=""){
		$bln = str_pad($bln,2,'0', STR_PAD_LEFT);
		$thnblnawal = $thnblnharga = $ta."-".$bln; 
	}else{
		if($smstr!=""){
			if($smstr==1){ $thnblnawal = $ta."-01"; $thnblnharga = $ta."-06"; }
			elseif($smstr==2){ $thnblnawal = $ta."-07"; $thnblnharga = $ta."-12"; }
		}else{ 
			$thnblnawal = $ta."-01"; $thnblnharga = $ta."-12";
		}
	}
	
	$result = array();
	
	$clause = "SELECT id_jenis, nama_jenis AS nama
				FROM ref_jenis j 
				WHERE kd_sub <> 0
				ORDER BY j.kd_kel, j.kd_sub";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $ttotal = 0; $footer = array();
	while($row = mysql_fetch_assoc($rs)){
		$sel = "(SELECT id_barang FROM ref_barang WHERE id_jenis = '$row[id_jenis]')";
		$sel1 = "(SELECT id_barang_kegiatan FROM ref_barang_kegiatan WHERE id_jenis = '$row[id_jenis]')";
		$ids = "AND ( id_barang IN $sel OR id_barang IN ($sel1) )";
		
		$nilaiPers = 0; $saldoKom = 0;
		
		$saldo = mysql_query("SELECT SUM(jml_in-jml_out) AS saldo, harga, id_barang, uuid_skpd FROM kartu_stok 
											WHERE DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
											AND soft_delete = 0 $ids $sub $idsum GROUP BY id_barang, uuid_skpd, harga");
											
		while($s = mysql_fetch_assoc($saldo)){
			$nilaiBar = $s['saldo']*$s['harga'];
			$saldoKom += $s['saldo'];
			$nilaiPers += $nilaiBar;
		}
		
		$row['saldo'] = number_format($saldoKom, 0, ',', '.');
		$row['nilai'] = number_format($nilaiPers, 0, ',', '.');
		if($saldoKom!=0){
			$ttotal += $nilaiPers;
			array_push($items, $row);
		}	
	}
	$result["rows"] = $items;
	$foot['ids'] = $id_sub;
	$foot['saldo'] = "Total";
	$foot['nilai'] = number_format($ttotal, 0, ',', '.');
	array_push($footer, $foot);
	$result["footer"] = $footer;
	echo json_encode($result);
	mysql_close();
?>