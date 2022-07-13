<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$kd = isset($_GET['kd']) ? $_GET['kd'] : '1.1.1';
	$idb = isset($_GET['idb']) ? $_GET['idb'] : '3e56de5f-7fc8-11e6-aed5-000476f4fa98';
	$id_sum = isset($_GET['id_sum']) ? $_GET['id_sum'] : '';
	$bln = isset($_GET['bln']) ? $_GET['bln'] : '';
	$ta = isset($_GET['ta']) ? $_GET['ta'] : date('Y');
	$smstr = isset($_GET['smstr']) ? $_GET['smstr'] : '';
	
	if($ta=="") $ta = date('Y');
	$sel = "(SELECT uuid_sub2_unit FROM ref_sub2_unit WHERE CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit) = '$kd')";
	$ids = "AND k.uuid_skpd IN $sel";
	$idsub = "AND uuid_skpd IN $sel";
	
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
	
	$result = array(); $nilaiRekap = 0; $items = array();
	$query = mysql_query("SELECT SUM(k.jml_in-k.jml_out) AS jml, harga, k.uuid_skpd, nm_sub2_unit AS nama_unit,
						CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub) AS kode_unit
						FROM kartu_stok k
						LEFT JOIN ref_sub2_unit s ON s.uuid_sub2_unit = k.uuid_skpd
						WHERE k.id_barang = '$idb' 
						AND k.soft_delete = 0 $idsub $idsum
						AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
						GROUP BY k.uuid_skpd, k.harga
						ORDER BY kd_urusan, kd_bidang, kd_unit, kd_sub");
	$result["total"] = mysql_num_rows($query); 
	$kode_unit = ""; $nilai = $no = $jml = 0;		
	while($n = mysql_fetch_assoc($query)){
		if($kode_unit!="" && $kode_unit!=$n['kode_unit']){
			array_push($items, $n);
			$nilai = $jml = 0; 
		}
		$kode_unit = $n['kode_unit'];
		$jml += $n['jml'];
		$nilai += $n['jml']*$n['harga'];
		$n['saldo'] = number_format($jml, 0, ',', '.');
		$n['nilai'] = number_format($nilai, 0, ',', '.');
		
		$no++;
		if($no==$result["total"]) array_push($items, $n);
	}
		
		
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>