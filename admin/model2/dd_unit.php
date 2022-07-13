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
	$clause = "SELECT *, CONCAT_WS('.', u.kd_urusan, u.kd_bidang, u.kd_unit) AS id,
					CONCAT_WS('.', u.kd_urusan, u.kd_bidang) AS id_bidang
				FROM ref_unit u
				LEFT JOIN ref_bidang b ON CONCAT_WS('.', b.kd_urusan, b.kd_bidang) = CONCAT_WS('.', u.kd_urusan, u.kd_bidang) 
				".$where." ORDER BY u.kd_urusan, u.kd_bidang, u.kd_unit ";
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