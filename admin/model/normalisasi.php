<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : 'xxx';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$id_bar = isset($_POST['id_bar']) ? $_POST['id_bar'] : '';
	$harga_cari = isset($_POST['harga_cari']) ? $_POST['harga_cari'] : '';
	$kode_cari = isset($_POST['kode_cari']) ? $_POST['kode_cari'] : '';
	$id_sumberd = isset($_POST['id_sumberd']) ? $_POST['id_sumberd'] : '';
	$sd = isset($_POST['sd']) ? $_POST['sd'] : '0';
	 
	
	$harga_cari = str_replace(".","",$harga_cari);
	$harga_cari = str_replace(",",".",$harga_cari);
	 
	if($harga_cari == ''){
		$s1 = " "; 
	} else {
		$s1 = " AND k.harga = '$harga_cari' "; 
	}	
	
	if($id_sumberd == ''){
		$s2 = " "; 
	} else {
		$s2 = " AND k.id_sumber_dana = '$id_sumberd' "; 
	}
	
	
	if($id_bar == ''){
		$s3 = " "; 
	} else {
		$s3 = " AND k.id_barang = '$id_bar' "; 
	}
	
	if($kode_cari == ''){
		$s4 = " "; 
	} else {
		$s4 = " AND k.kode = '$kode_cari' "; 
	}
	 
	
	$grand_total = 0;
	$sub_total = 0;
	$subtotal_in = 0;
	$subtotal_out = 0;
	$subtotal_a = 0;
	$total_in = 0;
	$total_out = 0;
	$total_a = 0;
	$sisa = 0;
	$akumul = 0;
	
	$result = array();
	
	$clause = "SELECT *, k.soft_delete, k.kode AS kode_stok, k.ta AS ta_stok, k.create_date AS cd, k.update_date AS ud FROM kartu_stok k 
				LEFT JOIN ref_barang b ON b.id_barang = k.id_barang
				LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang
				LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis
				LEFT JOIN ref_sumber_dana sd ON k.id_sumber_dana = sd.id_sumber
				WHERE k.soft_delete = '$sd' AND k.uuid_skpd = '$id_sub' $s1 $s2 $s3 $s4
				ORDER BY k.tgl_transaksi, k.id_barang ASC";
				
				
				//echo $clause;			
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $ttotal = 0; $footer = array();
	while($row = mysql_fetch_assoc($rs)){ 
		
		$row['tgl_transaksi'] = balikTanggalIndo($row['tgl_transaksi']);
		if($row['kode_stok'] == 'i' or $row['kode_stok'] == 'r'){
			$subtotal_in = $row['jml_in']*$row['harga'];
			$total_in += $subtotal_in;
		} else if($row['kode_stok'] == 'ok' or $row['kode_stok'] == 'os'){
			$subtotal_out = $row['jml_out']*$row['harga'];
			$total_out += $subtotal_out;
			$akumul += $row['jml_out'];
		} else if($row['kode_stok'] == 'a'){
			$subtotal_a = $row['jml_in']*$row['harga'];
			$total_a += $subtotal_a;
		}
	
		$row['jml_in'] = number_format($row['jml_in'], 2, ',', '.');
		$row['jml_out'] = number_format($row['jml_out'], 2, ',', '.');
		$row['harga'] = number_format($row['harga'], 6, ',', '.');
		$row['akumul'] = number_format($akumul, 2, ',', '.');
		
		
		$ex1 = explode(",",$row['harga']);
		if($ex1[1] > 0){
			$row['harga'] = $ex1[0].",".$ex1[1];
		} else {
			$row['harga'] = $ex1[0];
		}

		array_push($items, $row);
	}
	$result["rows"] = $items;  
	
	$sisa = $total_in+$total_a-$total_out;
	
	$total_in = number_format($total_in, 2, ',', '.');
	$total_out = number_format($total_out, 2, ',', '.');
	$total_a = number_format($total_a, 2, ',', '.');
	$sisa = number_format($sisa, 6, ',', '.');
	
	/* 
	$ex1 = explode(",",$sisa);
	if($ex1[1] > 0){
		$sisa = $ex1[0].",".$ex[1];
	} else {
		$sisa = $ex1[0];
	}
	 */
	
	$foot['id_stok'] = "Saldo Awal : <b>".$total_a."</b>";
	$foot['nama_barang'] = "In : <b>".$total_in."</b>";
	$foot['nama_sumber'] = "Out : <b>".$total_out."</b>";
	$foot['harga'] = "Saldo : <b>".$sisa."</b>";
	
	array_push($footer, $foot);
	$result["footer"] = $footer;
	
	
	echo json_encode($result);
	mysql_close();
?>