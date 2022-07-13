<?php
session_start();
//if(!isset($_SESSION['namauser'])){
 //   die("Anda belum login, Bila login klik di <a href=../index.php>sini</a>");//jika belum login jangan lanjut..
//}
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	$peran = cekLogin();
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$cari = isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';	
	if($peran!=md5('1')) $id_sub = " AND MD5(p1.uuid_skpd) = '$_SESSION[uidunit]'";
	else $id_sub = "";
	
    $where = " where nama_pejabat like '%$cari%' $id_sub";	
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT p1.id_pejabat, r3.nm_sub2_unit, ta, nama_pejabat, r2.nama_jabatan, r1.nama_golongan, nip ,uuid_skpd AS id_sub2 , p1.id_golongan, p1.id_jabatan
				FROM pejabat p1
				LEFT JOIN ref_sub2_unit r3 
				ON p1.uuid_skpd = r3.uuid_sub2_unit
				LEFT JOIN ref_jabatan r2
				ON p1.id_jabatan=r2.id_jabatan
				LEFT JOIN ref_golongan r1
				ON p1.id_golongan=r1.id_golongan
				".$where;
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