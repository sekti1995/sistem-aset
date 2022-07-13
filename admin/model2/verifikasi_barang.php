<?php

	require_once "../../config/db.koneksi.php";
	require_once "../../config/library.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	$id_sp = isset($_POST['id']) ? $_POST['id'] : '';
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tgl = isset($_POST['tgl']) ? $_POST['tgl'] : '';
	
	if($tgl!="") $tgl = balikTanggal($tgl); else $tgl = date('Y-m-d');
	$offset = ($page-1)*$rows;
	$result = array();
	if($act=='ver'){
		$clause = "SELECT nama_barang AS nama_bar, sd.id_barang AS id_bar, FORMAT(jumlah, 0,'de_DE') AS jmlminta, simbol AS nama_sat, 
					b.id_satuan AS id_sat, FORMAT(jumlah, 0,'de_DE') AS jmlkeluar, id_surat_minta_detail AS id,
					FORMAT(IFNULL((SELECT SUM(jml_in-jml_out) FROM kartu_stok k WHERE k.id_barang = sd.id_barang 
					 AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$tgl' AND k.uuid_skpd = o.uuid_skpd
					 AND k.soft_delete = 0), 0)
					 -IFNULL((SELECT SUM(jml_barang) FROM sp_out_detail od, sp_out so WHERE od.id_sp_out = so.id_sp_out
					 AND od.id_barang = sd.id_barang AND so.uuid_skpd = o.uuid_skpd AND so.id_surat_minta <> sd.id_surat_minta
					 AND od.soft_delete = 0 AND so.soft_delete = 0 AND so.status = 1), 0), 0,'de_DE') AS jmlstok
					FROM surat_minta_detail sd
					LEFT JOIN sp_out o ON o.id_surat_minta = sd.id_surat_minta
					LEFT JOIN ref_barang b ON sd.id_barang = b.id_barang 
					LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
					WHERE sd.id_surat_minta = '$id_sp' AND sd.soft_delete=0";
	}elseif($act=='edt'){
		$clause = "SELECT nama_barang AS nama_bar, o.id_barang AS id_bar, simbol AS nama_sat, b.id_satuan AS id_sat, 
					FORMAT(IFNULL(e.jumlah,0), 0,'de_DE') AS jmlminta, 	FORMAT(jml_barang, 0,'de_DE') AS jmlkeluar,
					FORMAT(IFNULL((SELECT SUM(jml_in-jml_out) FROM kartu_stok k WHERE k.id_barang = o.id_barang 
					 AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= NOW() AND k.uuid_skpd = o.uuid_skpd AND jml_in <> 0 
					 AND k.soft_delete = 0), 0)
					 -IFNULL((SELECT SUM(jml_barang) FROM sp_out_detail od, sp_out so 
					 WHERE od.id_sp_out = so.id_sp_out AND od.id_barang = o.id_barang AND so.uuid_skpd = o.uuid_skpd 
					 AND so.id_surat_minta <> p.id_surat_minta AND od.soft_delete = 0 AND so.soft_delete = 0 
					 AND so.status = 1), 0), 0,'de_DE') AS jmlstok,	id_sp_out_detail AS id
					FROM sp_out_detail o
					LEFT JOIN sp_out p ON p.id_sp_out = o.id_sp_out
					LEFT JOIN surat_minta_detail e ON e.id_surat_minta = p.id_surat_minta AND e.id_barang = o.id_barang
					LEFT JOIN ref_barang b ON o.id_barang = b.id_barang 
					LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
					WHERE p.id_surat_minta = '$id_sp' AND o.soft_delete=0";
	}
	
	//$rs = mysql_query("CALL verifikasi_surat_perintah('$id_sp', '$tgl')");
	$rs = mysql_query("$clause");
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $total = 0;
	while($row = mysql_fetch_assoc($rs)){
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>