<?php
	require_once "../../config/db.koneksi.php";
	require_once "../../config/library.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	$id_sp = isset($_GET['id']) ? $_GET['id'] : '';
	$skpd = isset($_GET['skpd']) ? $_GET['skpd'] : '';
		
	$offset = ($page-1)*$rows;
	$result = array();
	
	$ks = mysql_fetch_assoc(mysql_query("SELECT k.id_gudang, k.id_sumber_dana, nama_gudang, nama_sumber
				FROM kartu_stok k
				LEFT JOIN ref_gudang g ON g.id_gudang = k.id_gudang
				LEFT JOIN ref_sumber_dana s ON s.id_sumber = k.id_sumber_dana WHERE k.uuid_skpd = '$skpd' and k.soft_delete ='0' LIMIT 1"));
	
	$clause = "SELECT nama_barang AS nama_bar, k.id_barang AS id_bar, SUM(jml_barang) AS jumlah, 
				simbol AS nama_sat, b.id_satuan AS id_sat, k.keterangan AS ket,	id_sp_out_detail AS id,
				(SELECT IFNULL(DATE_FORMAT(MAX(tgl_terima), '%d-%m-%Y'), '00-00-0000') FROM keluar_detail kd 
				WHERE kd.id_barang = k.id_barang AND kd.uuid_skpd = k.uuid_skpd AND kd.soft_delete = 0) AS tgl_akhir
				FROM sp_out_detail k
				LEFT JOIN ref_barang b ON k.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				WHERE id_sp_out = '$id_sp' AND k.soft_delete=0 GROUP BY k.id_barang";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause ");
	$items = array(); $total = 0;
	while($row = mysql_fetch_assoc($rs)){
		$row['id_gud'] = $ks['id_gudang'];
		$row['nama_gud'] = $ks['nama_gudang'];
		$row['id_sum'] = $ks['id_sumber_dana'];
		$row['nama_sumber'] = $ks['nama_sumber'];
		$row["j"] = $row['jumlah'];
		$row['jumlah'] = str_replace(".",",",$row['jumlah']); 
		
			$ex = explode(",",$row['jumlah']);
		
			// $row['jumlah'] = number_format($row['jumlah'], 15, ',', '.');
			if((int)$ex[1] >0){
				$row['jumlah'] = number_format($row['j'], 6, ',', '.');
				// $row['jumlah'] = (float)$row['jumlah'];
			} else {
				$row['jumlah'] = str_replace(".","",$row['j']); 
				$row['jumlah'] = number_format($row['j'], 0, ',', '.');
			}
		
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>