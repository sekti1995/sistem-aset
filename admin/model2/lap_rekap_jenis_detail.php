<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$ids = isset($_GET['ids']) ? $_GET['ids'] : '';
	$idj = isset($_GET['idj']) ? $_GET['idj'] : '';
	$id_sum = isset($_GET['id_sum']) ? $_GET['id_sum'] : '';
	$bln = isset($_GET['bln']) ? $_GET['bln'] : '';
	$ta = isset($_GET['ta']) ? $_GET['ta'] : date('Y');
	$smstr = isset($_GET['smstr']) ? $_GET['smstr'] : '';
	
	if($ta=="") $ta = date('Y');
	//$sel = "(SELECT uuid_sub2_unit FROM ref_sub2_unit WHERE CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit) = '$kd')";
	//$ids = "AND k.uuid_skpd IN $sel";
	$idsub = "AND uuid_skpd = '$ids'";
	
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
	$nilai = mysql_query("SELECT SUM(k.jml_in-k.jml_out) AS jml, k.harga, k.id_barang AS id_bar,
							IFNULL(rb.nama_barang, bk.nama_barang_kegiatan) AS nama_bar,
							IF(ISNULL(bk.id_barang_kegiatan), 
								CONCAT_WS('.', j.kd_kel, j.kd_sub, rb.kd_sub2),
								CONCAT_WS('.', j1.kd_kel, j1.kd_sub, bk.kode)) kode_bar,
							IF(ISNULL(bk.id_barang_kegiatan), 'a', 'b') stat
						FROM kartu_stok k
						LEFT JOIN ref_barang rb ON rb.id_barang = k.id_barang
						LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang
						LEFT JOIN ref_jenis j ON j.id_jenis = rb.id_jenis
						LEFT JOIN ref_jenis j1 ON j1.id_jenis = bk.id_jenis
						WHERE k.id_barang IN ( 
							SELECT b.id_barang FROM ref_barang b 
								WHERE id_jenis = '$idj' 
							UNION ALL
							SELECT g.id_barang_kegiatan FROM ref_barang_kegiatan g 
								WHERE id_jenis = '$idj' 
								
						)
						AND k.soft_delete = 0 AND k.uuid_skpd = '$ids' $idsum
						AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
						GROUP BY k.id_barang, k.harga
						HAVING jml <> 0
						ORDER BY stat, j.kd_kel, j.kd_sub, rb.kd_sub2, bk.kode");
				
	$result["total"] = mysql_num_rows($nilai);					
	while($n = mysql_fetch_assoc($nilai)){
		$harga = mysql_fetch_row(mysql_query("SELECT harga FROM kartu_stok 
										WHERE id_barang = '$n[id_bar]' 
										AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
										AND uuid_skpd = '$ids'
										AND soft_delete = 0  AND jml_in <> 0 AND kode<>'m' 
										ORDER BY tgl_transaksi DESC, create_date DESC LIMIT 1"));
		$nilaiUPT = $n['jml']*$n['harga'];
		$n['saldo'] = number_format($n['jml'], 0, ',', '.');
		$n['nilai'] = number_format($nilaiUPT, 0, ',', '.');
		array_push($items, $n);
	}
		
		
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>