<?php
	require_once "../../config/db.koneksi.php";

	$clause = "SELECT id_kelompok AS id_kel, nama_kelompok AS nama_kel FROM ref_kelompok";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_kel'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>