<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	$peran = cekLogin();
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$idn = isset($_GET['idn']) ? $_GET['idn'] : '';
	
	if($id!="" && $id!="undefined") $w = "AND o.uuid_skpd = '$id'";
	else{
		$w = " AND MD5(o.uuid_skpd) = '$_SESSION[uidunit]'";
	}
	if($idn!="" && $idn!="undefined") $g = "AND ( o.status = 1 OR id_sp_out = '$idn')";
	else $g = " AND o.status = 1";
	
	$clause = "SELECT id_sp_out AS id, no_sp_out AS text, o.uuid_untuk AS id_untuk, IFNULL(nm_sub2_unit, o.peruntukan) AS txtuntuk,
					IF(o.stat_untuk=0, 'k', 's') AS stat, tgl_spb, o.uuid_skpd, o.stat_untuk
					FROM sp_out o
					LEFT JOIN ref_sub2_unit ON uuid_sub2_unit = o.uuid_untuk
					LEFT JOIN surat_minta sm ON sm.id_surat_minta = o.id_surat_minta 
					WHERE o.soft_delete = 0 $w $g
					ORDER BY tgl_sp_out, o.create_date";

	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array(); $no = 1;
	while($row = mysql_fetch_assoc($rs)){
		$ni = mysql_num_rows(mysql_query("SELECT id_stok FROM kartu_stok WHERE uuid_skpd = '$row[uuid_skpd]'
						GROUP BY id_gudang, id_sumber_dana"));
		if($ni>1) $row['show'] = "no";
		else $row['show'] = "yes";
		$basket = array();
		$detail = mysql_query("SELECT sd.id_barang, SUM(jml_barang) AS jml_barang, nama_barang, simbol AS satuan
							FROM sp_out_detail sd
							LEFT JOIN ref_barang b ON b.id_barang = sd.id_barang
							LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan
							WHERE sd.id_sp_out = '$row[id]' AND sd.soft_delete = 0
							GROUP BY sd.id_barang");
		while($de = mysql_fetch_assoc($detail)){
			$de['jml_barang'] = number_format($de['jml_barang'], 15, ',', '.');
			array_push($basket, $de);
		}
		
		
		if($row['txtuntuk'] == "Diserahkan Kepada Masyarakat" && $row['stat_untuk'] == "0"){
			$row['jenis_keluar'] = "K";
		} else {
			$row['jenis_keluar'] = "";
		}
		
		$row['basket'] = $basket;
		$row['no'] = $no;
		array_push($items, $row);
		$no++;
	}
	
	echo json_encode($items);
	mysql_close();
	
?>