<?php
	require_once "../../config/db.koneksi.php";
	$tingkat = isset($_GET['tingkat']) ? $_GET['tingkat'] : '';
	
	$clause = "SELECT * FROM ref_akses ORDER BY id_akses";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_akses'];
		$row['text'] = $row['nama_akses'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>