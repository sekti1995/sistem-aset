<?php

	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	$id_masuk = isset($_POST['id']) ? $_POST['id'] : '';
	$idsubj = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$nmkeg = isset($_POST['nama_pengadaan']) ? $_POST['nama_pengadaan'] : '';
	$nmbar = isset($_POST['id_bar']) ? $_POST['id_bar'] : '';
		
	$offset = ($page-1)*$rows;
	$result = array();
	/* $clause = "SELECT IF(d.id_kelompok<=6, nama_barang, nama_barang_kegiatan) nama_bar, d.id_barang AS id_bar, tahun,(SELECT jumlah_barang-jumlah_barang_isi FROM log_import WHERE uuid_skpd = '$idsubj' AND  nm_kegiatan like '%$nmkeg%' AND id_barang='$nmbar') AS jumlah_ren, 
				jml_masuk AS jumlah, IF(d.id_kelompok<7, s.simbol, s1.simbol) nama_sat, 
				IF(d.id_kelompok<7, b.id_satuan, b1.id_satuan) id_sat, harga_masuk AS harga, nama_kelompok AS nama_kel, 
				d.id_kelompok AS id_kel, d.keterangan AS ket, id_masuk_detail AS id,
				IF((SELECT MAX(tgl_transaksi) FROM kartu_stok k WHERE k.id_barang = d.id_barang 
				  AND d.uuid_skpd = k.uuid_skpd AND jml_out <> 0 AND k.soft_delete = 0) > tgl_penerimaan, 1, 0) AS edited
				FROM masuk_detail d
				LEFT JOIN masuk m ON m.id_masuk = d.id_masuk 
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				LEFT JOIN ref_barang_kegiatan b1 ON d.id_barang = b1.id_barang_kegiatan
				LEFT JOIN ref_kelompok k ON d.id_kelompok = k.id_kelompok 
				LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
				LEFT JOIN ref_satuan s1 ON b1.id_satuan = s1.id_satuan 
				WHERE d.id_masuk = '$id_masuk' AND d.soft_delete=0"; */
	$clause = "SELECT  m.uuid_skpd AS id_sub,m.nama_pengadaan,IF(d.id_kelompok<=6, nama_barang, nama_barang_kegiatan) nama_bar, d.id_barang AS id_bar, tahun,m.id_sumber AS id_sumber,
	jml_masuk AS jumlah, IF(d.id_kelompok<7, s.simbol, s1.simbol) nama_sat, 
	IF(d.id_kelompok<7, b.id_satuan, b1.id_satuan) id_sat, harga_masuk AS harga, nama_kelompok AS nama_kel, 
	d.id_kelompok AS id_kel, d.keterangan AS ket, id_masuk_detail AS id,
	IF((SELECT MAX(tgl_transaksi) FROM kartu_stok k WHERE k.id_barang = d.id_barang 
	  AND d.uuid_skpd = k.uuid_skpd AND jml_out <> 0 AND k.soft_delete = 0) > tgl_penerimaan, 1, 0) AS edited
	FROM masuk_detail d
	LEFT JOIN masuk m ON m.id_masuk = d.id_masuk 
	LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
	LEFT JOIN ref_barang_kegiatan b1 ON d.id_barang = b1.id_barang_kegiatan
	LEFT JOIN ref_kelompok k ON d.id_kelompok = k.id_kelompok 
	LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
	LEFT JOIN ref_satuan s1 ON b1.id_satuan = s1.id_satuan 
	WHERE d.id_masuk = '$id_masuk' AND d.soft_delete=0";
		//echo($clause);		
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); $total = 0; $c = 0; $basrinci = array();
	while($row = mysql_fetch_assoc($rs)){
		$clauseT3 = "SELECT jumlah_barang-jumlah_barang_isi as jumlah_ren FROM log_import WHERE uuid_skpd='$row[id_sub]' AND nm_kegiatan LIKE '$row[nama_pengadaan]'
       AND id_barang='$row[id_bar]' ";
       //echo($clauseT3);
		$rsT3 = mysql_query($clauseT3);
		$rowT3 = mysql_fetch_assoc($rsT3);

		$hrg = $row['harga']*$row['jumlah'];
		$row['harga_asli'] = $hrg;
		$row['jumlah'] = number_format($row['jumlah'], 0, ',', '.');
		$row['jumlah_ren'] = number_format($rowT3['jumlah_ren'], 0, ',', '.');
		$row['harga_satuan'] = number_format($row['harga'], 0, ',', '.');
		$row['harga'] = number_format($hrg, 0, ',', '.');
		$row['idbas'] = $c;
		$total += $row['harga_asli'];

	   
		
		if($row['id_kel']==7 || $row['id_kel']==8){
			$sub = mysql_query("SELECT m.id_barang AS id_bar, nama_barang AS nama_bar, jumlah, harga, 
										b.id_satuan AS id_sat, nama_satuan AS nama_sat, id_masuk_detail_rinci AS id, tgl_masuk_rinci as tgl_detail
								FROM masuk_detail_rinci m
								LEFT JOIN ref_barang b ON m.id_barang = b.id_barang
								LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
								WHERE id_masuk_detail = '$row[id]' AND m.soft_delete = 0");
			$rinci['total'] = mysql_num_rows($sub); $ite = array();
			while($r = mysql_fetch_assoc($sub)){
				$hr = $r['harga']*$r['jumlah'];
				$r['tgl_detail'] = balikTanggalIndo($r['tgl_detail']);
				$r['harga_asli'] = $hr;
				$r['harga_satuan'] = number_format($r['harga'], 0, ',', '.');
				$r['jumlah'] = number_format($r['jumlah'], 0, ',', '.');
				$r['harga'] = number_format($hr, 0, ',', '.');
				array_push($ite, $r);
			}
			$rinci['rows'] = $ite;
			$basrinci[$c] = $rinci;
		}
		
		array_push($items, $row);
		$c++;
	}
	
	$result["rows"] = $items;
	$item2 = array();
	$row2['merk_tipe'] = "TOTAL";
	$row2['harga'] = number_format($total,15,',','.');
	array_push($item2, $row2);
	$result["footer"]= $item2;
	$result["rinci"] = $basrinci;
 	echo json_encode($result);
	mysql_close();
?>