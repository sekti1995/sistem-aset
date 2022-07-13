<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$kd = isset($_REQUEST['kode_unit']) ? $_REQUEST['kode_unit'] : '1.1.1.1';
	$id_sum = isset($_GET['id_sum']) ? $_GET['id_sum'] : '';
	$bln = isset($_GET['bln']) ? $_GET['bln'] : '';
	$ta = isset($_GET['ta']) ? $_GET['ta'] : date('Y');
	$smstr = isset($_GET['smstr']) ? $_GET['smstr'] : '';
		
	if($ta=="" || $ta=="undefined") $ta = date('Y');	
	if($id_sum!="" && $id_sum!="undefined"){
		$idsum = "AND id_sumber_dana = '$id_sum'";
	}else{
		$idsum = "";
	}
	
	if($bln!="" && $bln!="undefined"){
		$bln = str_pad($bln,2,'0', STR_PAD_LEFT);
		$thnblnawal = $thnblnharga = $ta."-".$bln; 
	}else{
		if($smstr!="" && $smstr!="undefined"){
			if($smstr==1){ $thnblnawal = $ta."-01"; $thnblnharga = $ta."-06"; }
			elseif($smstr==2){ $thnblnawal = $ta."-07"; $thnblnharga = $ta."-12"; }
		}else{ 
			$thnblnawal = $ta."-01"; $thnblnharga = $ta."-12";
		}
	}
	$result = array();
	
	$clause = "SELECT uuid_sub2_unit AS id, nm_sub2_unit AS nama_sub, 
				CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) AS kode_sub
				FROM ref_sub2_unit 
				WHERE CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit,kd_sub) = '$kd'
				ORDER BY kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); //$totalPers = 0;
	while($row = mysql_fetch_assoc($rs)){
		if($row['id']!="cfa58119-5543-11e6-a2df-000476f4fa98" && $row['id']!="cfa57ef4-5543-11e6-a2df-000476f4fa98"){
			$nilaiPers = 0;
			$saldo = mysql_query("SELECT SUM(jml_in-jml_out) AS saldo, harga, id_barang, uuid_skpd FROM kartu_stok 
												WHERE DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
												AND soft_delete = 0 AND uuid_skpd = '$row[id]' $idsum GROUP BY id_barang, harga");
			while($s = mysql_fetch_assoc($saldo)){
				$nilaiBar = $s['saldo']*$s['harga'];		
				$nilaiPers += $nilaiBar;
			}
			//$totalPers += $nilaiPers;
			$row['nilai'] = number_format($nilaiPers, 0, ',', '.');
			
			array_push($items, $row);
		}
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>