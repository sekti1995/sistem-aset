<?php
//session_start();
//if(!isset($_SESSION['namauser'])){
 //   die("Anda belum login, Bila login klik di <a href=../index.php>sini</a>");//jika belum login jangan lanjut..
//}

	require_once "../../config/db.koneksi.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$cari = isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';	
    $where = " where nm_sub_unit like '%$cari%'";	
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nm_unit, kd_sub, nm_sub_unit, CONCAT_WS('.', r1.kd_urusan, r1.kd_bidang, r1.kd_unit) AS id_unit, 
				CONCAT_WS('.', r1.kd_urusan, r1.kd_bidang, r1.kd_unit, r1.kd_sub) AS id_sub
				FROM ref_sub_unit r1
				LEFT JOIN ref_unit r2
				ON CONCAT_WS('.', r1.kd_urusan, r1.kd_bidang, r1.kd_unit) = CONCAT_WS('.', r2.kd_urusan, r2.kd_bidang, r2.kd_unit)".$where;
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