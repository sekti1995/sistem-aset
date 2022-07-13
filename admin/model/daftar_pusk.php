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
	$clause = "SELECT *  FROM ref_sub2_unit WHERE kd_unit = 2 AND kd_sub <> 1 AND kd_sub2 <> 1 ORDER BY kd_sub, kd_sub2 ASC";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){

		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>