<?php

	require_once "../../config/db.koneksi.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	$id_sp = isset($_POST['id']) ? $_POST['id'] : '';
	if($id_sp=="") $id_sp = isset($_GET['id']) ? $_GET['id'] : '';
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nama_barang AS nama_bar, o.id_barang AS id_bar, jml_barang AS jumlah, simbol AS nama_sat, 
				b.id_satuan AS id_sat, harga_barang AS harga, o.keterangan AS ket, 
				DATE_FORMAT(tgl_spb, '%d-%m-%Y') AS tgl_minta, id_sp_out_detail AS id
				FROM sp_out_detail o
				LEFT JOIN sp_out t ON t.id_sp_out = o.id_sp_out
				LEFT JOIN surat_minta m ON m.id_surat_minta = t.id_surat_minta 
				LEFT JOIN ref_barang b ON o.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				WHERE o.id_sp_out = '$id_sp' AND o.soft_delete=0";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $total = 0;
	while($row = mysql_fetch_assoc($rs)){
		$jmlhrg = $row['harga']*$row['jumlah'];
		$hrg = $row['harga'];
		$row['jmlhrg_asli'] = $jmlhrg;
		$row['harga_asli'] = $hrg;
		$row['jumlah'] = number_format($row['jumlah'], 0, ',', '.');
		$row['harga'] = number_format($hrg, 0, ',', '.');
		$row['jmlhrg'] = number_format($jmlhrg, 0, ',', '.');
		
		$total += $row['harga_asli'];
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>