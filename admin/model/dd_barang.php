<?php
session_start();

	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
	$kode = isset($_POST['kode']) ? $_POST['kode'] : '';
	$jenis = isset($_POST['jenis']) ? $_POST['jenis'] : '';
	
	if($nama!='') $a = "AND nama_barang LIKE '%$nama%'";
	else $a = "";
	if($kode!='') $b = "AND kd_sub2 = '$kode'";
	else $b = "";
	if($jenis!='') $c = "AND r1.id_jenis = '$jenis'";
	else $c = "";
	
	$where = "$a $b $c";
		
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT r1.id_barang AS id, nama_jenis, nama_barang, r1.id_satuan, nama_satuan, keterangan,  
				r1.id_jenis, kd_sub2, harga_index, r1.jumlah_terkecil, r1.satuan_terkecil, r1.harga_terkecil
				FROM ref_barang r1
				LEFT JOIN ref_jenis r3 
				ON r1.id_jenis = r3.id_jenis
				LEFT JOIN ref_satuan r2
				ON r2.id_satuan = r1.id_satuan 
				WHERE soft_delete=0 $where";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		
		$sat = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_satuan WHERE id_satuan = '$row[satuan_terkecil]' "));
		$row["txt_satuan_terkecil"] = $sat["simbol"];
		
		
		$row['jumlah_terkecil'] = number_format($row['jumlah_terkecil'], 0, ',', '.');
		$row['harga_index'] = number_format($row['harga_index'], 0, ',', '.');
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>