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
	
	if($nama!='') $a = "AND nama_barang_kegiatan LIKE '%$nama%'";
	else $a = "";
	if($kode!='') $b = "AND kode = '$kode'";
	else $b = "";
	if($jenis!='') $c = "AND r1.id_jenis = '$jenis'";
	else $c = "";
	if($peran==MD5('1')) $d = "";
	else{
		$d = " AND MD5(r1.uuid_skpd) = '$_SESSION[uidunit]'";
	}
	
	
	$where = "$a $b $c $d";
		
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT r1.id_barang_kegiatan AS id, nama_jenis, nama_barang_kegiatan AS nama_barang, r1.id_satuan, nama_satuan, keterangan,  
				r1.id_jenis, kode, uuid_skpd AS id_unit, ta
				FROM ref_barang_kegiatan r1
				LEFT JOIN ref_jenis r3 ON r1.id_jenis = r3.id_jenis
				LEFT JOIN ref_satuan r2	ON r2.id_satuan = r1.id_satuan 
				WHERE soft_delete=0 $where
				ORDER BY kd_kel, kd_sub, kode";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>