<?php
//session_start();
//if(!isset($_SESSION['namauser'])){
 //   die("Anda belum login, Bila login klik di <a href=../index.php>sini</a>");//jika belum login jangan lanjut..
//}

	require_once "../../config/db.koneksi.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$cari = isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';	
    $where = " where nm_unit like '%$cari%'";	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT * FROM opd WHERE e = 1 GROUP BY c ORDER BY CAST(c AS UNSIGNED) ASC";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		// mysql_query("INSERT INTO ref_unit(kd_urusan, kd_bidang, kd_unit, nm_unit) VALUES ('$row[kd_urusan]','$row[kd_bidang]','$row[c]','$row[d]')");
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>