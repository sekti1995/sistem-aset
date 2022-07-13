<?php
//session_start();
//if(!isset($_SESSION['namauser'])){
 //   die("Anda belum login, Bila login klik di <a href=../index.php>sini</a>");//jika belum login jangan lanjut..
//}

	require_once "../../config/db.koneksi.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
	$cari = isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';	
    $where = " where nm_sub2_unit like '%$cari%'";	
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT *, CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kd_skpd FROM ref_unit ORDER BY kd_urusan, kd_bidang, kd_unit ASC";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$cek = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$row[kd_skpd]'");
		$ck = mysql_num_rows($cek);
		if($ck == 1){
			$row['ck'] = '1';
		} else {
			$row['ck'] = '0';
		}
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>