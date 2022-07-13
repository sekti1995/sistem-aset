<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	error_reporting(E_ALL); ini_set('display_errors', 'on'); 
	
	$peran = cekLogin();
	$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
	$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal']!="" ? balikTanggal($_POST['tanggal']) : '' : '';
	//$tanggal = "2018-12-30";
	$ta = date("Y");
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
	
	$clause = "SELECT 
							k.id_barang AS id_bar, k.id_gudang AS id_gud, d.nama_sumber AS nama_sumber, k.id_sumber_dana AS id_sum, 'b' AS stat, harga AS hrgsat_admin, k.uuid_skpd
						FROM kartu_stok k
							LEFT JOIN ref_sumber_dana d ON d.id_sumber = k.id_sumber_dana
						WHERE 
							k.soft_delete = 0 AND k.id_barang IS NOT NULL $a AND k.ta <= '$ta'
						GROUP BY 
							k.id_barang, k.harga,  k.id_sumber_dana , k.id_gudang
					";
	
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		
		$refbar = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang WHERE id_barang = '$row[id_bar]' "));
		$row["nama_bar"] = $refbar["nama_barang"];
		
		if($row["nama_bar"] == ""){
			$refbar = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang_kegiatan WHERE id_barang_kegiatan = '$row[id_bar]' "));
			$row["nama_bar"] = $refbar["nama_barang_kegiatan"];
		}
		
		$refsat = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_satuan WHERE id_satuan = '$refbar[id_satuan]' "));
		$row["sat_admin"] = $refsat["nama_satuan"];
		$row["sat_so"] = $refsat["nama_satuan"];
		$row["id_satuan"] = $refsat["id_satuan"];
		
		$refgud = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_gudang WHERE id_gudang = '$row[id_gud]' "));
		$row["nama_gud"] = $refgud["nama_gudang"];
		
		// $refbar = $jmlad = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang WHERE id_barang = '$row[id_bar]' "));
		// $row["nama_bar"] = $refbar["nama_barang"];
		
		$refjns = $jmlad = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_jenis WHERE id_jenis = '$refbar[id_jenis]' "));
		$row["id_jenis"] = $refjns["id_jenis"];
		$row["nama_jenis"] = $refjns["nama_jenis"];
		
		$jmlad = mysql_fetch_assoc(mysql_query("SELECT (SUM(k1.jml_in)-SUM(k1.jml_out)) AS jml_admin, SUM(k1.jml_in) AS j1, SUM(k1.jml_out) AS j2 FROM kartu_stok k1
						WHERE k1.id_barang = '$row[id_bar]' AND DATE_FORMAT(k1.tgl_transaksi, '%Y-%m-%d') <= '$tanggal'
						AND k1.soft_delete = 0 AND k1.uuid_skpd = '$row[uuid_skpd]' AND k1.id_gudang = '$row[id_gud]'
						AND k1.id_sumber_dana = '$row[id_sum]' AND k1.harga = '$row[hrgsat_admin]' "));
		
		$row["jml_admin"] = $jmlad["jml_admin"];
		
		$totadmin = $row['jml_admin']*$row['hrgsat_admin'];
		$row['jml_admin'] = number_format($row['jml_admin'], 0, ',', '.');
		$row['hrgsat_so'] = number_format($row['hrgsat_admin'], 0, ',', '.');
		$row['hrgsat_admin'] = number_format($row['hrgsat_admin'], 0, ',', '.');
		$row['hrgtot_admin'] = number_format($totadmin, 0, ',', '.');
		
		// $row['jml_so'] = $row['jml_admin'];
		// $row['hrgsat_so'] = $row['hrgsat_admin'];
		// $row['hrgtot_so'] = $row['hrgtot_admin'];
		if($row['hrgtot_admin'] >= 0){
			$row['jml_so'] = 0;
			$row['hrgsat_so'] = $row['hrgsat_admin'];
			$row['hrgtot_so'] = 0;
		} else {
			$row['jml_so'] = $row['jml_admin'];
			$row['hrgsat_so'] = $row['hrgsat_admin'];
			$row['hrgtot_so'] = $row['hrgtot_admin'];
		}
		
		if($id_so!=''){
			$totso = $row['jml_so']*$row['hrgsat_so'];
			$row['jml_so'] = number_format($row['jml_so'], 0, ',', '.');
			$row['hrgsat_so'] = number_format($row['hrgsat_so'], 0, ',', '.');
			$row['hrgtot_so'] = number_format($totso, 0, ',', '.');
		}
		
		//$row['jml_admin'] = $row['jml_admin']." ".$jmlad["j1"]." ".$jmlad["j2"];
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	$result["id"] = $id_so;
	$result["tgl_akhir"] = $tgl_akhir;
	echo json_encode($result);
	mysql_close();
?>