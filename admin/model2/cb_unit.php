<?php
	require_once "../../config/db.koneksi.php";

	$bidang = isset($_GET['id_bidang']) ? "WHERE CONCAT_WS('.', kd_urusan, kd_bidang) ='$_GET[id_bidang]'" : '';
	$clause = "SELECT *, CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) AS id_unit FROM ref_unit $bidang";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_unit'];
		$row['text'] = $row['nm_unit'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>